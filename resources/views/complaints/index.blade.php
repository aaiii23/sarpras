@extends('layouts.app')

@section('title', 'Riwayat Pengaduan Saya')
@section('page-title', 'Riwayat Pengaduan Sarana')

@section('styles')
<style>
    /* Filter Bar Responsive */
    .filter-bar {
        background-color: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 16px 18px;
        margin-bottom: 20px;
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }

    .filter-form {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
        flex: 1;
    }

    .search-wrap {
        position: relative;
        flex: 1;
        min-width: 220px;
    }

    .search-wrap i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        pointer-events: none;
    }

    .search-input {
        width: 100%;
        padding: 9px 12px 9px 36px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 13px;
        color: var(--text-dark);
        background-color: #ffffff;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        height: 40px;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(124, 66, 83, 0.15);
    }

    .filter-selects-group {
        display: flex;
        gap: 8px;
        align-items: center;
        flex-wrap: wrap;
    }

    .filter-select {
        padding: 8px 12px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 13px;
        color: var(--text-dark);
        background-color: #ffffff;
        height: 40px;
        min-width: 160px;
        cursor: pointer;
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--accent);
    }

    .btn-create-action {
        flex-shrink: 0;
    }

    /* Unified Complaints List (No Slide / Anti-Horizontal Scroll) */
    .complaints-card-container {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
    }

    .complaints-list-view {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .complaint-card {
        background-color: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 18px 20px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
    }

    .complaint-card:hover {
        border-color: #d1c5be;
        box-shadow: 0 4px 12px rgba(53, 7, 19, 0.06);
    }

    .complaint-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        flex-wrap: wrap;
        padding-bottom: 10px;
        border-bottom: 1px solid #f1f5f9;
    }

    .ticket-chip {
        font-family: monospace;
        font-weight: 700;
        font-size: 12.5px;
        color: #350713;
        background-color: #f2e8e5;
        padding: 3px 8px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .complaint-card-body {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .complaint-card-title {
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.35;
        margin: 0;
    }

    .complaint-card-desc {
        font-size: 13px;
        color: #64748b;
        margin: 0;
        line-height: 1.45;
    }

    .complaint-card-meta {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 10px 14px;
        background-color: #f8fafc;
        border: 1px solid #f1f5f9;
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 12px;
        margin-top: 4px;
    }

    .meta-row {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #475569;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .meta-row i {
        color: #7c4253;
        font-size: 13px;
        flex-shrink: 0;
    }

    .complaint-card-footer {
        display: flex;
        justify-content: flex-end;
        padding-top: 4px;
    }

    .btn-detail-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 8px 18px;
        font-size: 13px;
        font-weight: 600;
        border-radius: 8px;
    }

    /* Mobile Responsive Breakpoints */
    @media (max-width: 860px) {
        .filter-bar {
            flex-direction: column;
            align-items: stretch;
            padding: 14px;
            gap: 12px;
        }

        .filter-form {
            flex-direction: column;
            align-items: stretch;
            width: 100%;
            gap: 10px;
        }

        .search-wrap {
            width: 100%;
            min-width: 100%;
        }

        .filter-selects-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            width: 100%;
        }

        .filter-select {
            width: 100%;
            min-width: 0;
        }

        .filter-reset-btn {
            width: 100%;
            justify-content: center;
        }

        .btn-create-action {
            width: 100%;
        }

        .btn-create-action .btn-primary {
            width: 100%;
            justify-content: center;
        }

        .complaint-card-meta {
            grid-template-columns: 1fr 1fr;
        }

        .complaint-card-footer .btn-detail-action {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .filter-selects-group {
            grid-template-columns: 1fr;
        }

        .complaint-card-meta {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<!-- Filter & Search Bar -->
<div class="filter-bar">
    <form action="{{ route('complaints.index') }}" method="GET" class="filter-form">
        <div class="search-wrap">
            <i class="bi bi-search"></i>
            <input type="text" name="search" class="search-input" placeholder="Cari judul kerusakan / no. tiket..." value="{{ request('search') }}">
        </div>

        <div class="filter-selects-group">
            <select name="status" class="filter-select" onchange="this.form.submit()">
                <option value="">-- Semua Status --</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Sedang Diproses</option>
                <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Selesai</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>

            <select name="category_id" class="filter-select" onchange="this.form.submit()">
                <option value="">-- Semua Kategori --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        @if(request()->hasAny(['search', 'status', 'category_id']))
            <a href="{{ route('complaints.index') }}" class="btn-outline filter-reset-btn" style="padding: 8px 12px; font-size: 12.5px;">
                <i class="bi bi-x-circle"></i> Reset Filter
            </a>
        @endif
    </form>

    <div class="btn-create-action">
        <a href="{{ route('complaints.create') }}" class="btn-primary">
            <i class="bi bi-plus-circle-fill"></i> Buat Aduan Baru
        </a>
    </div>
</div>

<!-- Main Complaints List (No Slide / Anti-Horizontal Scroll) -->
<div class="complaints-card-container">
    <div class="complaints-list-view">
        @forelse($complaints as $complaint)
            <div class="complaint-card">
                <!-- Top Row: Ticket & Status Badge -->
                <div class="complaint-card-header">
                    <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                        <span class="ticket-chip">
                            <i class="bi bi-ticket-perforated"></i> {{ $complaint->ticket_number }}
                        </span>
                        <span class="badge badge-{{ $complaint->status }}">
                            @if($complaint->status === 'pending') <i class="bi bi-hourglass-split"></i>
                            @elseif($complaint->status === 'in_progress') <i class="bi bi-gear-fill"></i>
                            @elseif($complaint->status === 'resolved') <i class="bi bi-check2-circle"></i>
                            @else <i class="bi bi-x-circle"></i>
                            @endif
                            {{ $complaint->status_label }}
                        </span>
                        @if($complaint->urgency)
                            <span class="badge urgency-{{ $complaint->urgency }}" style="font-size: 11.5px;">
                                {{ $complaint->urgency_label }}
                            </span>
                        @else
                            <span class="badge" style="background-color: #fef3c7; color: #92400e; font-size: 11.5px; border: 1px solid #fde68a;">
                                <i class="bi bi-clock"></i> Belum Divalidasi
                            </span>
                        @endif
                    </div>

                    <span style="font-size: 12px; color: #64748b;">
                        <i class="bi bi-calendar3"></i> {{ $complaint->created_at->format('d M Y, H:i') }}
                    </span>
                </div>

                <!-- Body: Title, Short Desc, Meta Info Grid -->
                <div class="complaint-card-body">
                    <h4 class="complaint-card-title">{{ $complaint->title }}</h4>
                    @if($complaint->description)
                        <p class="complaint-card-desc">{{ $complaint->description }}</p>
                    @endif

                    <div class="complaint-card-meta">
                        <div class="meta-row">
                            <i class="bi bi-folder2-open"></i>
                            <span><strong>Kategori:</strong> {{ $complaint->category->name }}</span>
                        </div>
                        <div class="meta-row">
                            <i class="bi bi-geo-alt"></i>
                            <span><strong>Lokasi:</strong> {{ $complaint->location->name }}</span>
                        </div>
                        <div class="meta-row">
                            <i class="bi bi-calendar-event"></i>
                            <span><strong>Tanggal:</strong> {{ $complaint->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="meta-row">
                            <i class="bi bi-activity"></i>
                            <span><strong>Kondisi:</strong> {{ $complaint->urgency ? $complaint->urgency_label : 'Belum Divalidasi' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Footer: Action Button -->
                <div class="complaint-card-footer">
                    <a href="{{ route('complaints.show', $complaint->id) }}" class="btn-primary btn-detail-action">
                        <i class="bi bi-eye"></i> Detail &amp; Lacak Status <i class="bi bi-chevron-right arrow"></i>
                    </a>
                </div>
            </div>
        @empty
            <div style="background-color: #ffffff; border: 1px solid var(--border-color); border-radius: 12px; padding: 40px 20px; text-align: center; color: #64748b;">
                <i class="bi bi-inbox" style="font-size: 38px; display: block; margin-bottom: 8px; color: #94a3b8;"></i>
                <strong style="color: #334155; font-size: 14px; display: block; margin-bottom: 4px;">Tidak Ada Pengaduan</strong>
                Tidak ditemukan data pengaduan yang sesuai dengan pencarian atau filter Anda.
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div style="margin-top: 20px; padding: 0 4px;">
        {{ $complaints->links() }}
    </div>
</div>
@endsection
