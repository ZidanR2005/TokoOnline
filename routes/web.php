
<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BerandaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RajaOngkirController;


// ==================================================
// ROUTE UTAMA
// ==================================================

Route::get('/', function () {
    return redirect()->route('beranda');
});


// ==================================================
// BACKEND
// ==================================================

// Halaman Beranda Backend
Route::get('backend/beranda', [BerandaController::class, 'berandaBackend'])
    ->name('backend.beranda')
    ->middleware('auth');

// Login Backend
Route::get('backend/login', [LoginController::class, 'loginBackend'])
    ->name('backend.login');

Route::post('backend/login', [LoginController::class, 'authenticateBackend'])
    ->name('backend.login');

Route::post('backend/logout', [LoginController::class, 'logoutBackend'])
    ->name('backend.logout');


// ==================================================
// CRUD USER
// ==================================================

Route::resource('backend/user', UserController::class, [
    'as' => 'backend'
])->middleware('auth');

// Laporan User
Route::get('backend/laporan/formuser', [UserController::class, 'formUser'])
    ->name('backend.laporan.formuser')
    ->middleware('auth');

Route::post('backend/laporan/cetakuser', [UserController::class, 'cetakUser'])
    ->name('backend.laporan.cetakuser')
    ->middleware('auth');


// ==================================================
// CRUD KATEGORI
// ==================================================

Route::resource('backend/kategori', KategoriController::class, [
    'as' => 'backend'
])->middleware('auth');


// ==================================================
// CRUD PRODUK
// ==================================================

Route::resource('backend/produk', ProdukController::class, [
    'as' => 'backend'
])->middleware('auth');

// Menambahkan Foto Produk
Route::post('foto-produk/store', [ProdukController::class, 'storeFoto'])
    ->name('backend.foto_produk.store')
    ->middleware('auth');

// Menghapus Foto Produk
Route::delete('foto-produk/{id}', [ProdukController::class, 'destroyFoto'])
    ->name('backend.foto_produk.destroy')
    ->middleware('auth');

// Laporan Produk
Route::get('backend/laporan/formproduk', [ProdukController::class, 'formProduk'])
    ->name('backend.laporan.formproduk')
    ->middleware('auth');

Route::post('backend/laporan/cetakproduk', [ProdukController::class, 'cetakProduk'])
    ->name('backend.laporan.cetakproduk')
    ->middleware('auth');


// ==================================================
// FRONTEND PRODUK
// ==================================================

Route::get('/produk/detail/{id}', [ProdukController::class, 'detail'])
    ->name('produk.detail');

Route::get('/produk/kategori/{id}', [ProdukController::class, 'produkKategori'])
    ->name('produk.kategori');

Route::get('/produk', [ProdukController::class, 'index'])
    ->name('produk.all');


// ==================================================
// FRONTEND BERANDA
// ==================================================

Route::get('/beranda', [BerandaController::class, 'index'])
    ->name('beranda');


// ==================================================
// LOGIN GOOGLE CUSTOMER
// ==================================================

Route::get('/auth/redirect', [CustomerController::class, 'redirect'])
    ->name('auth.redirect');

Route::get('/auth/google/callback', [CustomerController::class, 'callback'])
    ->name('auth.callback');


// ==================================================
// LOGOUT CUSTOMER
// ==================================================

Route::post('/logout', [CustomerController::class, 'logout'])
    ->name('logout');

Route::post('/customer/logout', [CustomerController::class, 'logout'])
    ->name('customer.logout');


// ==================================================
// CRUD CUSTOMER BACKEND
// ==================================================

Route::resource('backend/customer', CustomerController::class, [
    'as' => 'backend'
])->middleware('auth');


// ==================================================
// AKUN CUSTOMER DAN KERANJANG
// ==================================================
// ==================================================
// AKUN CUSTOMER, KERANJANG DAN PENGIRIMAN
// ==================================================

Route::middleware('is.customer')->group(function () {

    // AKUN CUSTOMER
    Route::get('/customer/akun/{id}', [CustomerController::class, 'akun'])
        ->name('customer.akun');

    Route::put('/customer/updateakun/{id}', [CustomerController::class, 'updateAkun'])
        ->name('customer.updateakun');

    // KERANJANG
    Route::post('add-to-cart/{id}', [OrderController::class, 'addToCart'])
        ->name('order.addToCart');

    Route::get('cart', [OrderController::class, 'viewCart'])
        ->name('order.cart');

    Route::post('cart/update/{id}', [OrderController::class, 'updateCart'])
        ->name('order.updateCart');

    Route::post('remove/{id}', [OrderController::class, 'removeFromCart'])
        ->name('order.remove');

    // HALAMAN PILIH PENGIRIMAN
 Route::get('/select-shipping', [OrderController::class, 'selectShipping'])
    ->name('order.selectShipping');

    // API RAJAONGKIR V2
    Route::get('/provinces', [RajaOngkirController::class, 'getProvinces'])
        ->name('ongkir.provinces');

    Route::get('/cities', [RajaOngkirController::class, 'getCities'])
        ->name('ongkir.cities');

    Route::get('/districts', [RajaOngkirController::class, 'getDistricts'])
        ->name('ongkir.districts');

    Route::post('/cost', [RajaOngkirController::class, 'getCost'])
        ->name('ongkir.cost');

    // SIMPAN ONGKIR
    Route::post('/updateongkir', [OrderController::class, 'updateongkir'])
        ->name('order.updateongkir');

        Route::get('selectpayment', [OrderController::class, 'selectPayment'])
    ->name('order.selectpayment');
    // History Pesanan
// History Pesanan
// Bayar Sekarang
Route::post('/bayar-sekarang', [OrderController::class, 'bayarSekarang'])
    ->name('order.bayarSekarang');

// History Pesanan
Route::get('/history', [OrderController::class, 'history'])
    ->name('order.history');
});

// ==================================================
// RAJAONGKIR V2
// ==================================================

// Halaman Cek Ongkir
//Route::get('/cek-ongkir', function () {
  //  return view('ongkir');
//})->name('ongkir.index');

// Mengambil Data Provinsi
//Route::get('/provinces', [RajaOngkirController::class, 'getProvinces'])
  //  ->name('ongkir.provinces');

// Mengambil Data Kota Berdasarkan Provinsi
//Route::get('/cities', [RajaOngkirController::class, 'getCities'])
  //  ->name('ongkir.cities');

// Mengambil Data Kecamatan Berdasarkan Kota
//Route::get('/districts', [RajaOngkirController::class, 'getDistricts'])
  //  ->name('ongkir.districts');

// Menghitung Ongkos Kirim
//Route::post('/cost', [RajaOngkirController::class, 'getCost'])
  //  ->name('ongkir.cost');
