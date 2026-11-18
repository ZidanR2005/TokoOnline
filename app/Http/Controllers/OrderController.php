<?php 
 
namespace App\Http\Controllers; 
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Produk;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    // Mengambil data customer yang sedang login
    private function getCustomer()
    {
        return Customer::where('user_id', Auth::id())->first();
    }

    // Menghitung ulang total harga order
    private function calculateTotal(Order $order)
    {
        $total = $order->orderItems()
            ->selectRaw('COALESCE(SUM(quantity * harga), 0) as total')
            ->value('total');

        $order->total_harga = $total;
        $order->save();
    }

    // 1. Menambahkan produk ke keranjang
    public function addToCart($id)
    {
        $customer = $this->getCustomer();

        if (!$customer) {
            return back()->with('error', 'Data customer tidak ditemukan.');
        }

        $produk = Produk::findOrFail($id);

        if ($produk->stok < 1) {
            return back()->with('error', 'Stok produk habis.');
        }

        DB::transaction(function () use ($customer, $produk) {

            $order = Order::firstOrCreate(
                [
                    'customer_id' => $customer->id,
                    'status' => 'pending'
                ],
                [
                    'total_harga' => 0
                ]
            );

            $orderItem = OrderItem::where('order_id', $order->id)
                ->where('produk_id', $produk->id)
                ->first();

            if ($orderItem) {

                if (($orderItem->quantity + 1) > $produk->stok) {
                    throw new \Exception('Jumlah produk melebihi stok tersedia.');
                }

                $orderItem->quantity++;
                $orderItem->save();

            } else {

                OrderItem::create([
                    'order_id' => $order->id,
                    'produk_id' => $produk->id,
                    'quantity' => 1,
                    'harga' => $produk->harga
                ]);
            }

            $this->calculateTotal($order);
        });

        return redirect()->route('order.cart')
            ->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    // 2. Menampilkan keranjang
    public function viewCart()
    {
        $customer = $this->getCustomer();

        if (!$customer) {
            return view('v_order.cart', ['order' => null]);
        }

        $order = Order::where('customer_id', $customer->id)
            ->where('status', 'pending')
            ->with('orderItems.produk')
            ->first();

        return view('v_order.cart', compact('order'));
    }

    // 3. Mengubah jumlah produk
    public function updateCart(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $customer = $this->getCustomer();

        if (!$customer) {
            return back()->with('error', 'Data customer tidak ditemukan.');
        }

        $order = Order::where('customer_id', $customer->id)
            ->where('status', 'pending')
            ->first();

        if (!$order) {
            return back()->with('error', 'Keranjang tidak ditemukan.');
        }

        $orderItem = $order->orderItems()
            ->where('id', $id)
            ->first();

        if (!$orderItem) {
            return back()->with('error', 'Produk dalam keranjang tidak ditemukan.');
        }

        $quantity = (int) $request->quantity;

        if (!$orderItem->produk ||
            $quantity > $orderItem->produk->stok) {

            return redirect()->route('order.cart')
                ->with('error', 'Jumlah produk melebihi stok yang tersedia.');
        }

        $orderItem->quantity = $quantity;
        $orderItem->save();

        $this->calculateTotal($order);

        return redirect()->route('order.cart')
            ->with('success', 'Jumlah produk berhasil diperbarui.');
    }

    // 4. Menghapus produk dari keranjang
    public function removeFromCart(Request $request, $id)
    {
        $customer = $this->getCustomer();

        if (!$customer) {
            return back()->with('error', 'Data customer tidak ditemukan.');
        }

        $order = Order::where('customer_id', $customer->id)
            ->where('status', 'pending')
            ->first();

        if (!$order) {
            return back()->with('error', 'Keranjang tidak ditemukan.');
        }

        $orderItem = $order->orderItems()
            ->where('produk_id', $id)
            ->first();

        if (!$orderItem) {
            return back()->with('error', 'Produk tidak ditemukan.');
        }

        $orderItem->delete();

        if ($order->orderItems()->count() == 0) {
            $order->delete();
        } else {
            $this->calculateTotal($order);
        }

        return redirect()->route('order.cart')
            ->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    // 5. Memilih pengiriman
    
// 5. Memilih pengiriman
public function selectShipping()
{
    $customer = $this->getCustomer();

    if (!$customer) {
        return back()->with('error', 'Data customer tidak ditemukan.');
    }

    $order = Order::where('customer_id', $customer->id)
        ->where('status', 'pending')
        ->with('orderItems.produk')
        ->first();

    if (!$order || $order->orderItems->isEmpty()) {
        return redirect()->route('order.cart')
            ->with('error', 'Keranjang belanja kosong.');
    }

    // Menghitung total berat seluruh produk dalam gram
    $totalBerat = $order->orderItems->sum(function ($item) {
        return ($item->produk->berat ?? 0) * $item->quantity;
    });

    // Menghitung total harga produk
    $totalHarga = $order->orderItems->sum(function ($item) {
        return $item->quantity * $item->harga;
    });

    if ($totalBerat < 1) {
        return redirect()->route('order.cart')
            ->with('error', 'Berat produk belum diatur atau tidak valid.');
    }

    return view('v_order.select_shipping', compact(
        'order',
        'totalBerat',
        'totalHarga'
    ));
}
public function selectPayment()
{
    $customer = $this->getCustomer();

    $order = Order::where('customer_id', $customer->id)
        ->where('status', 'pending')
        ->with('orderItems.produk')
        ->first();

    if (!$order || $order->orderItems->isEmpty()) {
        return redirect()->route('order.cart');
    }

    return view('v_order.selectpayment', compact('order'));
}



    // 6. Menyimpan data ongkos kirim
    public function updateongkir(Request $request)
    {
        $request->validate([
            'kurir' => 'required|string',
            'layanan_ongkir' => 'required|string',
            'biaya_ongkir' => 'required|numeric|min:0',
            'estimasi_ongkir' => 'nullable|string',
            'total_berat' => 'required|numeric|min:1',
            'alamat' => 'required|string',
            'city_name' => 'required|string',
            'province_name' => 'required|string',
            'pos' => 'required|string'
        ]);

        $customer = $this->getCustomer();

        if (!$customer) {
            return back()->with('error', 'Data customer tidak ditemukan.');
        }

        $order = Order::where('customer_id', $customer->id)
            ->where('status', 'pending')
            ->with('orderItems')
            ->first();

        if (!$order || $order->orderItems->isEmpty()) {
            return redirect()->route('order.cart')
                ->with('error', 'Keranjang belanja kosong.');
        }

        $order->kurir = $request->kurir;
        $order->layanan_ongkir = $request->layanan_ongkir;
        $order->biaya_ongkir = $request->biaya_ongkir;
        $order->estimasi_ongkir = $request->estimasi_ongkir;
        $order->total_berat = $request->total_berat;

        // Simpan alamat sebagai teks biasa
        $order->alamat = $request->alamat . ', ' .
            $request->city_name . ', ' .
            $request->province_name;

        $order->pos = $request->pos;

        $order->save();

        return redirect()->route('order.selectpayment');
    }
    // Menyelesaikan pesanan dan mengubah status menjadi Proses
public function bayarSekarang()
{
    $customer = $this->getCustomer();

    if (!$customer) {
        return back()->with('error', 'Data customer tidak ditemukan.');
    }

    $order = Order::where('customer_id', $customer->id)
        ->where('status', 'pending')
        ->with('orderItems')
        ->first();

    if (!$order || $order->orderItems->isEmpty()) {
        return redirect()->route('order.cart')
            ->with('error', 'Pesanan tidak ditemukan.');
    }

    if (!$order->kurir || $order->biaya_ongkir === null) {
        return back()->with('error', 'Silakan pilih pengiriman terlebih dahulu.');
    }

    DB::transaction(function () use ($order) {
        $order->status = 'Proses';
        $order->save();
    });

    return redirect()->route('order.history')
        ->with('success', 'Pesanan berhasil dibuat.');
}
  public function history()
{
    $customer = $this->getCustomer();

    if (!$customer) {
        return back()->with('error', 'Data customer tidak ditemukan.');
    }

    $orders = Order::where('customer_id', $customer->id)
        ->where('status', '!=', 'pending')
        ->with('orderItems.produk')
        ->orderBy('created_at', 'desc')
        ->get();

    return view('v_order.history', compact('orders'));
}

    // 7. Mengambil data provinsi
    public function getProvinces()
    {
        $response = Http::withHeaders([
            'key' => env('RAJAONGKIR_API_KEY')
        ])->acceptJson()
          ->timeout(30)
          ->get(rtrim(env('RAJAONGKIR_BASE_URL'), '/') . '/provinces');

        return response()->json(
            $response->json() ?? ['error' => 'Gagal mengambil data provinsi'],
            $response->status()
        );
    }

    // 8. Mengambil data kota berdasarkan provinsi
    public function getCities(Request $request)
    {
        $request->validate([
            'province_id' => 'required'
        ]);

        $response = Http::withHeaders([
            'key' => env('RAJAONGKIR_API_KEY')
        ])->acceptJson()
          ->timeout(30)
          ->get(rtrim(env('RAJAONGKIR_BASE_URL'), '/') . '/cities', [
              'province_id' => $request->province_id
          ]);

        return response()->json(
            $response->json() ?? ['error' => 'Gagal mengambil data kota'],
            $response->status()
        );
    }


    // 9. Menghitung ongkos kirim
    public function getCost(Request $request)
    {
        $request->validate([
            'origin' => 'required',
            'destination' => 'required',
            'weight' => 'required|numeric|min:1',
            'courier' => 'required|string'
        ]);

        $response = Http::withHeaders([
            'key' => env('RAJAONGKIR_API_KEY')
        ])->acceptJson()
          ->timeout(30)
          ->post(rtrim(env('RAJAONGKIR_BASE_URL'), '/') . '/cost', [
              'origin' => $request->origin,
              'destination' => $request->destination,
              'weight' => $request->weight,
              'courier' => $request->courier
          ]);

        return response()->json(
            $response->json() ?? ['error' => 'Gagal menghitung ongkos kirim'],
            $response->status()
        );
    }
}