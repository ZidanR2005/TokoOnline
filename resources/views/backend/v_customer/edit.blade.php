<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ubah Customer - Projek Toko Online</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-bg: #202830;
            --sidebar-width: 270px;
            --accent: #25a9e1;
            --topbar-bg: #15181c;
            --page-bg: #eef0f3;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--page-bg);
            color: #343a40;
            min-height: 100vh;
        }

        /* ---------- Sidebar ---------- */
        .sidebar {
            position: fixed;
            top: 0; left: 0; bottom: 0;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            z-index: 1040;
            overflow-y: auto;
            transition: transform .3s ease;
        }

        .sidebar .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            height: 70px;
            padding: 0 20px;
            background: var(--topbar-bg);
            color: #fff;
            text-decoration: none;
        }

        .sidebar .brand img {
            width: 48px;
            height: 48px;
            object-fit: contain;
            border-radius: 6px;
        }

        .sidebar .brand span {
            font-size: 1.15rem;
            font-weight: 500;
            letter-spacing: .2px;
        }

        .sidebar .brand span b { font-weight: 600; color: #fff; }
        .sidebar .brand span em { font-style: normal; color: #6c7bff; }

        .sidebar-menu {
            list-style: none;
            margin: 25px 0 0;
            padding: 0;
        }

        .sidebar-menu > li > a {
            display: flex;
            align-items: center;
            padding: 16px 25px;
            color: #b4bcc6;
            font-weight: 500;
            text-decoration: none;
            transition: background .2s, color .2s;
        }

        .sidebar-menu > li > a i.icon {
            width: 32px;
            font-size: 1.15rem;
            text-align: left;
        }

        .sidebar-menu > li > a .arrow {
            margin-left: auto;
            font-size: .75rem;
            transition: transform .25s;
        }

        .sidebar-menu > li > a:hover {
            background: rgba(255, 255, 255, .06);
            color: #fff;
        }

        .sidebar-menu > li.active > a {
            background: var(--accent);
            color: #fff;
        }

        .sidebar-menu > li.open > a .arrow { transform: rotate(90deg); }

        .submenu {
            list-style: none;
            margin: 0;
            padding: 0;
            max-height: 0;
            overflow: hidden;
            background: rgba(0, 0, 0, .18);
            transition: max-height .3s ease;
        }

        .sidebar-menu > li.open > .submenu { max-height: 200px; }

        .submenu a {
            display: block;
            padding: 12px 25px 12px 57px;
            color: #9aa4af;
            font-size: .9rem;
            text-decoration: none;
        }

        .submenu a:hover { color: #fff; }

        /* ---------- Topbar ---------- */
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: 70px;
            background: var(--topbar-bg);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 25px;
            z-index: 1030;
            transition: left .3s ease;
        }

        .topbar .toggle-btn {
            background: none;
            border: 0;
            color: #fff;
            font-size: 1.5rem;
            cursor: pointer;
        }

        .topbar .user-info { font-size: .9rem; color: #c9d0d8; }
        .topbar .user-info i { margin-right: 6px; }

        /* ---------- Content ---------- */
        .main {
            margin-left: var(--sidebar-width);
            padding: 100px 30px 40px;
            transition: margin-left .3s ease;
        }

        .page-header { margin-bottom: 22px; }
        .page-header h3 { font-weight: 600; margin: 0 0 4px; }

        .breadcrumb-lite { font-size: .85rem; color: #7b8590; }
        .breadcrumb-lite a { color: var(--accent); text-decoration: none; }

        .card-form {
            background: #fff;
            border: 0;
            border-radius: 10px;
            box-shadow: 0 2px 14px rgba(0, 0, 0, .06);
            max-width: 760px;
        }

        .card-form .card-header {
            background: #fff;
            border-bottom: 1px solid #edf0f3;
            padding: 18px 28px;
            font-weight: 600;
            border-radius: 10px 10px 0 0;
        }

        .card-form .card-header i { color: var(--accent); margin-right: 8px; }
        .card-form .card-body { padding: 28px; }

        .form-group label {
            font-weight: 500;
            font-size: .9rem;
            margin-bottom: 6px;
        }

        .form-group label .req { color: #e74c3c; }

        .input-group-text {
            background: #f5f7fa;
            border-right: 0;
            color: #8a94a0;
            min-width: 44px;
            justify-content: center;
        }

        .form-control {
            height: 46px;
            border-left: 0;
            font-size: .95rem;
        }

        .form-control:focus {
            border-color: #ced4da;
            box-shadow: none;
        }

        .input-group:focus-within .input-group-text,
        .input-group:focus-within .form-control {
            border-color: var(--accent);
        }

        .input-group:focus-within {
            box-shadow: 0 0 0 .2rem rgba(37, 169, 225, .2);
            border-radius: .25rem;
        }

        .form-control.is-invalid { background-image: none; }

        .error-text {
            display: block;
            margin-top: 5px;
            font-size: .82rem;
            color: #e74c3c;
        }

        .btn-save {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
            padding: 10px 24px;
            font-weight: 500;
            border-radius: 6px;
        }

        .btn-save:hover { background: #1b94c8; border-color: #1b94c8; color: #fff; }

        .btn-cancel {
            padding: 10px 24px;
            font-weight: 500;
            border-radius: 6px;
        }

        /* ---------- Sidebar collapsed (desktop) / mobile ---------- */
        body.sidebar-collapsed .sidebar { transform: translateX(-100%); }
        body.sidebar-collapsed .topbar { left: 0; }
        body.sidebar-collapsed .main { margin-left: 0; }

        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .topbar { left: 0; }
            .main { margin-left: 0; padding: 95px 15px 30px; }

            body.sidebar-open .sidebar { transform: translateX(0); }
            body.sidebar-open::after {
                content: '';
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, .45);
                z-index: 1035;
            }

            body.sidebar-collapsed .sidebar { transform: translateX(-100%); }
            body.sidebar-open.sidebar-collapsed .sidebar { transform: translateX(0); }
        }
    </style>
</head>
<body>

    {{-- ===== SIDEBAR ===== --}}
    <aside class="sidebar" id="sidebar">
        <a href="{{ Route::has('backend.beranda') ? route('backend.beranda') : '#' }}" class="brand">
            <img src="{{ asset('backend/images/image/icon_univ_bsi.png') }}" alt="Logo" onerror="this.style.display='none'">
            <span><em>Projek</em> Toko Online</span>
        </a>

        <ul class="sidebar-menu">
            <li class="{{ request()->routeIs('backend.beranda') ? 'active' : '' }}">
                <a href="{{ Route::has('backend.beranda') ? route('backend.beranda') : '#' }}">
                    <i class="icon fas fa-th-large"></i> Beranda
                </a>
            </li>

            <li class="{{ request()->routeIs('backend.user.*') ? 'active' : '' }}">
                <a href="{{ Route::has('backend.user.index') ? route('backend.user.index') : '#' }}">
                    <i class="icon fas fa-chart-bar"></i> User
                </a>
            </li>

            <li class="{{ request()->routeIs('backend.customer.*') ? 'active' : '' }}">
                <a href="{{ route('backend.customer.index') }}">
                    <i class="icon fas fa-users"></i> Customer
                </a>
            </li>

            <li class="has-sub {{ request()->routeIs('backend.produk.*', 'backend.kategori.*') ? 'open' : '' }}">
                <a href="#" onclick="this.parentElement.classList.toggle('open'); return false;">
                    <i class="icon fas fa-receipt"></i> Data Produk
                    <i class="arrow fas fa-chevron-right"></i>
                </a>
                <ul class="submenu">
                    <li><a href="{{ Route::has('backend.kategori.index') ? route('backend.kategori.index') : '#' }}">Kategori</a></li>
                    <li><a href="{{ Route::has('backend.produk.index') ? route('backend.produk.index') : '#' }}">Produk</a></li>
                </ul>
            </li>
        </ul>
    </aside>

    {{-- ===== TOPBAR ===== --}}
    <header class="topbar">
        <button class="toggle-btn" id="toggleSidebar" aria-label="Toggle sidebar">
            <i class="fas fa-bars"></i>
        </button>
        <div class="user-info">
            <i class="fas fa-user-circle"></i> {{ auth()->user()->nama ?? 'Admin' }}
        </div>
    </header>

    {{-- ===== CONTENT ===== --}}
    <main class="main">
        <div class="page-header">
            <h3>Ubah Customer</h3>
            <div class="breadcrumb-lite">
                <a href="{{ route('backend.customer.index') }}">Customer</a> / Ubah
            </div>
        </div>

        <div class="card card-form">
            <div class="card-header">
                <i class="fas fa-user-edit"></i> Form Ubah Data Customer
            </div>

            <div class="card-body">
                <form action="{{ route('backend.customer.update', $customer->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Nama <span class="req">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="text"
                                   name="nama"
                                   class="form-control @error('nama') is-invalid @enderror"
                                   placeholder="Masukkan nama lengkap"
                                   value="{{ old('nama', $customer->user->nama ?? '') }}"
                                   required>
                        </div>
                        @error('nama')
                            <small class="error-text">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Email <span class="req">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <input type="email"
               name="email"
               class="form-control"
               placeholder="contoh@email.com"
               value="{{ old('email', $customer->user->email ?? '') }}"
               readonly>
                        </div>
                        @error('email')
                            <small class="error-text">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Alamat</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                            </div>
                            <input type="text"
                                   name="alamat"
                                   class="form-control @error('alamat') is-invalid @enderror"
                                   placeholder="Masukkan alamat"
                                   value="{{ old('alamat', $customer->alamat ?? '') }}">
                        </div>
                        @error('alamat')
                            <small class="error-text">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Kode Pos</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-mail-bulk"></i></span>
                            </div>
                            <input type="text"
                                   name="pos"
                                   class="form-control @error('pos') is-invalid @enderror"
                                   placeholder="Contoh: 43191"
                                   value="{{ old('pos', $customer->pos ?? '') }}">
                        </div>
                        @error('pos')
                            <small class="error-text">{{ $message }}</small>
                        @enderror
                    </div>

                    <hr class="my-4">

                    <div class="d-flex flex-wrap" style="gap: 10px;">
                        <button type="submit" class="btn btn-save">
                            <i class="fas fa-save mr-1"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('backend.customer.index') }}" class="btn btn-secondary btn-cancel">
                            <i class="fas fa-times mr-1"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        (function () {
            var body = document.body;
            var btn = document.getElementById('toggleSidebar');
            var isMobile = function () { return window.innerWidth < 992; };

            btn.addEventListener('click', function () {
                if (isMobile()) {
                    body.classList.toggle('sidebar-open');
                } else {
                    body.classList.toggle('sidebar-collapsed');
                }
            });

            // tutup sidebar mobile saat klik di luar
            document.addEventListener('click', function (e) {
                if (isMobile() && body.classList.contains('sidebar-open') &&
                    !e.target.closest('#sidebar') && !e.target.closest('#toggleSidebar')) {
                    body.classList.remove('sidebar-open');
                }
            });
        })();
    </script>
</body>
</html>