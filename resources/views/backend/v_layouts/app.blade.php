
<!DOCTYPE html>
<html lang="id" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="Dashboard Admin">
    <meta name="author" content="">

    <link rel="icon" type="image/png" sizes="16x16"
        href="{{ asset('backend/images/favicon.png') }}">

    <title>Dashboard Admin - Toko Online</title>

    <!-- CSS Matrix Admin -->
    <link rel="stylesheet"
        href="{{ asset('backend/extra-libs/multicheck/multicheck.css') }}">

    <link rel="stylesheet"
        href="{{ asset('backend/libs/datatables.net-bs4/css/dataTables.bootstrap4.css') }}">

    <link rel="stylesheet"
        href="{{ asset('backend/dist/css/style.min.css') }}">

    <style>
        /* ==============================
           NAVBAR
        ============================== */

        .topbar .navbar-header {
            min-height: 65px;
        }

        .topbar .navbar {
            min-height: 65px;
        }

        .topbar .navbar-nav .nav-link {
            display: flex;
            align-items: center;
        }

        /* ==============================
           FOTO PROFIL NAVBAR
        ============================== */

        .user-profile {
            display: flex;
            align-items: center;
        }

        .user-profile-toggle {
            display: flex !important;
            align-items: center;
            justify-content: center;
            gap: 8px;
            height: 65px;
            padding: 8px 14px !important;
            text-decoration: none !important;
        }

        .profile-image {
            display: block;
            width: 40px;
            height: 40px;
            min-width: 40px;
            border-radius: 50%;
            object-fit: cover;
            object-position: center;
            border: 2px solid #ffffff;
            background: #f1f5f9;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.12);
        }

        .profile-arrow {
            font-size: 18px;
            color: #ffffff;
            margin-left: 2px;
        }

        /* ==============================
           DROPDOWN PROFIL
        ============================== */

        .user-profile-menu {
            min-width: 260px;
            padding: 8px 0;
            margin-top: 4px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        .profile-info {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
        }

        .profile-image-large {
            width: 50px;
            height: 50px;
            min-width: 50px;
            border: 2px solid #e5e7eb;
            box-shadow: none;
        }

        .profile-name {
            display: flex;
            flex-direction: column;
            min-width: 0;
            flex: 1;
        }

        .profile-name strong {
            display: block;
            color: #343a40;
            font-size: 14px;
            font-weight: 600;
            overflow-wrap: anywhere;
            line-height: 1.5;
        }

        .profile-name small {
            display: block;
            margin-top: 3px;
            color: #888888;
            font-size: 12px;
            overflow-wrap: anywhere;
            line-height: 1.4;
        }

        .user-profile-menu .dropdown-divider {
            margin: 5px 0;
        }

        .user-profile-menu .dropdown-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 11px 16px;
            color: #555555;
            font-size: 14px;
            transition: background-color 0.2s ease;
        }

        .user-profile-menu .dropdown-item:hover {
            background-color: #f4f6f9;
            color: #2962ff;
        }

        .user-profile-menu .dropdown-item i {
            width: 20px;
            font-size: 16px;
        }

        .profile-logout:hover {
            color: #dc3545 !important;
        }

        /* ==============================
           SIDEBAR
        ============================== */

        .sidebar-nav ul .sidebar-item .sidebar-link {
            cursor: pointer;
        }

        .sidebar-nav .hide-menu {
            white-space: normal;
        }

        /* ==============================
           KONTEN
        ============================== */

        .page-wrapper {
            min-height: 100vh;
        }

        .container-fluid {
            padding-top: 20px;
            padding-bottom: 25px;
        }

        .footer {
            padding: 15px;
        }

        /* ==============================
           RESPONSIVE MOBILE
        ============================== */

        @media (max-width: 767px) {
            .topbar .navbar-header {
                width: 100%;
            }

            .user-profile-toggle {
                height: 55px;
                padding: 8px !important;
            }

            .profile-image {
                width: 35px;
                height: 35px;
                min-width: 35px;
            }

            .user-profile-menu {
                min-width: 240px;
                right: 0;
            }

            .container-fluid {
                padding: 15px;
            }
        }
    </style>
</head>

<body>

    <!-- PRELOADER -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>

    <!-- MAIN WRAPPER -->
    <div id="main-wrapper">

        <!-- ==============================
             TOPBAR
        ============================== -->

        <header class="topbar" data-navbarbg="skin5">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">

                <!-- LOGO -->
                <div class="navbar-header" data-logobg="skin5">

                    <a class="nav-toggler waves-effect waves-light
                        d-block d-md-none" href="javascript:void(0)">
                        <i class="ti-menu ti-close"></i>
                    </a>

                    <a class="navbar-brand" href="{{ route('backend.beranda') }}">

                        <b class="logo-icon p-l-10">
                            <img
                                src="{{ asset('backend/images/image/icon_univ_bsi.png') }}"
                                alt="Logo"
                                class="light-logo"
                                style="width: 35px; height: 35px; object-fit: contain;">
                        </b>

                        <span class="logo-text">
                            <img
                                src="{{ asset('backend/images/image/logo_text.png') }}"
                                alt="Logo Toko Online"
                                class="light-logo"
                                style="max-width: 140px; max-height: 40px; object-fit: contain;">
                        </span>

                    </a>

                    <a class="topbartoggler d-block d-md-none
                        waves-effect waves-light"
                        href="javascript:void(0)"
                        data-toggle="collapse"
                        data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent"
                        aria-expanded="false"
                        aria-label="Toggle navigation">

                        <i class="ti-more"></i>
                    </a>

                </div>
                <!-- END LOGO -->

                <!-- NAVBAR CONTENT -->
                <div class="navbar-collapse collapse"
                    id="navbarSupportedContent"
                    data-navbarbg="skin5">

                    <!-- LEFT NAVIGATION -->
                    <ul class="navbar-nav float-left mr-auto">

                        <li class="nav-item d-none d-md-block">
                            <a class="nav-link sidebartoggler waves-effect waves-light"
                                href="javascript:void(0)"
                                data-sidebartype="mini-sidebar">

                                <i class="mdi mdi-menu font-24"></i>
                            </a>
                        </li>

                    </ul>

                    <!-- RIGHT NAVIGATION -->
                    <ul class="navbar-nav float-right">

                        <!-- ==============================
                             USER PROFILE
                        ============================== -->

                        <li class="nav-item dropdown user-profile">

                            <a class="nav-link dropdown-toggle user-profile-toggle"
                                href="#"
                                id="userProfileDropdown"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false">

                                @auth
                                    @if (Auth::user()->foto)
                                        <img
                                            src="{{ asset('storage/img-user/' . Auth::user()->foto) }}"
                                            alt="Foto Profil"
                                            class="profile-image">
                                    @else
                                        <img
                                            src="{{ asset('backend/images/image/default-user.png') }}"
                                            alt="Foto Profil Default"
                                            class="profile-image">
                                    @endif
                                @else
                                    <img
                                        src="{{ asset('backend/images/image/default-user.png') }}"
                                        alt="Foto Profil"
                                        class="profile-image">
                                @endauth

                                <i class="mdi mdi-chevron-down profile-arrow"></i>

                            </a>

                            <!-- DROPDOWN MENU -->
                            <div class="dropdown-menu dropdown-menu-right
                                user-profile-menu animated fadeIn"
                                aria-labelledby="userProfileDropdown">

                                @auth

                                    <!-- USER INFORMATION -->
                                    <div class="profile-info">

                                        @if (Auth::user()->foto)
                                            <img
                                                src="{{ asset('storage/img-user/' . Auth::user()->foto) }}"
                                                alt="Foto Profil"
                                                class="profile-image profile-image-large">
                                        @else
                                            <img
                                                src="{{ asset('backend/images/image/default-user.png') }}"
                                                alt="Foto Profil Default"
                                                class="profile-image profile-image-large">
                                        @endif

                                        <div class="profile-name">

                                            <strong>
                                                {{ Auth::user()->nama ?? Auth::user()->name ?? 'Pengguna' }}
                                            </strong>

                                            <small>
                                                {{ Auth::user()->email }}
                                            </small>

                                        </div>

                                    </div>

                                    <div class="dropdown-divider"></div>

                                    <!-- PROFILE LINK -->
                                    <a class="dropdown-item"
                                        href="{{ route('backend.user.edit', ['user' => Auth::user()->id]) }}">

                                        <i class="ti-user"></i>
                                        <span>Profil Saya</span>

                                    </a>

                                @endauth

                                <!-- LOGOUT -->
                                <a class="dropdown-item profile-logout"
                                    href="{{ route('backend.logout') }}"
                                    onclick="event.preventDefault();
                                    document.getElementById('keluar-app').submit();">

                                    <i class="fa fa-power-off"></i>
                                    <span>Keluar</span>

                                </a>

                            </div>
                            <!-- END DROPDOWN -->

                        </li>

                        <!-- END USER PROFILE -->

                    </ul>

                </div>
                <!-- END NAVBAR CONTENT -->

            </nav>
        </header>

        <!-- END TOPBAR -->


        <!-- ==============================
             SIDEBAR
        ============================== -->

        <aside class="left-sidebar" data-sidebarbg="skin5">

            <div class="scroll-sidebar">

                <nav class="sidebar-nav">

                    <ul id="sidebarnav" class="p-t-30">

                        <!-- BERANDA -->
                        <li class="sidebar-item">

                            <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="{{ route('backend.beranda') }}"
                                aria-expanded="false">

                                <i class="mdi mdi-view-dashboard"></i>
                                <span class="hide-menu">Beranda</span>

                            </a>

                        </li>

                        <!-- USER -->
                        <li class="sidebar-item">

                            <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="{{ route('backend.user.index') }}"
                                aria-expanded="false">

                                <i class="mdi mdi-account"></i>
                                <span class="hide-menu">User</span>

                            </a>

                        </li>

                        <!-- CUSTOMER -->
                        <li class="sidebar-item">

                            <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="{{ route('backend.customer.index') }}"
                                aria-expanded="false">

                                <i class="mdi mdi-account-multiple"></i>
                                <span class="hide-menu">Customer</span>

                            </a>

                        </li>

                        <!-- DATA PRODUK -->
                        <li class="sidebar-item">

                            <a class="sidebar-link has-arrow waves-effect waves-dark"
                                href="javascript:void(0)"
                                aria-expanded="false">

                                <i class="mdi mdi-receipt"></i>
                                <span class="hide-menu">Data Produk</span>

                            </a>

                            <ul aria-expanded="false" class="collapse first-level">

                                <!-- KATEGORI -->
                                <li class="sidebar-item">

                                    <a href="{{ route('backend.kategori.index') }}"
                                        class="sidebar-link">

                                        <i class="mdi mdi-chevron-right"></i>
                                        <span class="hide-menu">Kategori</span>

                                    </a>

                                </li>

                                <!-- PRODUK -->
                                <li class="sidebar-item">

                                    <a href="{{ route('backend.produk.index') }}"
                                        class="sidebar-link">

                                        <i class="mdi mdi-chevron-right"></i>
                                        <span class="hide-menu">Produk</span>

                                    </a>

                                </li>
                                

                            </ul>

                        </li>

                    </ul>

                </nav>

            </div>

        </aside>

        <!-- END SIDEBAR -->


        <!-- ==============================
             PAGE WRAPPER
        ============================== -->

        <div class="page-wrapper">

            <!-- MAIN CONTENT -->
            <div class="container-fluid">

                @yield('content')

            </div>
            <!-- END MAIN CONTENT -->

            <!-- FOOTER -->
            <footer class="footer text-center">
                &copy; {{ date('Y') }} Toko Online.
                Semua hak dilindungi.
            </footer>
            <!-- END FOOTER -->

        </div>

        <!-- END PAGE WRAPPER -->

    </div>
    <!-- END MAIN WRAPPER -->


    <!-- ==============================
         JAVASCRIPT
    ============================== -->

    <!-- jQuery -->
    <script src="{{ asset('backend/libs/jquery/dist/jquery.min.js') }}"></script>

    <!-- Bootstrap -->
    <script src="{{ asset('backend/libs/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('backend/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>

    <!-- Scrollbar -->
    <script src="{{ asset('backend/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}"></script>

    <!-- Sparkline -->
    <script src="{{ asset('backend/extra-libs/sparkline/sparkline.js') }}"></script>

    <!-- Matrix Admin -->
    <script src="{{ asset('backend/dist/js/waves.js') }}"></script>
    <script src="{{ asset('backend/dist/js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('backend/dist/js/custom.min.js') }}"></script>

    <!-- DataTables -->
    <script src="{{ asset('backend/extra-libs/multicheck/datatable-checkbox-init.js') }}"></script>
    <script src="{{ asset('backend/extra-libs/multicheck/jquery.multicheck.js') }}"></script>
    <script src="{{ asset('backend/extra-libs/DataTables/datatables.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            if ($('#zero_config').length) {
                $('#zero_config').DataTable();
            }
        });
    </script>


    <!-- ==============================
         FORM LOGOUT
    ============================== -->

    <form id="keluar-app"
        action="{{ route('backend.logout') }}"
        method="POST"
        style="display: none;">

        @csrf

    </form>


    <!-- ==============================
         SWEETALERT
    ============================== -->

    <script src="{{ asset('sweetalert/sweetalert2.all.min.js') }}"></script>

    <!-- NOTIFIKASI SUKSES -->
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: @json(session('success'))
            });
        </script>
    @endif


    <!-- ==============================
         KONFIRMASI HAPUS DATA
    ============================== -->

    <script>
        $(document).on('click', '.show_confirm', function (event) {
            event.preventDefault();

            const form = $(this).closest('form');
            const namaData = $(this).data('konf-delete');

            Swal.fire({
                title: 'Konfirmasi Hapus Data?',
                html: 'Data yang dihapus <strong>' +
                    $('<div>').text(namaData || '').html() +
                    '</strong> tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.trigger('submit');
                }
            });
        });
    </script>


    <!-- ==============================
         PREVIEW FOTO
    ============================== -->

    <script>
        function previewFoto() {
            const inputFoto = document.querySelector('input[name="foto"]');
            const fotoPreview = document.querySelector('.foto-preview');

            if (!inputFoto || !fotoPreview || !inputFoto.files.length) {
                return;
            }

            const file = inputFoto.files[0];

            if (!file.type.startsWith('image/')) {
                return;
            }

            const reader = new FileReader();

            reader.onload = function (event) {
                fotoPreview.src = event.target.result;
                fotoPreview.style.display = 'block';
                fotoPreview.style.width = '100%';
                fotoPreview.style.maxWidth = '250px';
                fotoPreview.style.height = 'auto';
                fotoPreview.style.objectFit = 'cover';
            };

            reader.readAsDataURL(file);
        }
    </script>


    <!-- ==============================
         CKEDITOR
    ============================== -->

    <script src="https://cdn.ckeditor.com/ckeditor5/30.0.0/classic/ckeditor.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const editorElement = document.querySelector('#ckeditor');

            if (editorElement) {
                ClassicEditor
                    .create(editorElement)
                    .catch(function (error) {
                        console.error('CKEditor error:', error);
                    });
            }
        });
    </script>

    @stack('scripts')

</body>

</html>
