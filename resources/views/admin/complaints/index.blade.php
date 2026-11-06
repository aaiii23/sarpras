@extends('layouts.app')

@section('title', 'Pengelolaan Aduan Sarana')
@section('page-title', 'Pengelolaan & Data Aduan')

@section('styles')
<style>
    .workflow-tabs {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 22px;
    }

    .tab-pill {
        padding: 10px 18px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        border: 1px solid #e2e8f0;
        background-color: #ffffff;
        color: #475569;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        white-space: nowrap;
    }

    .tab-pill:hover {
        background-color: #f8fafc;
        color: #0f172a;
        transform: translateY(-1px);
    }

    .tab-pill.active {
        color: #ffffff;
        border-color: transparent;
        box-shadow: 0 4px 10px rgba(0,0,0,0.12);
    }

    .tab-pill.active.tab-unvalidated {
        background: linear-gradient(135deg, #d97706, #b45309);
    }

    .tab-pill.active.tab-in_progress {
        background: linear-gradient(135deg, #4b1424, #7c4253);
    }

    .tab-pill.active.tab-resolved {
        background: linear-gradient(135deg, #16a34a, #15803d);
    }

    .tab-pill.active.tab-rejected {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
    }

    .tab-pill.active.tab-all {
        background: linear-gradient(135deg, #0f172a, #334155);
    }

    .tab-counter {
        font-size: 11px;
        padding: 2px 8px;
        border-radius: 9999px;
        background-color: rgba(0,0,0,0.07);
        font-weight: 800;
    }

    .tab-pill.active .tab-counter {
        background-color: rgba(255,255,255,0.28);
        color: #ffffff;
    }

    .tab-helper-alert {
        padding: 12px 18px;
        border-radius: 10px;
        font-size: 13px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 500;
    }

    .filter-card {
        background-color: #ffffff;
        border-radius: 12px;
        padding: 16px 20px;
        border: 1px solid var(--border-color);
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }

    .filter-form-admin {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
    }

    .search-wrap-admin {
        position: relative;
        flex: 1;
        min-width: 220px;
    }

    .search-wrap-admin i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 14px;
        pointer-events: none;
    }

    .search-wrap-admin input {
        padding-left: 36px;
        height: 40px;
    }

    .filter-dropdowns-admin {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .filter-dropdowns-admin select {
        height: 40px;
        font-size: 13px;
    }

    .filter-actions-admin {
        display: flex;
        gap: 8px;
    }

    /* Structured Complaints Stream List (Anti-Slide / Sleek & Aesthetic) */
    .complaint-stream-card {
        background-color: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        overflow: hidden;
    }

    .complaint-stream-list {
        display: flex;
        flex-direction: column;
    }

    .complaint-stream-item {
        display: flex;
        align-items: center;
        gap: 18px;
        padding: 16px 20px;
        border-bottom: 1px solid #f1f5f9;
        transition: background-color 0.15s ease;
        background-color: #ffffff;
    }

    .complaint-stream-item:last-child {
        border-bottom: none;
    }

    .complaint-stream-item:hover {
        background-color: #faf6f4;
    }

    .stream-item-main {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        gap: 7px;
    }

    .stream-item-top {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .stream-ticket-chip {
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

    .stream-photo-pill {
        font-size: 11px;
        font-weight: 600;
        color: #5c1a2a;
        background-color: #f6efeb;
        border: 1px solid #e4d7d3;
        padding: 2px 7px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .stream-photo-pill:hover {
        background-color: #5c1a2a;
        color: #ffffff;
        border-color: #5c1a2a;
    }

    .stream-date {
        font-size: 12px;
        color: #64748b;
        margin-left: auto;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .stream-item-title-desc {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .stream-item-title {
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        line-height: 1.35;
        word-break: break-word;
    }

    .stream-item-desc {
        font-size: 12.5px;
        color: #64748b;
        margin: 0;
        line-height: 1.45;
        word-break: break-word;
    }

    .stream-item-meta {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
        font-size: 12.5px;
        color: #64748b;
    }

    .stream-item-meta span {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .stream-item-meta i {
        color: #7c4253;
    }

    .stream-item-action {
        flex-shrink: 0;
    }

    .stream-btn-action {
        padding: 8px 18px;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        white-space: nowrap;
        border-radius: 8px;
    }

    .stream-empty-state {
        padding: 45px 20px;
        text-align: center;
        color: #64748b;
    }

    /* Mobile Responsive Breakpoints */
    @media (max-width: 860px) {
        .workflow-tabs {
            flex-wrap: nowrap;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            padding-bottom: 6px;
            gap: 8px;
        }

        .workflow-tabs::-webkit-scrollbar {
            display: none;
        }

        .tab-pill {
            flex-shrink: 0;
            padding: 8px 14px;
            font-size: 12.5px;
        }

        .filter-card {
            padding: 14px;
        }

        .filter-form-admin {
            flex-direction: column;
            align-items: stretch;
        }

        .search-wrap-admin {
            width: 100%;
        }

        .filter-dropdowns-admin {
            display: grid;
            grid-template-columns: 1fr 1fr;
            width: 100%;
        }

        .filter-actions-admin {
            width: 100%;
        }

        .filter-actions-admin .btn-primary,
        .filter-actions-admin .btn-outline {
            width: 100%;
            height: 40px;
            justify-content: center;
        }

        .complaint-stream-item {
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
            padding: 14px 16px;
        }

        .stream-item-top {
            gap: 6px;
        }

        .stream-date {
            margin-left: 0;
            width: 100%;
            font-size: 11.5px;
        }

        .stream-item-meta {
            gap: 8px 12px;
            font-size: 12px;
        }

        .stream-item-action .stream-btn-action {
            width: 100%;
            justify-content: center;
            padding: 10px 14px;
        }
    }

    @media (max-width: 480px) {
        .filter-dropdowns-admin {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<!-- Workflow Categories / Separated Rekap Tabs -->
<div class="workflow-tabs">
    <!-- 1. Belum Divalidasi / Baru Masuk -->
    <a href="{{ route('admin.complaints.index', ['tab' => 'unvalidated']) }}" class="tab-pill tab-unvalidated {{ $currentTab === 'unvalidated' || $currentTab === 'pending' ? 'active' : '' }}">
        <i class="bi bi-hourglass-split"></i>
        <span>1. Belum Divalidasi</span>
        <span class="tab-counter">{{ $counts['unvalidated'] }}</span>
    </a>

    <!-- 2. Sedang Dikerjakan (Antrean Prioritas) -->
    <a href="{{ route('admin.complaints.index', ['tab' => 'in_progress']) }}" class="tab-pill tab-in_progress {{ $currentTab === 'in_progress' ? 'active' : '' }}">
        <i class="bi bi-tools"></i>
        <span>2. Antrean Dikerjakan</span>
        <span class="tab-counter">{{ $counts['in_progress'] }}</span>
    </a>

    <!-- 3. Sudah Selesai -->
    <a href="{{ route('admin.complaints.index', ['tab' => 'resolved']) }}" class="tab-pill tab-resolved {{ $currentTab === 'resolved' ? 'active' : '' }}">
        <i class="bi bi-check-circle-fill"></i>
        <span>3. Sudah Selesai</span>
        <span class="tab-counter">{{ $counts['resolved'] }}</span>
    </a>

    <!-- 4. Ditolak -->
    <a href="{{ route('admin.complaints.index', ['tab' => 'rejected']) }}" class="tab-pill tab-rejected {{ $currentTab === 'rejected' ? 'active' : '' }}">
        <i class="bi bi-x-circle-fill"></i>
        <span>Ditolak</span>
        <span class="tab-counter">{{ $counts['rejected'] }}</span>
    </a>

    <!-- 5. Semua Data -->
    <a href="{{ route('admin.complaints.index', ['tab' => 'all']) }}" class="tab-pill tab-all {{ $currentTab === 'all' ? 'active' : '' }}">
        <i class="bi bi-collection-fill"></i>
        <span>Semua Data</span>
        <span class="tab-counter">{{ $counts['all'] }}</span>
    </a>
</div>

<!-- Contextual Helper Banner -->
@if($currentTab === 'unvalidated' || $currentTab === 'pending')
    <div class="tab-helper-alert" style="background-color: #fffbeb; color: #92400e; border: 1px solid #fde68a;">
        <i class="bi bi-exclamation-circle-fill" style="font-size: 18px; color: #d97706; flex-shrink: 0;"></i>
        <div>
            <strong>Kategori Pengelolaan: Aduan Masuk yang Belum Divalidasi ({{ $counts['unvalidated'] }} Laporan)</strong><br>
            Tinjau foto kerusakan dan tentukan tingkat kondisi kerusakan (Rusak Ringan/Sedang/Berat/Darurat) agar langsung masuk antrean pengerjaan teknisi.
        </div>
    </div>
@elseif($currentTab === 'in_progress')
    <div class="tab-helper-alert" style="background-color: #f2e8e5; color: #4d1b2a; border: 1px solid #ddc9c4;">
        <i class="bi bi-lightning-charge-fill" style="font-size: 18px; color: #5c1a2a; flex-shrink: 0;"></i>
        <div>
            <strong>Kategori Pengelolaan: Antrean Sedang Dikerjakan ({{ $counts['in_progress'] }} Laporan)</strong><br>
            Daftar otomatis diurutkan berdasarkan prioritas paling mendesak (Darurat &rarr; Rusak Berat &rarr; Rusak Sedang &rarr; Rusak Ringan).
        </div>
    </div>
@elseif($currentTab === 'resolved')
    <div class="tab-helper-alert" style="background-color: #f0fdf4; color: #166534; border: 1px solid #bbf7d0;">
        <i class="bi bi-check2-circle" style="font-size: 18px; color: #16a34a; flex-shrink: 0;"></i>
        <div>
            <strong>Kategori Pengelolaan: Laporan Tuntas / Selesai Diperbaiki ({{ $counts['resolved'] }} Laporan)</strong><br>
            Seluruh laporan pada kategori ini telah selesai ditindaklanjuti dan diperbaiki.
        </div>
    </div>
@endif

<!-- Responsive Filter Bar -->
<div class="filter-card">
    <form action="{{ route('admin.complaints.index') }}" method="GET" class="filter-form-admin">
        <input type="hidden" name="tab" value="{{ $currentTab }}">

        <div class="search-wrap-admin">
            <i class="bi bi-search"></i>
            <input type="text" name="search" class="form-control" placeholder="Cari nama pelapor, no. tiket, judul, ruangan..." value="{{ request('search') }}">
        </div>

        <div class="filter-dropdowns-admin">
            <select name="category_id" class="form-control" style="width: auto; min-width: 150px;" onchange="this.form.submit()">
                <option value="">-- Semua Kategori --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>

            <select name="urgency" class="form-control" style="width: auto; min-width: 150px;" onchange="this.form.submit()">
                <option value="">-- Semua Kondisi --</option>
                <option value="rendah" {{ request('urgency') === 'rendah' ? 'selected' : '' }}>Rusak Ringan</option>
                <option value="sedang" {{ request('urgency') === 'sedang' ? 'selected' : '' }}>Rusak Sedang</option>
                <option value="tinggi" {{ request('urgency') === 'tinggi' ? 'selected' : '' }}>Rusak Berat</option>
                <option value="darurat" {{ request('urgency') === 'darurat' ? 'selected' : '' }}>Sangat Berat / Darurat</option>
            </select>
        </div>

        <div class="filter-actions-admin">
            <button type="submit" class="btn-primary" style="height: 40px; padding: 0 16px;">
                <i class="bi bi-funnel-fill"></i> Filter
            </button>

            @if(request()->hasAny(['search', 'category_id', 'urgency']))
                <a href="{{ route('admin.complaints.index', ['tab' => $currentTab]) }}" class="btn-outline" style="height: 40px; padding: 8px 12px;">
                    <i class="bi bi-x"></i> Reset
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Main Complaints Stream List (Unified Container, No Slide, Contextual Actions) -->
<div class="card complaint-stream-card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
        <h3 class="card-title">
            <i class="bi bi-inbox-fill text-amber-700"></i> Daftar Aduan Sarana Masuk
        </h3>
        <span style="font-size: 12px; color: #64748b; font-weight: 600;">
            Menampilkan {{ $complaints->count() }} dari {{ $complaints->total() }} Laporan
        </span>
    </div>

    <div class="complaint-stream-list">
        @forelse($complaints as $complaint)
            <div class="complaint-stream-item">
                <!-- Bagian Informasi Utama Aduan -->
                <div class="stream-item-main">
                    <!-- Top Bar: Tiket, Status, Urgensi, Foto Preview, Tanggal -->
                    <div class="stream-item-top">
                        <span class="stream-ticket-chip">
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

                        @if($complaint->photo_evidence)
                            <span class="stream-photo-pill" data-photo-src="{{ asset('storage/' . $complaint->photo_evidence) }}" data-photo-alt="Foto bukti {{ $complaint->ticket_number }}" title="Klik untuk lihat foto bukti">
                                <i class="bi bi-camera-fill"></i> Foto Bukti
                            </span>
                        @endif

                        <span class="stream-date">
                            <i class="bi bi-calendar3"></i> {{ $complaint->created_at->format('d M Y, H:i') }}
                        </span>
                    </div>

                    <!-- Judul & Snippet Deskripsi -->
                    <div class="stream-item-title-desc">
                        <h4 class="stream-item-title">{{ $complaint->title }}</h4>
                        @if($complaint->description)
                            <p class="stream-item-desc">{{ Str::limit($complaint->description, 130) }}</p>
                        @endif
                    </div>

                    <!-- Metadata: Pelapor, Kategori, Lokasi Ruangan -->
                    <div class="stream-item-meta">
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

                <!-- Tombol Aksi Cepat Kontekstual -->
                <div class="stream-item-action">
                    @if($complaint->status === 'pending')
                        <a href="{{ route('admin.complaints.show', $complaint->id) }}" class="btn-primary stream-btn-action" style="background: linear-gradient(135deg, #d97706, #b45309); box-shadow: 0 2px 8px rgba(217, 119, 6, 0.25);">
                            <i class="bi bi-shield-check"></i> Validasi Sekarang <i class="bi bi-arrow-right"></i>
                        </a>
                    @elseif($complaint->status === 'in_progress')
                        <a href="{{ route('admin.complaints.show', $complaint->id) }}" class="btn-primary stream-btn-action">
                            <i class="bi bi-tools"></i> Tangani Aduan <i class="bi bi-arrow-right"></i>
                        </a>
                    @else
                        <a href="{{ route('admin.complaints.show', $complaint->id) }}" class="btn-outline stream-btn-action">
                            <i class="bi bi-eye"></i> Detail Laporan <i class="bi bi-arrow-right"></i>
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="stream-empty-state">
                <i class="bi bi-inbox" style="font-size: 38px; color: #94a3b8; display: block; margin-bottom: 8px;"></i>
                <strong style="color: #1e293b; font-size: 14px; display: block; margin-bottom: 4px;">Tidak Ada Data Pengaduan</strong>
                <p style="margin: 0;">Tidak ditemukan data pengaduan pada kategori alur kerja ini.</p>
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
