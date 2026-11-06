<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - SARPRAS SEKOLAH</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Chart.js (for Admin Dashboard) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --taupe: #91837b;
            --taupe-muted: #75665f;
            --primary: #4b1424;
            --primary-hover: #300710;
            --primary-light: #f2e8e5;
            --accent: #7c4253;
            --sidebar-bg: #2b0711;
            --sidebar-hover: #431421;
            --sidebar-active: #683044;
            --bg-main: #f4efeb;
            --card-bg: #fffdfa;
            --border-color: #e4d9d1;
            --text-dark: #2f1a20;
            --text-muted: #75665f;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            background-color: var(--bg-main);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            position: relative;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            color: #ffffff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            z-index: 1050;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 4px 0 20px rgba(0,0,0,0.08);
        }

        .sidebar-brand {
            padding: 22px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid #45232c;
        }

        .brand-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #7c4253, #350713);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(53, 7, 19, 0.3);
            flex-shrink: 0;
        }

        .brand-text h2 {
            font-size: 16px;
            font-weight: 800;
            letter-spacing: -0.3px;
            color: #ffffff;
            margin: 0;
            line-height: 1.2;
        }

        .brand-text p {
            font-size: 11px;
            color: #c5b6ad;
            margin: 0;
        }

        .sidebar-menu {
            padding: 16px 12px;
            flex-grow: 1;
            overflow-y: auto;
        }

        .menu-category {
            font-size: 10.5px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #a18f86;
            font-weight: 700;
            padding: 12px 12px 6px 12px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 8px;
            color: #c5b6ad;
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            margin-bottom: 3px;
            transition: all 0.2s ease;
        }

        .nav-item i {
            font-size: 17px;
            width: 20px;
            text-align: center;
        }

        .nav-item:hover {
            background-color: var(--sidebar-hover);
            color: #ffffff;
        }

        .nav-item.active {
            background-color: var(--sidebar-active);
            color: #ffffff;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(18, 3, 8, 0.24);
        }

        .badge-nav {
            margin-left: auto;
            background-color: #ef4444;
            color: white;
            font-size: 11px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 10px;
        }

        .sidebar-footer {
            padding: 16px 18px;
            border-top: 1px solid #45232c;
            display: flex;
            align-items: center;
            gap: 12px;
            background-color: #21050c;
        }

        .user-avatar-sm {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #91837b, #5d454b);
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            flex-shrink: 0;
        }

        .user-info-sm {
            flex-grow: 1;
            overflow: hidden;
        }

        .user-info-sm .name {
            font-size: 13px;
            font-weight: 600;
            color: #ffffff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-info-sm .role {
            font-size: 11px;
            color: #c5b6ad;
            text-transform: capitalize;
        }

        /* Sidebar Backdrop on Mobile */
        .sidebar-backdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(35, 5, 14, 0.62);
            backdrop-filter: blur(2px);
            z-index: 1040;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-backdrop.active {
            display: block;
            opacity: 1;
        }

        /* Main Wrapper */
        .main-wrapper {
            margin-left: 260px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            width: calc(100% - 260px);
            background-color: var(--bg-main);
            transition: margin-left 0.3s ease;
        }

        /* Topbar */
        .topbar {
            height: 68px;
            background-color: var(--card-bg);
            border-bottom: 1px solid var(--border-color);
            padding: 0 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 40;
        }

        .topbar-title h1 {
            font-size: 19px;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        /* Content Area */
        .content-area {
            padding: 28px;
            flex-grow: 1;
        }

        /* Reusable UI Components */
        .card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
            margin-bottom: 24px;
            overflow: hidden;
        }

        .card-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .card-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-body {
            padding: 24px;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            color: white;
            border: none;
            padding: 9px 18px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13.5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
            box-shadow: 0 2px 6px rgba(53, 7, 19, 0.22);
            min-height: 40px;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-hover), #491020);
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(53, 7, 19, 0.3);
        }

        .btn-outline {
            background-color: transparent;
            color: var(--text-dark);
            border: 1px solid var(--border-color);
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
            min-height: 40px;
        }

        .btn-outline:hover {
            background-color: #f1eae5;
            border-color: #d7cac2;
        }

        .btn-danger {
            background-color: #ef4444;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-danger:hover {
            background-color: #dc2626;
        }

        /* Badges */
        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .badge-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .badge-in_progress {
            background-color: #eadde0;
            color: #4d1b2a;
        }

        .badge-resolved {
            background-color: #dcfce7;
            color: #166534;
        }

        .badge-rejected {
            background-color: #fee2e2;
            color: #991b1b;
        }

        /* Urgency Badges */
        .urgency-rendah { color: #16a34a; font-weight: 600; }
        .urgency-sedang { color: #d97706; font-weight: 600; }
        .urgency-tinggi { color: #ea580c; font-weight: 700; }
        .urgency-darurat { color: #dc2626; font-weight: 800; animation: pulse-alert 2s infinite; }

        @keyframes pulse-alert {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #5d454b;
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 14px;
            color: var(--text-dark);
            background-color: #fffdfa;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            min-height: 42px;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(92, 26, 42, 0.15);
        }

        /* Alerts */
        .alert {
            padding: 14px 18px;
            border-radius: 10px;
            font-size: 13.5px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success {
            background-color: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .alert-error {
            background-color: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        /* Responsive Table */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table-custom {
            width: 100%;
            border-collapse: collapse;
            font-size: 13.5px;
            white-space: nowrap;
        }

        .table-custom th {
            background-color: #f3ede8;
            color: #57444a;
            font-weight: 600;
            text-align: left;
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-color);
        }

        .table-custom td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border-color);
            color: #3f2b31;
            vertical-align: middle;
        }

        .table-custom tbody tr:hover {
            background-color: #faf6f2;
        }

        /* Responsive Pagination */
        nav[role="navigation"] {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            font-size: 13px;
        }

        nav[role="navigation"] svg {
            width: 16px;
            height: 16px;
            display: inline-block;
            vertical-align: middle;
        }

        nav[role="navigation"] > div {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
        }

        .modal-backdrop {
            position: fixed;
            inset: 0;
            z-index: 1200;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 16px;
            overflow-y: auto;
            background: rgba(35, 5, 14, 0.58);
        }

        .modal-backdrop.show {
            display: flex;
        }

        .modal-box {
            width: min(100%, 560px);
            max-height: calc(100dvh - 32px);
            overflow-y: auto;
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.25);
        }

        .modal-header,
        .modal-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 16px 20px;
        }

        .modal-header {
            position: sticky;
            top: 0;
            background: #ffffff;
            border-bottom: 1px solid var(--border-color);
            z-index: 1;
        }

        .modal-body {
            padding: 20px;
        }

        .modal-footer {
            justify-content: flex-end;
            border-top: 1px solid var(--border-color);
        }

        .photo-preview {
            display: block;
            width: 100%;
            padding: 0;
            overflow: hidden;
            border: 0;
            border-radius: 8px;
            background: transparent;
            cursor: zoom-in;
        }

        .photo-viewer {
            width: min(96vw, 1100px);
            max-width: 96vw;
            max-height: 94dvh;
            padding: 0;
            margin: auto;
            overflow: hidden;
            color: #ffffff;
            background: #0f172a;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 12px;
        }

        .photo-viewer::backdrop {
            background: rgba(2, 6, 23, 0.82);
            backdrop-filter: blur(3px);
        }

        .photo-viewer-image {
            display: block;
            max-width: 100%;
            max-height: calc(94dvh - 64px);
            margin: 0 auto;
            object-fit: contain;
        }

        .photo-viewer-actions {
            display: flex;
            justify-content: flex-start;
            padding: 12px;
            background: #0f172a;
        }

        @media (max-width: 600px) {
            .modal-backdrop {
                align-items: flex-end;
                padding: 8px;
            }

            .modal-box {
                max-height: calc(100dvh - 16px);
                border-radius: 12px;
            }

            .modal-header,
            .modal-body,
            .modal-footer {
                padding: 16px;
            }
        }

        /* Mobile Toggle Button */
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            color: var(--text-dark);
            cursor: pointer;
            padding: 4px;
        }

        /* Media Queries for Tablet & Mobile */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .main-wrapper {
                margin-left: 0;
                width: 100%;
            }
            .mobile-toggle {
                display: block;
            }
            .topbar {
                padding: 0 16px;
            }
            .content-area {
                padding: 16px;
            }
            .card-body {
                padding: 16px;
            }
        }

        @media (max-width: 600px) {
            .topbar-actions span {
                display: none;
            }
            .topbar-title h1 {
                font-size: 16px;
            }
        }

        /* Photo Lightbox Dialog */
        .photo-lightbox-dialog {
            border: none;
            border-radius: 16px;
            padding: 0;
            max-width: 95vw;
            max-height: 94vh;
            width: 860px;
            background: #1c1417;
            color: #ffffff;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.65);
            overflow: hidden;
            margin: auto;
        }
        .photo-lightbox-dialog::backdrop {
            background: rgba(18, 3, 8, 0.82);
            backdrop-filter: blur(8px);
        }
        .photo-lightbox-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 18px;
            background: #2b131c;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            gap: 12px;
        }
        .btn-lightbox-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background-color: #5c1a2a;
            color: #ffffff;
            border: 1px solid #7c2d40;
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-lightbox-back:hover {
            background-color: #7c2d40;
            transform: translateY(-1px);
        }
        .photo-lightbox-caption {
            font-size: 13px;
            color: #d1c5be;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            flex: 1;
        }
        .btn-lightbox-close {
            background: none;
            border: none;
            color: #cbd5e1;
            font-size: 18px;
            cursor: pointer;
            padding: 6px 10px;
            border-radius: 6px;
            transition: all 0.2s;
        }
        .btn-lightbox-close:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.12);
        }
        .photo-lightbox-body {
            padding: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #120b0e;
            max-height: calc(94vh - 65px);
            overflow: auto;
        }
        .photo-lightbox-img {
            max-width: 100%;
            max-height: calc(94vh - 95px);
            object-fit: contain;
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.5);
        }

        /* Logout Confirm Dialog */
        .app-confirm-dialog {
            border: none;
            border-radius: 16px;
            padding: 24px;
            width: 100%;
            max-width: 420px;
            background: #fffdfa;
            box-shadow: 0 25px 50px -12px rgba(35, 5, 14, 0.35);
            overflow: hidden;
            margin: auto;
        }
        .app-confirm-dialog::backdrop {
            background: rgba(35, 5, 14, 0.7);
            backdrop-filter: blur(5px);
        }
        .confirm-dialog-content {
            text-align: center;
        }
        .confirm-icon-box {
            width: 58px;
            height: 58px;
            margin: 0 auto 14px auto;
            border-radius: 50%;
            background-color: #fee2e2;
            color: #dc2626;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        .confirm-dialog-title {
            font-size: 19px;
            font-weight: 800;
            color: #350713;
            margin-bottom: 8px;
        }
        .confirm-dialog-desc {
            font-size: 13.5px;
            color: #55444b;
            line-height: 1.5;
            margin-bottom: 22px;
        }
        .confirm-dialog-actions {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }
        .confirm-dialog-actions .btn-cancel {
            padding: 10px 18px;
            border-radius: 8px;
            background: #f1eae5;
            border: 1px solid #d7cac2;
            color: #4b363d;
            font-weight: 600;
            font-size: 13.5px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .confirm-dialog-actions .btn-cancel:hover {
            background: #e3d9d3;
        }
        .confirm-dialog-actions .btn-confirm-danger {
            padding: 10px 20px;
            border-radius: 8px;
            background: linear-gradient(135deg, #b91c1c, #991b1b);
            border: none;
            color: #ffffff;
            font-weight: 700;
            font-size: 13.5px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(185, 28, 28, 0.3);
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .confirm-dialog-actions .btn-confirm-danger:hover {
            background: linear-gradient(135deg, #991b1b, #7f1d1d);
            transform: translateY(-1px);
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Sidebar Backdrop for Mobile/Tablet -->
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon">
                <i class="bi bi-tools"></i>
            </div>
            <div class="brand-text">
                <h2>SARPRAS SEKOLAH</h2>
                <p>Helpdesk &amp; Pengaduan</p>
            </div>
        </div>

        <nav class="sidebar-menu">
            <div class="menu-category">Menu Utama</div>
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i>
                <span>Dashboard</span>
            </a>

            @if(!auth()->user()->isAdmin())
                <div class="menu-category">Layanan Pengaduan</div>
                <a href="{{ route('complaints.create') }}" class="nav-item {{ request()->routeIs('complaints.create') ? 'active' : '' }}">
                    <i class="bi bi-plus-circle-fill"></i>
                    <span>Ajukan Aduan Baru</span>
                </a>
                <a href="{{ route('complaints.index') }}" class="nav-item {{ request()->routeIs('complaints.index') ? 'active' : '' }}">
                    <i class="bi bi-card-checklist"></i>
                    <span>Riwayat Aduan Saya</span>
                </a>
            @endif

            @if(auth()->user()->isAdmin())
                <div class="menu-category">Pengelolaan Aduan</div>
                <a href="{{ route('admin.complaints.index') }}" class="nav-item {{ request()->routeIs('admin.complaints.*') ? 'active' : '' }}">
                    <i class="bi bi-inbox-fill"></i>
                    <span>Data Pengaduan</span>
                    @php
                        $pendingCount = \App\Models\Complaint::where('status', 'pending')->count();
                    @endphp
                    @if($pendingCount > 0)
                        <span class="badge-nav">{{ $pendingCount }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.reports.index') }}" class="nav-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-bar-graph-fill"></i>
                    <span>Rekap &amp; Laporan</span>
                </a>

                <div class="menu-category">Master Data</div>
                <a href="{{ route('admin.categories.index') }}" class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="bi bi-tag-fill"></i>
                    <span>Kategori Sarana</span>
                </a>
                <a href="{{ route('admin.locations.index') }}" class="nav-item {{ request()->routeIs('admin.locations.*') ? 'active' : '' }}">
                    <i class="bi bi-geo-alt-fill"></i>
                    <span>Lokasi &amp; Ruangan</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i>
                    <span>Data Pengguna</span>
                </a>
            @endif

            <div class="menu-category">Pengaturan</div>
            <a href="{{ route('profile') }}" class="nav-item {{ request()->routeIs('profile') ? 'active' : '' }}">
                <i class="bi bi-person-gear"></i>
                <span>Profil &amp; Akun</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="user-avatar-sm">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="user-info-sm">
                <div class="name">{{ auth()->user()->name }}</div>
                <div class="role">{{ auth()->user()->role_label }}</div>
            </div>
            <button type="button" id="openLogoutBtn" title="Keluar dari sistem" style="background: none; border: none; color: #94a3b8; cursor: pointer; font-size: 18px; padding: 4px 6px; border-radius: 6px; transition: all 0.2s;" onmouseover="this.style.color='#fca5a5'; this.style.backgroundColor='rgba(255,255,255,0.06)';" onmouseout="this.style.color='#94a3b8'; this.style.backgroundColor='transparent';">
                <i class="bi bi-box-arrow-right"></i>
            </button>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="main-wrapper">
        <!-- Topbar -->
        <header class="topbar">
            <div style="display: flex; align-items: center; gap: 14px;">
                <button class="mobile-toggle" id="menuToggle" aria-label="Toggle Menu">
                    <i class="bi bi-list"></i>
                </button>
                <div class="topbar-title">
                    <h1>@yield('page-title', 'Dashboard')</h1>
                </div>
            </div>

            <div class="topbar-actions">
                @if(!auth()->user()->isAdmin())
                    <a href="{{ route('complaints.create') }}" class="btn-primary">
                        <i class="bi bi-plus-lg"></i>
                        <span>Buat Aduan</span>
                    </a>
                @endif

                <div style="font-size: 13px; color: var(--text-muted);">
                    <i class="bi bi-calendar3"></i> {{ date('d F Y') }}
                </div>
            </div>
        </header>

        <!-- Page Content Area -->
        <main class="content-area">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle-fill" style="font-size: 18px;"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <i class="bi bi-exclamation-triangle-fill" style="font-size: 18px;"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <i class="bi bi-exclamation-circle-fill" style="font-size: 18px;"></i>
                    <div>
                        <strong>Terjadi beberapa kesalahan:</strong>
                        <ul style="margin-top: 4px; padding-left: 18px;">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Full Photo Lightbox Modal -->
    <dialog class="photo-lightbox-dialog" id="photoViewer" aria-label="Pratinjau Foto Penuh">
        <div class="photo-lightbox-header">
            <button type="button" class="btn-lightbox-back" id="photoViewerBack">
                <i class="bi bi-arrow-left"></i> Kembali ke Laporan
            </button>
            <span class="photo-lightbox-caption" id="photoViewerCaption">Bukti Foto Sarana</span>
            <button type="button" class="btn-lightbox-close" id="photoViewerClose" title="Tutup Pratinjau">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="photo-lightbox-body">
            <img class="photo-lightbox-img" id="photoViewerImage" alt="">
        </div>
    </dialog>

    <!-- Logout Confirmation Modal Dialog -->
    <dialog class="app-confirm-dialog" id="logoutConfirmDialog" aria-labelledby="logoutDialogTitle">
        <div class="confirm-dialog-content">
            <div class="confirm-icon-box">
                <i class="bi bi-box-arrow-right"></i>
            </div>
            <h3 id="logoutDialogTitle" class="confirm-dialog-title">Konfirmasi Keluar</h3>
            <p class="confirm-dialog-desc">Apakah Anda yakin ingin keluar dari sistem SARPRAS? Anda harus login kembali untuk mengakses data.</p>
            <div class="confirm-dialog-actions">
                <button type="button" class="btn-cancel" id="cancelLogoutBtn">
                    Batal
                </button>
                <form id="logoutFormAction" action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn-confirm-danger">
                        <i class="bi bi-box-arrow-right"></i> Ya, Keluar
                    </button>
                </form>
            </div>
        </div>
    </dialog>

    <!-- Scripts -->
    <script>
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebarBackdrop');

        function toggleSidebar() {
            sidebar?.classList.toggle('open');
            backdrop?.classList.toggle('active');
        }

        function closeSidebar() {
            sidebar?.classList.remove('open');
            backdrop?.classList.remove('active');
        }

        menuToggle?.addEventListener('click', toggleSidebar);
        backdrop?.addEventListener('click', closeSidebar);

        // Photo Lightbox Handling
        const photoViewer = document.getElementById('photoViewer');
        const photoViewerImage = document.getElementById('photoViewerImage');
        const photoViewerCaption = document.getElementById('photoViewerCaption');
        const photoViewerClose = document.getElementById('photoViewerClose');
        const photoViewerBack = document.getElementById('photoViewerBack');

        function openLightbox(src, alt) {
            if (!photoViewer || !photoViewerImage) return;
            photoViewerImage.src = src;
            photoViewerImage.alt = alt || 'Foto bukti aduan';
            if (photoViewerCaption) {
                photoViewerCaption.textContent = alt || 'Pratinjau Foto Sarana';
            }
            if (typeof photoViewer.showModal === 'function') {
                photoViewer.showModal();
            } else {
                photoViewer.style.display = 'block';
            }
        }

        function closeLightbox() {
            if (!photoViewer) return;
            if (typeof photoViewer.close === 'function') {
                photoViewer.close();
            } else {
                photoViewer.style.display = 'none';
            }
            photoViewerImage?.removeAttribute('src');
        }

        document.addEventListener('click', (event) => {
            const trigger = event.target.closest('[data-photo-src]');
            if (trigger) {
                event.preventDefault();
                openLightbox(trigger.dataset.photoSrc, trigger.dataset.photoAlt);
            }
        });

        photoViewerBack?.addEventListener('click', closeLightbox);
        photoViewerClose?.addEventListener('click', closeLightbox);
        photoViewer?.addEventListener('click', (event) => {
            if (event.target === photoViewer) closeLightbox();
        });

        // Logout Confirmation Dialog Handling
        const logoutConfirmDialog = document.getElementById('logoutConfirmDialog');
        const openLogoutBtn = document.getElementById('openLogoutBtn');
        const cancelLogoutBtn = document.getElementById('cancelLogoutBtn');

        openLogoutBtn?.addEventListener('click', () => {
            if (logoutConfirmDialog) {
                if (typeof logoutConfirmDialog.showModal === 'function') {
                    logoutConfirmDialog.showModal();
                } else {
                    logoutConfirmDialog.style.display = 'block';
                }
            }
        });

        cancelLogoutBtn?.addEventListener('click', () => {
            if (logoutConfirmDialog) {
                if (typeof logoutConfirmDialog.close === 'function') {
                    logoutConfirmDialog.close();
                } else {
                    logoutConfirmDialog.style.display = 'none';
                }
            }
        });

        logoutConfirmDialog?.addEventListener('click', (event) => {
            if (event.target === logoutConfirmDialog) {
                if (typeof logoutConfirmDialog.close === 'function') {
                    logoutConfirmDialog.close();
                } else {
                    logoutConfirmDialog.style.display = 'none';
                }
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
