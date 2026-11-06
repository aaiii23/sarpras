@extends('layouts.app')

@section('title', 'Dashboard Administrator')
@section('page-title', 'Dashboard Utama Administrator')

@section('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 18px;
        margin-bottom: 24px;
    }

    .stat-card {
        background-color: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        text-decoration: none;
        color: inherit;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.06);
        border-color: #cbd5e1;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .stat-info h3 {
        font-size: 24px;
        font-weight: 800;
        color: var(--text-dark);
        line-height: 1;
        margin-bottom: 4px;
    }

    .stat-info p {
        font-size: 12.5px;
        color: var(--text-muted);
        margin: 0;
        font-weight: 600;
    }

    .charts-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
        margin-bottom: 24px;
    }

    @media (max-width: 992px) {
        .charts-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Dashboard Complaints List (Anti-Horizontal Scroll / No Slide) */
    .dashboard-complaints-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
        padding: 16px;
    }

    .dashboard-complaint-item {
        background-color: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 16px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }

    .dashboard-complaint-item:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(53, 7, 19, 0.05);
        transform: translateY(-1px);
    }

    .dashboard-item-info {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .dashboard-item-top {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .dashboard-item-title {
        font-size: 14.5px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }

    .dashboard-item-meta {
        display: flex;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
        font-size: 12px;
        color: #64748b;
    }

    .dashboard-item-meta span {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .dashboard-item-meta i {
        color: #7c4253;
    }

    .dashboard-item-action {
        flex-shrink: 0;
    }

    .dashboard-btn-detail {
        padding: 8px 16px;
        font-size: 13px;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    @media (max-width: 768px) {
        .dashboard-complaint-item {
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
            padding: 14px;
        }

        .dashboard-item-action .btn-primary {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endsection

@section('content')
<!-- Workflow Stat Cards (Direct Clickable to Tabs) -->
<div class="stats-grid">
    <a href="{{ route('admin.complaints.index', ['tab' => 'all']) }}" class="stat-card">
        <div class="stat-icon" style="background-color: #f2e8e5; color: #5c1a2a;">
            <i class="bi bi-inbox-fill"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $totalComplaints }}</h3>
            <p>Total Semua Aduan</p>
        </div>
    </a>

    <a href="{{ route('admin.complaints.index', ['tab' => 'unvalidated']) }}" class="stat-card" style="border-left: 4px solid #f59e0b;">
        <div class="stat-icon" style="background-color: #fef3c7; color: #d97706;">
            <i class="bi bi-hourglass-split"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $pendingComplaints }}</h3>
            <p style="color: #b45309;">1. Belum Divalidasi</p>
        </div>
    </a>

    <a href="{{ route('admin.complaints.index', ['tab' => 'in_progress']) }}" class="stat-card" style="border-left: 4px solid #7c4253;">
        <div class="stat-icon" style="background-color: #eadde0; color: #5c1a2a;">
            <i class="bi bi-tools"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $inProgressComplaints }}</h3>
            <p style="color: #5d454b;">2. Sedang Dikerjakan</p>
        </div>
    </a>

    <a href="{{ route('admin.complaints.index', ['tab' => 'resolved']) }}" class="stat-card" style="border-left: 4px solid #10b981;">
        <div class="stat-icon" style="background-color: #dcfce7; color: #16a34a;">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $resolvedComplaints }}</h3>
            <p style="color: #15803d;">3. Sudah Selesai</p>
        </div>
    </a>

    <a href="{{ route('admin.complaints.index', ['tab' => 'rejected']) }}" class="stat-card">
        <div class="stat-icon" style="background-color: #fee2e2; color: #dc2626;">
            <i class="bi bi-x-circle-fill"></i>
        </div>
        <div class="stat-info">
            <h3>{{ $rejectedComplaints }}</h3>
            <p>Ditolak</p>
        </div>
    </a>
</div>

<!-- Charts & Quick Summary -->
<div class="charts-grid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="bi bi-bar-chart-fill text-blue-600"></i> Distribusi Aduan Berdasarkan Kategori Sarana</h3>
        </div>
        <div class="card-body" style="height: 260px; position: relative;">
            <canvas id="categoryChart"></canvas>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="bi bi-pie-chart-fill text-indigo-600"></i> Status Rekap Penanganan</h3>
        </div>
        <div class="card-body" style="height: 260px; position: relative; display: flex; align-items: center; justify-content: center;">
            <canvas id="statusChart"></canvas>
        </div>
    </div>
</div>

<!-- Recent Active Complaints List (No Slide / Direct Action) -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="bi bi-clock-history text-amber-600"></i> Pengaduan Perlu Penanganan
            <span class="badge" style="background: #fef3c7; color: #92400e; font-size: 11px; margin-left: 6px; border: 1px solid #fde68a;">
                Baru Masuk &amp; Sedang Dikerjakan
            </span>
        </h3>
        <a href="{{ route('admin.complaints.index') }}" class="btn-outline" style="font-size: 12.5px; padding: 6px 14px;">
            Buka Pengelolaan Lengkap <i class="bi bi-arrow-right"></i>
        </a>
    </div>

    <div class="dashboard-complaints-list">
        @forelse($recentComplaints as $complaint)
            <div class="dashboard-complaint-item">
                <div class="dashboard-item-info">
                    <div class="dashboard-item-top">
                        <span style="font-family: monospace; font-size: 12px; font-weight: 700; color: #350713; background: #f2e8e5; padding: 3px 8px; border-radius: 6px;">
                            <i class="bi bi-ticket-perforated"></i> {{ $complaint->ticket_number }}
                        </span>
                        
                        <span class="badge badge-{{ $complaint->status }}" style="font-size: 11px;">
                            @if($complaint->status === 'pending')
                                <i class="bi bi-hourglass-split"></i> Belum Diverifikasi
                            @else
                                <i class="bi bi-gear-fill"></i> Sedang Dikerjakan
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

                        <span style="font-size: 11.5px; color: #94a3b8; margin-left: auto;">
                            <i class="bi bi-calendar3"></i> {{ $complaint->created_at->format('d M Y, H:i') }}
                        </span>
                    </div>

                    <h4 class="dashboard-item-title">{{ $complaint->title }}</h4>

                    <div class="dashboard-item-meta">
                        <span>
                            <i class="bi bi-person-circle"></i>
                            <strong>{{ $complaint->user->name }}</strong>
                            <small>({{ ($complaint->user->role ?? '') === 'guru' ? 'NIP' : 'NIS' }}: {{ $complaint->user->nis ?? '-' }})</small>
                        </span>
                        <span>
                            <i class="bi bi-folder2-open"></i> {{ $complaint->category->name }}
                        </span>
                        <span>
                            <i class="bi bi-geo-alt-fill"></i> {{ $complaint->location->name }}
                        </span>
                    </div>
                </div>

                <div class="dashboard-item-action">
                    <a href="{{ route('admin.complaints.show', $complaint->id) }}" class="btn-primary dashboard-btn-detail">
                        @if($complaint->status === 'pending')
                            <i class="bi bi-shield-check"></i> Validasi Sekarang
                        @else
                            <i class="bi bi-arrow-right-circle"></i> Lihat Detail
                        @endif
                    </a>
                </div>
            </div>
        @empty
            <div style="background-color: #ffffff; border: 1px dashed var(--border-color); border-radius: 12px; padding: 36px 20px; text-align: center; color: #64748b;">
                <i class="bi bi-check2-circle" style="font-size: 38px; color: #16a34a; display: block; margin-bottom: 8px;"></i>
                <strong style="color: #1e293b; font-size: 14px; display: block; margin-bottom: 4px;">Tidak Ada Antrean Pengaduan Aktif</strong>
                Semua pengaduan yang baru masuk telah diverifikasi dan diselesaikan.
            </div>
        @endforelse
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Category Distribution Bar Chart
    const catCtx = document.getElementById('categoryChart').getContext('2d');
    const catLabels = {!! json_encode($categoriesStats->pluck('name')) !!};
    const catData = {!! json_encode($categoriesStats->pluck('complaints_count')) !!};

    new Chart(catCtx, {
        type: 'bar',
        data: {
            labels: catLabels,
            datasets: [{
                label: 'Jumlah Aduan',
                data: catData,
                backgroundColor: '#7c4253',
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                },
                x: {
                    ticks: { font: { size: 11 } }
                }
            }
        }
    });

    // Status Doughnut Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['1. Belum Divalidasi', '2. Sedang Dikerjakan', '3. Selesai', 'Ditolak'],
            datasets: [{
                data: [{{ $pendingComplaints }}, {{ $inProgressComplaints }}, {{ $resolvedComplaints }}, {{ $rejectedComplaints }}],
                backgroundColor: ['#d97706', '#7c4253', '#10b981', '#ef4444'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { boxWidth: 12, font: { size: 11 } }
                }
            }
        }
    });
</script>
@endsection
