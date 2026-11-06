@extends('layouts.app')

@section('title', 'Detail Tiket #' . $complaint->ticket_number)
@section('page-title', 'Detail & Tracking Pengaduan')

@section('styles')
<style>
    .ticket-header-card {
        background: linear-gradient(135deg, #350713, #7c4253);
        color: #ffffff;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
    }

    .ticket-badge-lg {
        font-size: 14px;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 8px;
        background-color: #ffffff;
    }

    /* Timeline Stepper */
    .timeline-tracker {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin: 30px 10px 40px 10px;
    }

    .timeline-tracker::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 30px;
        right: 30px;
        height: 4px;
        background-color: #e2e8f0;
        z-index: 1;
    }

    .timeline-step {
        position: relative;
        z-index: 2;
        text-align: center;
        flex: 1;
    }

    .step-circle {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background-color: #ffffff;
        border: 4px solid #cbd5e1;
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px auto;
        font-size: 18px;
        font-weight: 700;
        transition: all 0.3s ease;
    }

    .timeline-step.completed .step-circle {
        background-color: #22c55e;
        border-color: #16a34a;
        color: #ffffff;
    }

    .timeline-step.active .step-circle {
        background-color: #5c1a2a;
        border-color: #491020;
        color: #ffffff;
        box-shadow: 0 0 0 5px rgba(37, 99, 235, 0.2);
    }

    .timeline-step.rejected .step-circle {
        background-color: #ef4444;
        border-color: #dc2626;
        color: #ffffff;
    }

    .step-title {
        font-size: 13px;
        font-weight: 700;
        color: #1e293b;
    }

    .step-desc {
        font-size: 11.5px;
        color: #64748b;
        margin-top: 2px;
    }

    .show-grid {
        display: grid;
        grid-template-columns: 1.6fr 1fr;
        gap: 24px;
    }

    .info-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 8px;
        font-size: 13.5px;
    }

    .info-label {
        color: #64748b;
    }

    .info-value {
        font-weight: 600;
        color: #0f172a;
    }

    .log-item {
        border-left: 2px solid #7c4253;
        padding-left: 14px;
        margin-bottom: 14px;
        position: relative;
    }

    @media (max-width: 900px) {
        .show-grid {
            grid-template-columns: 1fr;
        }
        .timeline-tracker {
            flex-direction: column;
            gap: 20px;
        }
        .timeline-tracker::before {
            display: none;
        }
        .timeline-step {
            display: flex;
            align-items: center;
            gap: 14px;
            text-align: left;
        }
        .step-circle {
            margin: 0;
            flex-shrink: 0;
        }
    }
</style>
@endsection

@section('content')
<div style="margin-bottom: 18px;">
    <a href="{{ route('complaints.index') }}" class="btn-outline">
        <i class="bi bi-arrow-left"></i> Kembali ke Riwayat Aduan
    </a>
</div>

<!-- Header Card -->
<div class="ticket-header-card">
    <div>
        <div style="font-size: 12.5px; text-transform: uppercase; letter-spacing: 0.8px; opacity: 0.85;">
            Nomor Tiket Aduan
        </div>
        <h2 style="font-size: 26px; font-weight: 800; font-family: monospace; margin: 4px 0;">
            {{ $complaint->ticket_number }}
        </h2>
        <div style="font-size: 13px; opacity: 0.9;">
            <i class="bi bi-clock"></i> Dilaporkan pada {{ $complaint->created_at->format('d F Y, H:i') }} WIB
        </div>
    </div>
    <div>
        <span class="ticket-badge-lg" style="
            @if($complaint->status === 'pending') color: #92400e; border: 2px solid #fde68a;
            @elseif($complaint->status === 'in_progress') color: #4d1b2a; border: 2px solid #ddc9c4;
            @elseif($complaint->status === 'resolved') color: #166534; border: 2px solid #bbf7d0;
            @else color: #991b1b; border: 2px solid #fecaca;
            @endif
        ">
            @if($complaint->status === 'pending') <i class="bi bi-hourglass-split"></i>
            @elseif($complaint->status === 'in_progress') <i class="bi bi-gear-fill"></i>
            @elseif($complaint->status === 'resolved') <i class="bi bi-check2-circle"></i>
            @else <i class="bi bi-x-circle"></i>
            @endif
            {{ $complaint->status_label }}
        </span>
    </div>
</div>

<!-- Progress Timeline -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="bi bi-diagram-3-fill text-blue-600"></i> Alur Penanganan Aduan</h3>
    </div>
    <div class="card-body">
        <div class="timeline-tracker">
            <!-- Step 1: Laporan Dibuat -->
            <div class="timeline-step completed">
                <div class="step-circle"><i class="bi bi-send-check"></i></div>
                <div class="step-title">1. Aduan Masuk</div>
                <div class="step-desc">{{ $complaint->created_at->format('d/m/Y H:i') }}</div>
            </div>

            <!-- Step 2: Verifikasi & Validasi Petugas -->
            <div class="timeline-step {{ $complaint->status === 'pending' ? 'active' : ($complaint->status === 'rejected' ? 'rejected' : 'completed') }}">
                <div class="step-circle">
                    @if($complaint->status === 'rejected') <i class="bi bi-x-lg"></i>
                    @else <i class="bi bi-clipboard-check"></i>
                    @endif
                </div>
                <div class="step-title">2. Validasi Petugas</div>
                <div class="step-desc">
                    @if($complaint->status === 'pending') Menunggu Verifikasi
                    @elseif($complaint->status === 'rejected') Laporan Ditolak
                    @else Telah Divalidasi
                    @endif
                </div>
            </div>

            <!-- Step 3: Pengerjaan Teknis -->
            <div class="timeline-step {{ $complaint->status === 'in_progress' ? 'active' : ($complaint->status === 'resolved' ? 'completed' : '') }}">
                <div class="step-circle"><i class="bi bi-tools"></i></div>
                <div class="step-title">3. Pengerjaan</div>
                <div class="step-desc">
                    @if($complaint->status === 'in_progress') Sedang Dikerjakan
                    @elseif($complaint->status === 'resolved') Selesai Diperbaiki
                    @else Menunggu Giliran
                    @endif
                </div>
            </div>

            <!-- Step 4: Selesai -->
            <div class="timeline-step {{ $complaint->status === 'resolved' ? 'completed' : '' }}">
                <div class="step-circle"><i class="bi bi-patch-check-fill"></i></div>
                <div class="step-title">4. Selesai</div>
                <div class="step-desc">
                    @if($complaint->status === 'resolved') Tuntas
                    @else -
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="show-grid">
    <!-- Left Column: Details & Photos -->
    <div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="bi bi-card-text text-blue-600"></i> Uraian Kerusakan</h3>
            </div>
            <div class="card-body">
                <div style="margin-bottom: 20px;">
                    <h4 style="font-size: 17px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">
                        {{ $complaint->title }}
                    </h4>
                    <p style="font-size: 14px; color: #334155; line-height: 1.6; white-space: pre-line; background-color: #f8fafc; padding: 14px; border-radius: 8px; border: 1px solid #e2e8f0;">
                        {{ $complaint->description }}
                    </p>
                </div>

                <!-- Photo Evidence -->
                <div>
                    <h5 style="font-size: 14px; font-weight: 700; color: #1e293b; margin-bottom: 10px;">
                        <i class="bi bi-image"></i> Foto Bukti Kerusakan Saat Dilaporkan:
                    </h5>
                    @if($complaint->photo_evidence)
                        <button type="button" class="photo-preview" data-photo-src="{{ asset('storage/' . $complaint->photo_evidence) }}" data-photo-alt="Bukti kerusakan">
                            <img src="{{ asset('storage/' . $complaint->photo_evidence) }}" alt="Bukti Kerusakan" style="width: 100%; max-height: 350px; object-fit: cover; border-radius: 8px; border: 1px solid #cbd5e1;">
                        </button>
                    @else
                        <div style="padding: 20px; background-color: #f1f5f9; border-radius: 8px; text-align: center; color: #64748b; font-size: 13px;">
                            <i class="bi bi-camera-fill" style="font-size: 24px; display: block; margin-bottom: 4px;"></i>
                            Foto bukti tidak dilampirkan atau menggunakan data awal.
                        </div>
                    @endif
                </div>

                <!-- Photo Resolved (if any) -->
                @php
                    $resolvedLog = $complaint->logs->where('status_to', 'resolved')->first();
                @endphp
                @if($resolvedLog && $resolvedLog->photo_resolved)
                    <div style="margin-top: 24px; padding-top: 18px; border-top: 1px solid #e2e8f0;">
                        <h5 style="font-size: 14px; font-weight: 700; color: #166534; margin-bottom: 10px;">
                            <i class="bi bi-patch-check-fill text-green-600"></i> Foto Bukti Hasil Perbaikan oleh Petugas:
                        </h5>
                        <button type="button" class="photo-preview" data-photo-src="{{ asset('storage/' . $resolvedLog->photo_resolved) }}" data-photo-alt="Bukti hasil perbaikan">
                            <img src="{{ asset('storage/' . $resolvedLog->photo_resolved) }}" alt="Bukti Perbaikan Selesai" style="width: 100%; max-height: 350px; object-fit: cover; border-radius: 8px; border: 2px solid #22c55e;">
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Right Column: Meta Info & History Logs -->
    <div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="bi bi-info-circle-fill text-blue-600"></i> Rincian Objek &amp; Validasi</h3>
            </div>
            <div class="card-body">
                <div class="info-list">
                    <div class="info-row">
                        <span class="info-label">Kategori Sarana</span>
                        <span class="info-value">{{ $complaint->category->name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Lokasi / Ruang</span>
                        <span class="info-value">{{ $complaint->location->name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Gedung / Lantai</span>
                        <span class="info-value">{{ $complaint->location->building ?? '-' }} ({{ $complaint->location->floor ?? '-' }})</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Kondisi Kerusakan</span>
                        @if($complaint->urgency)
                            <span class="badge urgency-{{ $complaint->urgency }}">{{ $complaint->urgency_label }}</span>
                        @else
                            <span class="badge" style="background-color: #f1f5f9; color: #64748b; border: 1px dashed #cbd5e1; font-size: 11px;">
                                <i class="bi bi-clock"></i> Menunggu Validasi Admin
                            </span>
                        @endif
                    </div>
                    <div class="info-row">
                        <span class="info-label">Nama Pelapor</span>
                        <span class="info-value">{{ $complaint->user->name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">{{ ($complaint->user->role ?? '') === 'guru' ? 'NIP Guru' : 'NIS Siswa' }}</span>
                        <span class="info-value">{{ $complaint->user->nis ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- History Activity Logs -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="bi bi-activity text-blue-600"></i> Riwayat Tanggapan &amp; Log</h3>
            </div>
            <div class="card-body">
                @forelse($complaint->logs as $log)
                    <div class="log-item">
                        <div style="display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 2px;">
                            <strong style="color: #350713;">{{ $log->status_to_label }}</strong>
                            <span style="color: #94a3b8;">{{ $log->created_at->format('d M, H:i') }}</span>
                        </div>
                        <p style="font-size: 12.5px; color: #334155; margin-top: 2px;">
                            {{ $log->notes }}
                        </p>
                        @if($log->user)
                            <small style="font-size: 11px; color: #64748b;">Oleh: {{ $log->user->name }} ({{ $log->user->role }})</small>
                        @endif
                    </div>
                @empty
                    <p style="font-size: 13px; color: #64748b;">Belum ada catatan aktivitas.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
