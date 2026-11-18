<?php 
namespace App\Http\Controllers; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;



class RajaOngkirController extends Controller
{
    private function api()
    {
        return Http::timeout(30)->withHeaders([
            'key' => env('RAJAONGKIR_API_KEY'),
            'Accept' => 'application/json',
        ]);
    }

    // Mengambil provinsi
    public function getProvinces()
    {
        return $this->requestApi('/destination/province');
    }

    // Mengambil kota berdasarkan provinsi
    public function getCities(Request $request)
    {
        $request->validate([
            'province_id' => 'required|integer',
        ]);

        return $this->requestApi(
            '/destination/city/' . $request->province_id
        );
    }

    // Mengambil kecamatan berdasarkan kota
    public function getDistricts(Request $request)
    {
        $request->validate([
            'city_id' => 'required|integer',
        ]);

        return $this->requestApi(
            '/destination/district/' . $request->city_id
        );
    }

    // Fungsi umum untuk mengambil data tujuan
    private function requestApi($endpoint)
    {
        try {
            $response = $this->api()->get(
                rtrim(env('RAJAONGKIR_BASE_URL'), '/') . $endpoint
            );

            $json = $response->json();

            if (!$response->successful()) {
                Log::error('RajaOngkir Destination Error', [
                    'status' => $response->status(),
                    'response' => $json,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => $json['meta']['message']
                        ?? 'Gagal mengambil data tujuan.',
                ], 422);
            }

            return response()->json([
                'success' => true,
                'data' => $json['data'] ?? [],
            ]);

        } catch (\Throwable $e) {
            Log::error('RajaOngkir Connection Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat terhubung ke RajaOngkir.',
            ], 500);
        }
    }

    // Menghitung ongkos kirim
    public function getCost(Request $request)
    {
        $validated = $request->validate([
            'destination' => 'required|integer',
            'weight' => 'required|integer|min:1',
            'courier' => 'required|in:jne,tiki,pos',
        ]);

        $origin = env('RAJAONGKIR_ORIGIN_DISTRICT_ID');

        if (!$origin) {
            return response()->json([
                'success' => false,
                'message' => 'ID kecamatan asal toko belum diatur di .env',
            ], 422);
        }

        try {
            $response = $this->api()
                ->asForm()
                ->post(
                    rtrim(env('RAJAONGKIR_BASE_URL'), '/') .
                    '/calculate/district/domestic-cost',
                    [
                        'origin' => $origin,
                        'destination' => $validated['destination'],
                        'weight' => $validated['weight'],
                        'courier' => $validated['courier'],
                        'price' => 'lowest',
                    ]
                );

            $json = $response->json();

            Log::info('RajaOngkir Cost Response', [
                'status' => $response->status(),
                'response' => $json,
            ]);

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => $json['meta']['message']
                        ?? 'Gagal menghitung ongkos kirim.',
                ], 422);
            }

            return response()->json([
                'success' => true,
                'data' => $json['data'] ?? [],
            ]);

        } catch (\Throwable $e) {
            Log::error('RajaOngkir Cost Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghitung ongkir.',
            ], 500);
        }
    }
}