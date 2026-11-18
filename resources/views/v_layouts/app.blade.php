
@php
    $kategori = \Illuminate\Support\Facades\DB::table('kategori')
        ->orderBy('nama_kategori', 'asc')
        ->get();
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" sizes="16x16"
          href="{{ asset('image/icon_univ_bsi.png') }}">

    <title>{{ $judul ?? 'tokoonline' }}</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Hind:400,700" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/nouislider.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
</head>

<body>

    <!-- HEADER -->
    <header>
        <div id="top-header">
            <div class="container">
                <div class="pull-left">
                    <span>Selamat datang di toko online</span>
                </div>
            </div>
        </div>

        <div id="header">
            <div class="container">

                <!-- Logo -->
          

                <!-- Menu akun -->
                <div class="pull-right">
                    <ul class="header-btns">

                        <!-- Keranjang -->
                        <li class="header-cart">
                            <a href="#" title="Keranjang">
                                <div class="header-btns-icon">
                                    <i class="fa fa-shopping-cart"></i>
                                </div>
                                <strong class="text-uppercase">Keranjang</strong>
                            </a>
                        </li>

                        <!-- Akun -->
                        @auth
                            <li class="header-account dropdown default-dropdown">
                                <a href="#"
                                   class="dropdown-toggle"
                                   data-toggle="dropdown"
                                   aria-expanded="false">
                                    <div class="header-btns-icon">
                                        <i class="fa fa-user-o"></i>
                                    </div>

                                    <strong class="text-uppercase">
                                        {{ auth()->user()->nama }}
                                        <i class="fa fa-caret-down"></i>
                                    </strong>
                                </a>

                                <ul class="custom-menu">
                                    <li>
                                        <a href="{{ route('customer.akun', ['id' => auth()->id()]) }}">
                                            <i class="fa fa-user-o"></i>
                                            Akun Saya
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#">
                                            <i class="fa fa-check"></i>
                                            History
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#"
                                           onclick="event.preventDefault(); document.getElementById('keluar-app').submit();">
                                            <i class="fa fa-power-off"></i>
                                            Keluar
                                        </a>

                                        <form id="keluar-app"
                                              action="{{ route('customer.logout') }}"
                                              method="POST"
                                              style="display: none;">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <li class="header-account">
                                <div class="header-btns-icon">
                                    <i class="fa fa-user-o"></i>
                                </div>

                                <strong class="text-uppercase">Akun Saya</strong>

                                <a href="{{ route('auth.redirect') }}"
                                   class="text-uppercase">
                                    Login Google
                                </a>
                            </li>
                        @endauth

                        <!-- Tombol navigasi mobile -->
                        <li class="nav-toggle">
                            <button class="nav-toggle-btn main-btn icon-btn"
                                    type="button"
                                    aria-label="Buka navigasi">
                                <i class="fa fa-bars"></i>
                            </button>
                        </li>

                    </ul>
                </div>

            </div>
        </div>
    </header>
    <!-- /HEADER -->


    <!-- NAVIGATION -->
    <div id="navigation">
        <div class="container">
            <div id="responsive-nav">

                <!-- Navigasi kategori -->
                <div class="category-nav {{ request()->segment(1) == '' || request()->segment(1) == 'beranda' ? '' : 'show-on-click' }}">
                    <span class="category-header">
                        Kategori <i class="fa fa-list"></i>
                    </span>

                    <ul class="category-list">
                        @forelse ($kategori as $row)
                            <li>
                                <a href="{{ route('produk.kategori', ['id' => $row->id]) }}">
                                    {{ $row->nama_kategori }}
                                </a>
                            </li>
                        @empty
                            <li>
                                <a href="#">Belum ada kategori</a>
                            </li>
                        @endforelse
                    </ul>
                </div>

                <!-- Menu utama -->
                <div class="menu-nav">
                    <span class="menu-header">
                        Menu <i class="fa fa-bars"></i>
                    </span>

                    <ul class="menu-list">
                        <li>
                            <a href="{{ route('beranda') }}">Beranda</a>
                        </li>
                        <li>
                            <a href="{{ route('produk.all') }}">Produk</a>
                        </li>
                        <li>
    <a href="{{ route('order.history') }}">
        HISTORY PESANAN
    </a>
</li>
                        <li>
                            <a href="#">Lokasi</a>
                        </li>
                        <li>
                            <a href="#">Hubungi Kami</a>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
    <!-- /NAVIGATION -->


    <!-- BANNER BERANDA -->
    @if (request()->segment(1) == '' || request()->segment(1) == 'beranda')
        <div id="home">
            <div class="container">
                <div class="home-wrap">
                    <div id="home-slick">

                        <div class="banner banner-1">
                            <img src="{{ asset('frontend/banner/banner01.jpg') }}"
                                 alt="Jajanan Tradisional">

                            <div class="banner-caption text-center">
                                <h1>Jajanan Tradisional</h1>
                                <h3 class="font-weak" style="color: #30323a;">
                                    Khas Makanan Indonesia
                                </h3>
                                <a href="{{ route('produk.all') }}" class="primary-btn">
                                    Pesan Sekarang
                                </a>
                            </div>
                        </div>

                        <div class="banner banner-1">
                            <img src="{{ asset('frontend/banner/banner02.jpg') }}"
                                 alt="Makanan Indonesia">

                            <div class="banner-caption">
                                <h1 class="primary-color">
                                    Khas Makanan Indonesia<br>
                                    <span class="white-color font-weak">
                                        Jajanan Tradisional
                                    </span>
                                </h1>
                                <a href="{{ route('produk.all') }}" class="primary-btn">
                                    Pesan Sekarang
                                </a>
                            </div>
                        </div>

                        <div class="banner banner-1">
                            <img src="{{ asset('frontend/banner/banner03.jpg') }}"
                                 alt="Produk makanan Indonesia">

                            <div class="banner-caption">
                                <h1 style="color: #f8694a;">
                                    Khas Makanan <span>Indonesia</span>
                                </h1>
                                <a href="{{ route('produk.all') }}" class="primary-btn">
                                    Pesan Sekarang
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- /BANNER BERANDA -->


    <!-- KONTEN HALAMAN -->
    <div class="section">
        <div class="container">
            <div class="row">

                <!-- SIDEBAR -->
                <div id="aside" class="col-md-3">

                    <!-- Produk unggulan contoh -->
                    <div class="aside">
                        <h3 class="aside-title">Top Rated Product</h3>

                        <div class="product product-widget">
                            <div class="product-thumb">
                                <img src="{{ asset('frontend/img/thumb-product01.jpg') }}"
                                     alt="Produk unggulan">
                            </div>

                            <div class="product-body">
                                <h2 class="product-name">
                                    <a href="{{ route('produk.all') }}">
                                        Lihat Produk
                                    </a>
                                </h2>
                            </div>
                        </div>
                    </div>

                    <!-- Filter kategori -->
                    <div class="aside">
                        <h3 class="aside-title">Filter Kategori</h3>

                        <ul class="list-links">
                            @forelse ($kategori as $row)
                                <li>
                                    <a href="{{ route('produk.kategori', ['id' => $row->id]) }}">
                                        {{ $row->nama_kategori }}
                                    </a>
                                </li>
                            @empty
                                <li>Belum ada kategori.</li>
                            @endforelse
                        </ul>
                    </div>

                </div>
                <!-- /SIDEBAR -->


                <!-- KONTEN UTAMA -->
                <div id="main" class="col-md-9">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('msgError'))
                        <div class="alert alert-danger">
                            {{ session('msgError') }}
                        </div>
                    @endif

                    @yield('content')
                </div>
                <!-- /KONTEN UTAMA -->

            </div>
        </div>
    </div>
    <!-- /KONTEN HALAMAN -->


    <!-- FOOTER -->
    <footer id="footer" class="section section-grey">
        <div class="container">
            <div class="row">

                <div class="col-md-3 col-sm-6 col-xs-6">
                    <div class="footer">
                        <div class="footer-logo">
                            <a class="logo" href="{{ route('beranda') }}">
                                <img src="{{ asset('image/logo.png') }}"
                                     alt="Logo Toko Online">
                            </a>
                        </div>

                        <p>
                            Selamat datang di toko online kami.
                            Temukan berbagai produk pilihan untuk Anda.
                        </p>

                        <ul class="footer-social">
                            <li><a href="#" aria-label="Facebook"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="#" aria-label="Twitter"><i class="fa fa-twitter"></i></a></li>
                            <li><a href="#" aria-label="Instagram"><i class="fa fa-instagram"></i></a></li>
                            <li><a href="#" aria-label="Google"><i class="fa fa-google-plus"></i></a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 col-xs-6">
                    <div class="footer">
                        <h3 class="footer-header">Akun Saya</h3>
                        <ul class="list-links">
                            @auth
                                <li>
                                    <a href="{{ route('customer.akun', ['id' => auth()->id()]) }}">
                                        Akun Saya
                                    </a>
                                </li>
                            @else
                                <li>
                                    <a href="{{ route('auth.redirect') }}">
                                        Login Google
                                    </a>
                                </li>
                            @endauth

                            <li><a href="{{ route('produk.all') }}">Produk</a></li>
                            <li><a href="#">History</a></li>
                        </ul>
                    </div>
                </div>

                <div class="clearfix visible-sm visible-xs"></div>

                <div class="col-md-3 col-sm-6 col-xs-6">
                    <div class="footer">
                        <h3 class="footer-header">Layanan Pelanggan</h3>
                        <ul class="list-links">
                            <li><a href="#">Tentang Kami</a></li>
                            <li><a href="#">Pengiriman dan Pengembalian</a></li>
                            <li><a href="#">Panduan Belanja</a></li>
                            <li><a href="#">FAQ</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 col-xs-6">
                    <div class="footer">
                        <h3 class="footer-header">Tetap Terhubung</h3>
                        <p>Dapatkan informasi terbaru dari toko online kami.</p>

                        <form action="#" method="GET">
                            <div class="form-group">
                                <input type="email"
                                       class="input"
                                       placeholder="Masukkan alamat email"
                                       aria-label="Alamat email">
                            </div>
                            <button type="submit" class="primary-btn">
                                Berlangganan
                            </button>
                        </form>
                    </div>
                </div>

            </div>

            <hr>

            <div class="row">
                <div class="col-md-8 col-md-offset-2 text-center">
                    <div class="footer-copyright">
                        Copyright &copy; {{ date('Y') }} Toko Online.
                        All rights reserved.
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- /FOOTER -->


    <!-- JAVASCRIPT -->
    <script src="{{ asset('frontend/js/jquery.min.js') }}"></script>
    <script src="{{ asset('frontend/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('frontend/js/slick.min.js') }}"></script>
    <script src="{{ asset('frontend/js/nouislider.min.js') }}"></script>
    <script src="{{ asset('frontend/js/jquery.zoom.min.js') }}"></script>
    <script src="{{ asset('frontend/js/main.js') }}"></script>

</body>
</html>
