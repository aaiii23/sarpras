@extends('layouts.app')

@section('title', 'Laporan Rekapitulasi Sarpras')
@section('page-title', 'Rekap & Laporan Pengaduan Sarana')

@section('styles')
<style>
    .report-filter-card {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }

    .report-grid-form {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 14px;
        align-items: flex-end;
    }

    .stat-badge-summary {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .summary-pill {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        font-weight: 600;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
    }

    .summary-pill strong {
        font-size: 16px;
        color: #350713;
    }

    /* Structured Rekap List (100% Responsive / No Slide) */
    .rekap-card-wrapper {
        background-color: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        overflow: hidden;
    }

    .rekap-list {
        display: flex;
        flex-direction: column;
    }

    .rekap-list-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px 20px;
        border-bottom: 1px solid #f1f5f9;
        transition: background-color 0.15s ease;
        background-color: #ffffff;
    }

    .rekap-list-item:last-child {
        border-bottom: none;
    }

    .rekap-list-item:hover {
        background-color: #faf6f4;
    }

    .rekap-item-num {
        font-size: 12px;
        font-weight: 800;
        color: #5c1a2a;
        background-color: #f2e8e5;
        padding: 6px 10px;
        border-radius: 8px;
        flex-shrink: 0;
        min-width: 40px;
        text-align: center;
    }

    .rekap-item-main {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        gap: 7px;
    }

    .rekap-item-top {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .rekap-ticket-chip {
        font-family: monospace;
        font-weight: 700;
        font-size: 12px;
        color: #350713;
        background-color: #f2e8e5;
        padding: 3px 8px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .rekap-date {
        font-size: 12px;
        color: #64748b;
        margin-left: auto;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .rekap-item-title {
        font-size: 14.5px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        line-height: 1.35;
        word-break: break-word;
    }

    .rekap-item-meta {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
        font-size: 12.5px;
        color: #64748b;
    }

    .rekap-item-meta span {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .rekap-item-meta i {
        color: #7c4253;
    }

    .rekap-item-action {
        flex-shrink: 0;
    }

    .rekap-btn-action {
        padding: 7px 16px;
        font-size: 12.5px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .rekap-empty-state {
        padding: 45px 20px;
        text-align: center;
        color: #64748b;
    }

    @media (max-width: 768px) {
        .rekap-list-item {
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
            padding: 14px 16px;
        }

        .rekap-item-top {
            gap: 6px;
        }

        .rekap-date {
            margin-left: 0;
            width: 100%;
            font-size: 11.5px;
        }

        .rekap-item-meta {
            gap: 8px 12px;
            font-size: 12px;
        }

        .rekap-item-action .rekap-btn-action {
            width: 100%;
            justify-content: center;
            padding: 9px 14px;
        }
    }
</style>
@endsection

@section('content')
<!-- Filter Form Card -->
<div class="report-filter-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 12px; flex-wrap: wrap; gap: 10px;">
        <h3 style="font-size: 15px; font-weight: 700; color: #0f172a;">
            <i class="bi bi-funnel-fill text-blue-600"></i> Filter Periode &amp; Kriteria Rekapitulasi
        </h3>

        <!-- Export Buttons -->
        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            <a href="{{ route('admin.reports.print', request()->all()) }}" target="_blank" class="btn-primary" style="background-color: #5d454b; padding: 7px 14px; font-size: 12.5px;">
                <i class="bi bi-printer-fill"></i> Cetak Laporan (Print / PDF)
            </a>
            <a href="{{ route('admin.reports.export', request()->all()) }}" class="btn-primary" style="background-color: #16a34a; padding: 7px 14px; font-size: 12.5px;">
                <i class="bi bi-file-earmark-excel-fill"></i> Unduh Excel / CSV
            </a>
        </div>
    </div>

    <form action="{{ route('admin.reports.index') }}" method="GET" class="report-grid-form">
        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Tanggal Awal</label>
            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
        </div>

        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Tanggal Akhir</label>
            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
        </div>

        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Status Aduan</label>
            <select name="status" class="form-control">
                <option value="">-- Semua Status --</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Sedang Diproses</option>
                <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Selesai</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Kategori Sarana</label>
            <select name="category_id" class="form-control">
                <option value="">-- Semua Kategori --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 0;">
            <label class="form-label">Lokasi Ruangan</label>
            <select name="location_id" class="form-control">
                <option value="">-- Semua Lokasi --</option>
                @foreach($locations as $loc)
                    <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>
                        {{ $loc->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <button type="submit" class="btn-primary" style="width: 100%; height: 42px; justify-content: center;">
                <i class="bi bi-filter"></i> Terapkan Filter
            </button>
        </div>
    </form>
</div>

<!-- Summary Badges -->
<div class="stat-badge-summary">
    <div class="summary-pill">
        <span>Total Rekap:</span>
        <strong>{{ $stats['total'] }} Laporan</strong>
    </div>
    <div class="summary-pill">
        <span style="color: #d97706;"><i class="bi bi-hourglass-split"></i> Pending:</span>
        <strong style="color: #d97706;">{{ $stats['pending'] }}</strong>
    </div>
    <div class="summary-pill">
        <span style="color: #5d454b;"><i class="bi bi-gear-fill"></i> Diproses:</span>
        <strong style="color: #5d454b;">{{ $stats['in_progress'] }}</strong>
    </div>
    <div class="summary-pill">
        <span style="color: #16a34a;"><i class="bi bi-check-circle-fill"></i> Selesai:</span>
        <strong style="color: #16a34a;">{{ $stats['resolved'] }}</strong>
    </div>
    <div class="summary-pill">
        <span style="color: #dc2626;"><i class="bi bi-x-circle-fill"></i> Ditolak:</span>
        <strong style="color: #dc2626;">{{ $stats['rejected'] }}</strong>
    </div>
</div>

<!-- Daftar Rekapitulasi (List View Terstruktur - Anti Slide) -->
<div class="card rekap-card-wrapper">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
        <h3 class="card-title">
            <i class="bi bi-card-checklist text-amber-700"></i> Daftar Rekapitulasi Aduan
        </h3>
        <span style="font-size: 12px; color: #64748b; font-weight: 600;">
            Menampilkan {{ $complaints->count() }} dari {{ $complaints->total() }} Data
        </span>
    </div>

    <div class="rekap-list">
        @forelse($complaints as $index => $complaint)
            <div class="rekap-list-item">
                <!-- Nomor Urut -->
                <div class="rekap-item-num">
                    #{{ $complaints->firstItem() + $index }}
                </div>

                <!-- Bagian Informasi Rekap -->
                <div class="rekap-item-main">
                    <!-- Top: Tiket, Status, Urgensi, Tanggal -->
                    <div class="rekap-item-top">
                        <span class="rekap-ticket-chip">
                            <i class="bi bi-ticket-perforated"></i> {{ $complaint->ticket_number }}
                        </span>

                        <span class="badge badge-{{ $complaint->status }}" style="font-size: 11px;">
                            @if($complaint->status === 'pending') <i class="bi bi-hourglass-split"></i> Belum Diverifikasi
                            @elseif($complaint->status === 'in_progress') <i class="bi bi-gear-fill"></i> Sedang Dikerjakan
                            @elseif($complaint->status === 'resolved') <i class="bi bi-check-circle-fill"></i> Selesai
                            @else <i class="bi bi-x-circle-fill"></i> Ditolak
                            @endif
                        </span>

                        @if($complaint->urgency)
                            <span class="badge urgency-{{ $complaint->urgency }}" style="font-size: 11px;">
                                @if($complaint->urgency === 'darurat') <i class="bi bi-lightning-fill"></i>
                                @elseif($complaint->urgency === 'tinggi') <i class="bi bi-exclamation-triangle-fill"></i>
                                @endif
                                {{ $complaint->urgency_label }}
                            </span>
                        @else
                            <span class="badge" style="background-color: #fef3c7; color: #92400e; font-size: 11px; border: 1px solid #fde68a;">
                                <i class="bi bi-clock"></i> Belum Divalidasi
                            </span>
                        @endif

                        <span class="rekap-date">
                            <i class="bi bi-calendar3"></i> {{ $complaint->created_at->format('d M Y, H:i') }}
                        </span>
                    </div>

                    <!-- Judul Sarana & Kerusakan -->
                    <h4 class="rekap-item-title">{{ $complaint->title }}</h4>

                    <!-- Metadata: Pelapor, Kategori, Lokasi Ruangan -->
                    <div class="rekap-item-meta">
                        <span>
                            <i class="bi bi-person-circle"></i>
                            <strong>{{ $complaint->user->name }}</strong>
                            <small>({{ ($complaint->user->role ?? '') === 'guru' ? 'Guru' : 'Siswa' }} - {{ ($complaint->user->role ?? '') === 'guru' ? 'NIP' : 'NIS' }}: {{ $complaint->user->nis ?? '-' }})</small>
                        </span>
                        <span>
                            <i class="bi bi-folder2-open"></i> {{ $complaint->category->name }}
                        </span>
                        <span>
                            <i class="bi bi-geo-alt-fill"></i> {{ $complaint->location->name }}
                        </span>
                    </div>
                </div>

                <!-- Tombol Aksi Detail -->
                <div class="rekap-item-action">
                    <a href="{{ route('admin.complaints.show', $complaint->id) }}" class="btn-outline rekap-btn-action">
                        <i class="bi bi-eye"></i> Detail
                    </a>
                </div>
            </div>
        @empty
            <div class="rekap-empty-state">
                <i class="bi bi-inbox" style="font-size: 38px; color: #94a3b8; display: block; margin-bottom: 8px;"></i>
                <strong style="color: #1e293b; font-size: 14px; display: block; margin-bottom: 4px;">Tidak Ada Data Laporan</strong>
                <p style="margin: 0;">Tidak ditemukan data pengaduan pada filter periode ini.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($complaints->hasPages())
        <div style="padding: 16px 20px; border-top: 1px solid #f1f5f9;">
            {{ $complaints->links() }}
        </div>
    @endif
</div>
@endsection
