@extends('layouts.app')

@section('title', 'Dashboard ' . auth()->user()->role_label)
@section('page-title', 'Dashboard ' . auth()->user()->role_label)

@section('styles')
<style>
    .welcome-banner {
        background: #4b1424;
        border-radius: 14px;
        padding: 26px 28px;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        box-shadow: 0 10px 25px -5px rgba(53, 7, 19, 0.22);
    }

    .welcome-banner > * {
        min-width: 0;
    }

    .banner-text h2 {
        font-size: 20px;
        font-weight: 800;
        margin-bottom: 6px;
    }

    .banner-text p {
        font-size: 14px;
        font-weight: 500;
        opacity: 0.94;
        max-width: 540px;
    }

    .welcome-action {
        background: #fffdfa;
        color: #350713;
        font-weight: 700;
        box-shadow: 0 4px 12px rgba(18, 3, 8, 0.2);
    }

    .welcome-action:hover {
        background: #eadde0;
        color: #350713;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background-color: #ffffff;
        border-radius: 12px;
        padding: 18px;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .stat-info h3 {
        font-size: 22px;
        font-weight: 800;
        color: #0f172a;
    }

    .stat-info p {
        font-size: 12px;
        color: #64748b;
        font-weight: 600;
    }

    .info-steps {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 14px;
        margin-top: 14px;
    }

    .step-item {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 14px;
        text-align: center;
    }

    .step-number {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background-color: #5c1a2a;
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 13px;
        margin-bottom: 8px;
    }

    .step-item h4 {
        font-size: 13px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 4px;
    }

    .step-item p {
        font-size: 11.5px;
        color: #64748b;
    }

    .student-table {
        width: 100%;
        min-width: 960px;
        table-layout: fixed;
        white-space: normal;
    }

    .student-table th,
    .student-table td {
        padding: 12px 10px;
        vertical-align: middle;
    }

    .student-table th:nth-child(1) { width: 15%; }
    .student-table th:nth-child(2) { width: 11%; }
    .student-table th:nth-child(3) { width: 18%; }
    .student-table th:nth-child(4) { width: 14%; }
    .student-table th:nth-child(5) { width: 17%; }
    .student-table th:nth-child(6) { width: 15%; }
    .student-table th:nth-child(7) { width: 10%; }

    .student-table td:nth-child(1),
    .student-table td:nth-child(2),
    .student-table td:nth-child(7) {
        white-space: nowrap;
    }

    .student-table td:nth-child(3),
    .student-table td:nth-child(4),
    .student-table td:nth-child(5) {
        overflow-wrap: anywhere;
    }

    .student-table .badge {
        max-width: 100%;
        white-space: normal;
        line-height: 1.4;
    }

    @media (max-width: 720px) {
        .welcome-banner {
            align-items: stretch;
            flex-direction: column;
            gap: 16px;
            padding: 22px;
        }

        .welcome-banner .btn-primary {
            justify-content: center;
            white-space: normal !important;
        }

        .stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .stat-card {
            gap: 10px;
            padding: 14px 12px;
        }

        .stat-icon {
            width: 38px;
            height: 38px;
            flex-shrink: 0;
        }

        .card-header {
            align-items: flex-start;
        }

        .info-steps {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    /* Responsive Recent Complaints */
    .dashboard-desktop-view {
        display: block;
    }
    .dashboard-mobile-view {
        display: none;
    }

    .recent-mobile-card {
        background-color: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 10px;
        padding: 14px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }

    .recent-mobile-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 8px;
        padding-bottom: 8px;
        border-bottom: 1px solid #f1f5f9;
    }

    .recent-mobile-title {
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }

    .recent-mobile-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px 12px;
        font-size: 12px;
        color: #475569;
    }

    .recent-mobile-meta span {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .recent-mobile-meta i {
        color: #7c4253;
    }

    @media (max-width: 768px) {
        .dashboard-desktop-view {
            display: none !important;
        }
        .dashboard-mobile-view {
            display: flex !important;
            flex-direction: column;
            gap: 10px;
            padding: 14px;
        }
    }

    @media (max-width: 420px) {
        .stats-grid,
        .info-steps {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<!-- Welcome Banner -->
<div class="welcome-banner">
    <div class="banner-text">
        <h2>Hai, {{ auth()->user()->name }}! 👋</h2>
        <p>Ada fasilitas kelas, laboratorium, toilet, atau sarana sekolah yang rusak? Laporkan langsung di sini agar segera ditangani oleh tim Sarpras.</p>
    </div>
    <a href="{{ route('complaints.create') }}" class="btn-primary welcome-action" style="padding: 12px 20px; font-size: 14px; white-space: nowrap;">
        <i class="bi bi-plus-circle-fill"></i> Buat Laporan Aduan
    </a>
</div>

<!-- Quick Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background-color: #f2e8e5; color: #5c1a2a;">
            <i class="bi bi-card-checklist"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $myComplaintsCount }}</h3>
            <p>Total Laporan Saya</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background-color: #fef3c7; color: #d97706;">
            <i class="bi bi-hourglass-split"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $myPendingCount }}</h3>
            <p>Menunggu Verifikasi</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background-color: #eadde0; color: #5c1a2a;">
            <i class="bi bi-tools"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $myInProgressCount }}</h3>
            <p>Sedang Diperbaiki</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background-color: #dcfce7; color: #166534;">
            <i class="bi bi-check2-circle"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $myResolvedCount }}</h3>
            <p>Selesai Ditangani</p>
        </div>
    </div>
</div>

<!-- Recent Complaints Table -->
<div class="card">
    <div class="card-header">
        <h3><i class="bi bi-clock-history text-blue-600"></i> Status Pengaduan Terakhir Anda</h3>
        <a href="{{ route('complaints.index') }}" class="btn-outline" style="font-size: 12px; padding: 5px 12px;">
            Semua Riwayat <i class="bi bi-arrow-right"></i>
        </a>
    </div>

    <!-- Desktop Table View (>= 769px) -->
    <div class="dashboard-desktop-view">
        <div class="table-responsive">
            <table class="table-custom student-table">
                <thead>
                    <tr>
                        <th>No. Tiket</th>
                        <th>Tanggal</th>
                        <th>Kategori Sarana</th>
                        <th>Lokasi</th>
                        <th>Judul Kerusakan</th>
                        <th>Status Progres</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentComplaints as $complaint)
                        <tr>
                            <td>
                                <strong style="color: #350713; font-family: monospace;">{{ $complaint->ticket_number }}</strong>
                            </td>
                            <td style="color: #64748b; font-size: 12.5px;">
                                {{ $complaint->created_at->format('d M Y') }}
                            </td>
                            <td>
                                <span style="font-weight: 600;">{{ $complaint->category->name }}</span>
                            </td>
                            <td style="color: #475569;">
                                {{ $complaint->location->name }}
                            </td>
                            <td>
                                <span style="font-weight: 500;">{{ Str::limit($complaint->title, 35) }}</span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $complaint->status }}">
                                    @if($complaint->status === 'pending') <i class="bi bi-hourglass-split"></i>
                                    @elseif($complaint->status === 'in_progress') <i class="bi bi-gear-fill"></i>
                                    @elseif($complaint->status === 'resolved') <i class="bi bi-check2-circle"></i>
                                    @else <i class="bi bi-x-circle"></i>
                                    @endif
                                    {{ $complaint->status_label }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('complaints.show', $complaint->id) }}" class="btn-primary" style="padding: 5px 12px; font-size: 12px;">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 30px; color: #64748b;">
                                <i class="bi bi-journal-plus" style="font-size: 32px; display: block; margin-bottom: 8px;"></i>
                                Anda belum pernah mengajukan laporan pengaduan sarana.
                                <div style="margin-top: 10px;">
                                    <a href="{{ route('complaints.create') }}" class="btn-primary" style="font-size: 12.5px;">
                                        <i class="bi bi-plus-circle"></i> Buat Laporan Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Cards View (< 768px) -->
    <div class="dashboard-mobile-view">
        @forelse($recentComplaints as $complaint)
            <div class="recent-mobile-card">
                <div class="recent-mobile-header">
                    <span style="font-family: monospace; font-size: 12.5px; font-weight: 700; color: #350713; background: #f2e8e5; padding: 2px 7px; border-radius: 5px;">
                        {{ $complaint->ticket_number }}
                    </span>
                    <span class="badge badge-{{ $complaint->status }}" style="font-size: 11px;">
                        {{ $complaint->status_label }}
                    </span>
                </div>
                <h4 class="recent-mobile-title">{{ $complaint->title }}</h4>
                <div class="recent-mobile-meta">
                    <span><i class="bi bi-folder2-open"></i> {{ $complaint->category->name }}</span>
                    <span><i class="bi bi-geo-alt"></i> {{ $complaint->location->name }}</span>
                    <span><i class="bi bi-calendar3"></i> {{ $complaint->created_at->format('d M Y') }}</span>
                </div>
                <div style="padding-top: 4px;">
                    <a href="{{ route('complaints.show', $complaint->id) }}" class="btn-primary" style="width: 100%; justify-content: center; padding: 8px 12px; font-size: 12.5px;">
                        <i class="bi bi-eye"></i> Detail &amp; Lacak Status
                    </a>
                </div>
            </div>
        @empty
            <div style="text-align: center; padding: 24px; color: #64748b; font-size: 13px;">
                <i class="bi bi-journal-plus" style="font-size: 30px; display: block; margin-bottom: 6px; color: #94a3b8;"></i>
                Anda belum pernah mengajukan laporan pengaduan sarana.
                <div style="margin-top: 10px;">
                    <a href="{{ route('complaints.create') }}" class="btn-primary" style="font-size: 12px; padding: 7px 14px;">
                        <i class="bi bi-plus-circle"></i> Buat Laporan Pertama
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- Alur Kerja / How it Works Guide -->
<div class="card">
    <div class="card-header">
        <h3><i class="bi bi-info-circle-fill text-blue-600"></i> Alur Penanganan Pengaduan Sarana</h3>
    </div>
    <div class="info-steps">
        <div class="step-item">
            <div class="step-number">1</div>
            <h4>Ajukan Aduan</h4>
            <p>Isi form kerusakan sarana &amp; sertakan foto bukti jelas.</p>
        </div>
        <div class="step-item">
            <div class="step-number">2</div>
            <h4>Verifikasi Admin</h4>
            <p>Petugas sarpras meninjau validitas &amp; urgensi laporan.</p>
        </div>
        <div class="step-item">
            <div class="step-number">3</div>
            <h4>Proses Perbaikan</h4>
            <p>Teknisi melakukan perbaikan fisik di lokasi ruangan terkait.</p>
        </div>
        <div class="step-item">
            <div class="step-number">4</div>
            <h4>Selesai &amp; Bukti</h4>
            <p>Admin mengunggah foto bukti perbaikan dan menutup tiket.</p>
        </div>
    </div>
</div>
@endsection
