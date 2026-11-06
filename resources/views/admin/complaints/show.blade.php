@extends('layouts.app')

@section('title', 'Proses Tiket #' . $complaint->ticket_number)
@section('page-title', 'Tinjau & Validasi Aduan Sarana')

@section('styles')
<style>
    .admin-grid {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 24px;
    }

    .status-select-btn {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
        margin-bottom: 14px;
    }

    .status-radio {
        display: none;
    }

    .status-radio-label {
        padding: 10px 8px;
        text-align: center;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        display: block;
    }

    .status-radio:checked + .status-radio-label.in_progress {
        border-color: #7c4253;
        background-color: #f2e8e5;
        color: #4d1b2a;
    }

    .status-radio:checked + .status-radio-label.resolved {
        border-color: #16a34a;
        background-color: #f0fdf4;
        color: #166534;
    }

    .status-radio:checked + .status-radio-label.rejected {
        border-color: #dc2626;
        background-color: #fef2f2;
        color: #991b1b;
    }

    /* Urgency Radio Selector */
    .urgency-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-bottom: 14px;
    }

    .urgency-radio {
        display: none;
    }

    .urgency-radio-label {
        padding: 9px 10px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }

    .urgency-radio:checked + .urgency-radio-label.rendah {
        border-color: #16a34a;
        background-color: #f0fdf4;
        color: #166534;
    }

    .urgency-radio:checked + .urgency-radio-label.sedang {
        border-color: #d97706;
        background-color: #fffbeb;
        color: #92400e;
    }

    .urgency-radio:checked + .urgency-radio-label.tinggi {
        border-color: #ea580c;
        background-color: #fff7ed;
        color: #9a3412;
    }

    .urgency-radio:checked + .urgency-radio-label.darurat {
        border-color: #dc2626;
        background-color: #fef2f2;
        color: #991b1b;
    }

    @media (max-width: 992px) {
        .admin-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div style="margin-bottom: 18px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
    <a href="{{ route('admin.complaints.index') }}" class="btn-outline">
        <i class="bi bi-arrow-left"></i> Kembali ke Rekap Aduan
    </a>
    
    <form action="{{ route('admin.complaints.destroy', $complaint->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data tiket ini secara permanen?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn-danger" style="padding: 6px 12px; font-size: 12.5px;">
            <i class="bi bi-trash-fill"></i> Hapus Tiket
        </button>
    </form>
</div>

<div class="admin-grid">
    <!-- Left Column: Details & Evidence -->
    <div>
        <div class="card">
            <div class="card-header">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="font-family: monospace; font-size: 16px; font-weight: 800; color: #350713;">
                        {{ $complaint->ticket_number }}
                    </span>
                    <span class="badge badge-{{ $complaint->status }}">
                        {{ $complaint->status_label }}
                    </span>
                </div>
                <div style="font-size: 12.5px; color: #64748b;">
                    {{ $complaint->created_at->format('d F Y, H:i') }} WIB
                </div>
            </div>

            <div class="card-body">
                <h2 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">
                    {{ $complaint->title }}
                </h2>

                <div style="margin-bottom: 20px;">
                    <label class="form-label" style="color: #64748b;">Deskripsi Kerusakan dari Pelapor:</label>
                    <p style="font-size: 13.5px; color: #334155; line-height: 1.6; background-color: #f8fafc; padding: 14px; border-radius: 8px; border: 1px solid #e2e8f0; white-space: pre-line;">
                        {{ $complaint->description }}
                    </p>
                </div>

                <!-- Photo Evidence -->
                <div>
                    <label class="form-label"><i class="bi bi-image"></i> Foto Bukti Kerusakan Saat Dilaporkan:</label>
                    @if($complaint->photo_evidence)
                        <button type="button" class="photo-preview" data-photo-src="{{ asset('storage/' . $complaint->photo_evidence) }}" data-photo-alt="Bukti kerusakan">
                            <img src="{{ asset('storage/' . $complaint->photo_evidence) }}" alt="Bukti Kerusakan" style="width: 100%; max-height: 380px; object-fit: cover; border-radius: 8px; border: 1px solid #cbd5e1;">
                        </button>
                    @else
                        <div style="padding: 24px; background-color: #f1f5f9; border-radius: 8px; text-align: center; color: #64748b; font-size: 13px;">
                            <i class="bi bi-camera" style="font-size: 28px; display: block; margin-bottom: 6px;"></i>
                            Foto bukti tidak dilampirkan atau data simulasi awal.
                        </div>
                    @endif
                </div>

                <!-- Photo Resolved (if any) -->
                @php
                    $resolvedLog = $complaint->logs->where('status_to', 'resolved')->first();
                @endphp
                @if($resolvedLog && $resolvedLog->photo_resolved)
                    <div style="margin-top: 24px; padding-top: 18px; border-top: 1px solid #e2e8f0;">
                        <label class="form-label" style="color: #166534; font-weight: 700;">
                            <i class="bi bi-check-circle-fill"></i> Foto Bukti Hasil Perbaikan Selesai:
                        </label>
                        <button type="button" class="photo-preview" data-photo-src="{{ asset('storage/' . $resolvedLog->photo_resolved) }}" data-photo-alt="Bukti hasil perbaikan">
                            <img src="{{ asset('storage/' . $resolvedLog->photo_resolved) }}" alt="Bukti Perbaikan Selesai" style="width: 100%; max-height: 350px; object-fit: cover; border-radius: 8px; border: 2px solid #22c55e;">
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- History Activity Log -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="bi bi-clock-history text-blue-600"></i> Riwayat Penanganan &amp; Catatan Petugas</h3>
            </div>
            <div class="card-body">
                @forelse($complaint->logs as $log)
                    <div style="border-left: 2px solid #7c4253; padding-left: 14px; margin-bottom: 14px; position: relative;">
                        <div style="display: flex; justify-content: space-between; font-size: 12.5px;">
                            <strong style="color: #350713;">{{ $log->status_to_label }}</strong>
                            <span style="color: #94a3b8;">{{ $log->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <p style="font-size: 13px; color: #334155; margin-top: 3px;">{{ $log->notes }}</p>
                        @if($log->user)
                            <small style="font-size: 11px; color: #64748b;">Diproses oleh: {{ $log->user->name }}</small>
                        @endif
                    </div>
                @empty
                    <p style="font-size: 13px; color: #64748b;">Belum ada log penanganan.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right Column: Verification & Status Action Form -->
    <div>
        <!-- Action Card -->
        <div class="card" style="border: 2px solid #7c4253;">
            <div class="card-header" style="background-color: #f2e8e5; padding: 16px 22px; border-radius: 10px 10px 0 0;">
                <h3 class="card-title" style="color: #4d1b2a;"><i class="bi bi-shield-check"></i> Form Validasi &amp; Penanganan Admin</h3>
            </div>

            <form action="{{ route('admin.complaints.status.update', $complaint->id) }}" method="POST" enctype="multipart/form-data" class="card-body">
                @csrf
                @method('PUT')

                <!-- 1. Validasi Kondisi Kerusakan (Ditentukan oleh Admin) -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="bi bi-clipboard-check"></i> 1. Tentukan Kondisi / Tingkat Kerusakan: <span style="color: #ef4444;">*</span>
                    </label>
                    <div class="urgency-grid">
                        <div>
                            <input type="radio" name="urgency" id="urg_rendah" value="rendah" class="urgency-radio" {{ old('urgency', $complaint->urgency) === 'rendah' ? 'checked' : '' }}>
                            <label for="urg_rendah" class="urgency-radio-label rendah">
                                <i class="bi bi-info-circle"></i> Rusak Ringan
                            </label>
                        </div>
                        <div>
                            <input type="radio" name="urgency" id="urg_sedang" value="sedang" class="urgency-radio" {{ old('urgency', $complaint->urgency ?? 'sedang') === 'sedang' ? 'checked' : '' }}>
                            <label for="urg_sedang" class="urgency-radio-label sedang">
                                <i class="bi bi-dash-circle"></i> Rusak Sedang
                            </label>
                        </div>
                        <div>
                            <input type="radio" name="urgency" id="urg_tinggi" value="tinggi" class="urgency-radio" {{ old('urgency', $complaint->urgency) === 'tinggi' ? 'checked' : '' }}>
                            <label for="urg_tinggi" class="urgency-radio-label tinggi">
                                <i class="bi bi-exclamation-triangle"></i> Rusak Berat
                            </label>
                        </div>
                        <div>
                            <input type="radio" name="urgency" id="urg_darurat" value="darurat" class="urgency-radio" {{ old('urgency', $complaint->urgency) === 'darurat' ? 'checked' : '' }}>
                            <label for="urg_darurat" class="urgency-radio-label darurat">
                                <i class="bi bi-lightning-fill"></i> Sangat Berat / Darurat
                            </label>
                        </div>
                    </div>
                    <small style="color: #64748b; font-size: 11.5px; display: block; margin-top: -6px; margin-bottom: 12px;">
                        Pilih tingkat keparahan setelah meninjau foto bukti kerusakan.
                    </small>
                </div>

                <!-- 2. Status Penanganan Baru -->
                <div class="form-group">
                    <label class="form-label">2. Status Penanganan Tiket: <span style="color: #ef4444;">*</span></label>
                    <div class="status-select-btn">
                        <div>
                            <input type="radio" name="status" id="st_progress" value="in_progress" class="status-radio" {{ old('status', $complaint->status === 'pending' ? 'in_progress' : $complaint->status) === 'in_progress' ? 'checked' : '' }} onchange="toggleProofUpload(this.value)">
                            <label for="st_progress" class="status-radio-label in_progress">
                                <i class="bi bi-gear-fill"></i> Diproses
                            </label>
                        </div>
                        <div>
                            <input type="radio" name="status" id="st_resolved" value="resolved" class="status-radio" {{ old('status', $complaint->status) === 'resolved' ? 'checked' : '' }} onchange="toggleProofUpload(this.value)">
                            <label for="st_resolved" class="status-radio-label resolved">
                                <i class="bi bi-check-circle-fill"></i> Selesai
                            </label>
                        </div>
                        <div>
                            <input type="radio" name="status" id="st_rejected" value="rejected" class="status-radio" {{ old('status', $complaint->status) === 'rejected' ? 'checked' : '' }} onchange="toggleProofUpload(this.value)">
                            <label for="st_rejected" class="status-radio-label rejected">
                                <i class="bi bi-x-circle-fill"></i> Tolak
                            </label>
                        </div>
                    </div>
                </div>

                <!-- 3. Catatan Tindak Lanjut (OPSIONAL) -->
                <div class="form-group">
                    <label class="form-label" for="notes">
                        3. Catatan Tindak Lanjut <span style="color: #64748b; font-weight: normal; font-size: 12px;">(Opsional)</span>
                    </label>
                    <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Tuliskan catatan teknisi jika ada (opsional, boleh dikosongkan)...">{{ old('notes') }}</textarea>
                    <small style="color: #64748b; font-size: 11.5px; display: block; margin-top: 3px;">
                        Boleh dikosongkan. Jika kosong, sistem otomatis membuat catatan standar penanganan.
                    </small>
                </div>

                <!-- Proof Upload (Show when Resolved) -->
                <div class="form-group" id="proofUploadGroup" style="{{ old('status', $complaint->status) === 'resolved' ? '' : 'display: none;' }}">
                    <label class="form-label" for="photo_resolved">
                        <i class="bi bi-camera"></i> Unggah Foto Bukti Perbaikan Selesai (Opsional)
                    </label>
                    <input type="file" name="photo_resolved" id="photo_resolved" class="form-control" accept="image/*">
                    <small style="color: #64748b; font-size: 11.5px;">Lampirkan foto sarana setelah berhasil diperbaiki oleh teknisi.</small>
                </div>

                <button type="submit" class="btn-primary" style="width: 100%; padding: 12px; font-size: 14px; justify-content: center; margin-top: 10px;">
                    <i class="bi bi-check2-circle"></i> Simpan Validasi &amp; Update Status
                </button>
            </form>
        </div>

        <!-- Object Details -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="bi bi-info-circle-fill text-blue-600"></i> Informasi Pelapor &amp; Lokasi</h3>
            </div>
            <div class="card-body" style="font-size: 13px; display: flex; flex-direction: column; gap: 10px;">
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #f1f5f9; padding-bottom: 6px;">
                    <span style="color: #64748b;">Nama Pelapor</span>
                    <strong>{{ $complaint->user->name }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #f1f5f9; padding-bottom: 6px;">
                    <span style="color: #64748b;">{{ ($complaint->user->role ?? '') === 'guru' ? 'NIP' : 'NIS' }}</span>
                    <span>{{ $complaint->user->nis ?? '-' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #f1f5f9; padding-bottom: 6px;">
                    <span style="color: #64748b;">No. HP / WhatsApp</span>
                    <span>{{ $complaint->user->phone ?? '-' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #f1f5f9; padding-bottom: 6px;">
                    <span style="color: #64748b;">Kategori Sarana</span>
                    <strong>{{ $complaint->category->name }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #f1f5f9; padding-bottom: 6px;">
                    <span style="color: #64748b;">Lokasi / Ruangan</span>
                    <span>{{ $complaint->location->name }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #f1f5f9; padding-bottom: 6px;">
                    <span style="color: #64748b;">Gedung &amp; Lantai</span>
                    <span>{{ $complaint->location->building ?? '-' }} ({{ $complaint->location->floor ?? '-' }})</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: #64748b;">Kondisi Kerusakan</span>
                    @if($complaint->urgency)
                        <span class="badge urgency-{{ $complaint->urgency }}">{{ $complaint->urgency_label }}</span>
                    @else
                        <span class="badge" style="background-color: #f1f5f9; color: #64748b; border: 1px dashed #cbd5e1;">
                            <i class="bi bi-clock"></i> Belum Divalidasi
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleProofUpload(status) {
        const uploadGroup = document.getElementById('proofUploadGroup');
        if (status === 'resolved') {
            uploadGroup.style.display = 'block';
        } else {
            uploadGroup.style.display = 'none';
        }
    }
</script>
@endsection
