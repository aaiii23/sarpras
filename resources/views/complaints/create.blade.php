@extends('layouts.app')

@section('title', 'Ajukan Pengaduan Sarana')
@section('page-title', 'Formulir Pengaduan Sarana')

@section('styles')
<style>
    .form-container {
        max-width: 820px;
        margin: 0 auto;
    }

    .form-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
    }

    .image-drop-area {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 26px 20px;
        text-align: center;
        background-color: #f8fafc;
        cursor: pointer;
        transition: all 0.25s ease;
        margin-top: 6px;
        position: relative;
    }

    .image-drop-area:hover {
        border-color: #7c4253;
        background-color: #f2e8e5;
    }

    .image-drop-area.drop-invalid {
        border-color: #dc2626 !important;
        background-color: #fef2f2 !important;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.18) !important;
        animation: shakeDrop 0.4s ease-in-out;
    }

    @keyframes shakeDrop {
        0%, 100% { transform: translateX(0); }
        20%, 60% { transform: translateX(-6px); }
        40%, 80% { transform: translateX(6px); }
    }

    .form-control.field-invalid {
        border-color: #dc2626 !important;
        background-color: #fffafb !important;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.16) !important;
    }

    #imagePreview {
        max-height: 240px;
        border-radius: 8px;
        margin-top: 14px;
        display: none;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        object-fit: cover;
        margin-left: auto;
        margin-right: auto;
        width: 100%;
        max-width: 420px;
    }

    .info-banner {
        background: linear-gradient(135deg, #f6efeb, #eadde0);
        border-left: 4px solid #7c4253;
        border-radius: 8px;
        padding: 14px 16px;
        margin-bottom: 22px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        font-size: 13px;
        color: #4d1b2a;
    }

    /* Missing items list in modal */
    .missing-item-row {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        line-height: 1.4;
    }

    .missing-item-icon {
        color: #dc2626;
        font-size: 16px;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .missing-item-label {
        font-weight: 700;
        color: #1f2937;
    }

    .missing-item-reason {
        color: #b91c1c;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .form-grid-2 {
            grid-template-columns: 1fr;
            gap: 14px;
        }
    }
</style>
@endsection

@section('content')
<div class="form-container">
    <!-- Info Banner -->
    <div class="info-banner">
        <i class="bi bi-info-circle-fill" style="font-size: 20px; color: #5c1a2a; flex-shrink: 0; margin-top: 1px;"></i>
        <div>
            <strong>Panduan Pengaduan Sarana Sekolah:</strong><br>
            Seluruh data pada formulir pengaduan <strong>wajib diisi lengkap</strong> beserta lampiran foto bukti kerusakan agar petugas Sarpras dapat langsung memverifikasi dan menindaklanjuti.
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="bi bi-pencil-square" style="color: #5c1a2a;"></i> Formulir Laporan Kerusakan
            </h2>
            <span style="font-size: 12px; color: #dc2626; font-weight: 700;">
                <i class="bi bi-asterisk"></i> Semua Isian Wajib Dilengkapi
            </span>
        </div>

        <form id="complaintForm" action="{{ route('complaints.store') }}" method="POST" enctype="multipart/form-data" class="card-body" novalidate>
            @csrf

            <!-- 1. Nama Barang / Objek Kerusakan -->
            <div class="form-group">
                <label class="form-label" for="title">
                    Nama Barang / Fasilitas &amp; Kerusakannya <span style="color: #ef4444;">*</span>
                </label>
                <input type="text" name="title" id="title" class="form-control" placeholder="Contoh: LCD Proyektor Mati / Kran Wastafel Patah / AC Tidak Dingin" value="{{ old('title') }}" required autofocus>
                <small style="color: #64748b; font-size: 12px; margin-top: 4px; display: block;">
                    Ketikkan nama fasilitas atau sarana yang rusak dengan jelas.
                </small>
            </div>

            <!-- 2. Kategori Sarana & Lokasi (2 Kolom Responsif) -->
            <div class="form-grid-2">
                <!-- Kategori Sarana -->
                <div class="form-group">
                    <label class="form-label" for="category_id">
                        Kategori Sarana <span style="color: #ef4444;">*</span>
                    </label>
                    <select name="category_id" id="category_id" class="form-control" required>
                        <option value="">-- Pilih Jenis Sarana --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Lokasi / Ruangan -->
                <div class="form-group">
                    <label class="form-label" for="location_id">
                        Lokasi / Ruangan <span style="color: #ef4444;">*</span>
                    </label>
                    <select name="location_id" id="location_id" class="form-control" required>
                        <option value="">-- Pilih Lokasi / Ruangan --</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                {{ $location->name }}
                            </option>
                        @endforeach
                    </select>
                    <small style="color: #64748b; font-size: 11.5px; margin-top: 3px; display: block;">
                        Pilih lokasi atau ruangan tempat sarana berada.
                    </small>
                </div>
            </div>

            <!-- 3. Deskripsi Detail Kerusakan -->
            <div class="form-group" style="margin-top: 14px;">
                <label class="form-label" for="description">
                    Deskripsi Detail Kerusakan <span style="color: #ef4444;">*</span>
                </label>
                <textarea name="description" id="description" class="form-control" rows="4" placeholder="Jelaskan detail kerusakan fasilitas, posisi barang di dalam ruangan, dan kendala yang dialami..." required>{{ old('description') }}</textarea>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 5px; flex-wrap: wrap; gap: 6px;">
                    <small style="color: #64748b; font-size: 11.5px;">
                        <i class="bi bi-info-circle"></i> Minimal 10 karakter agar informasi kerusakan jelas.
                    </small>
                    <span id="descCharCount" style="font-size: 11.5px; font-weight: 700; color: #94a3b8; background: #f1f5f9; padding: 2px 8px; border-radius: 6px;">
                        0 / 10 karakter
                    </span>
                </div>
            </div>

            <!-- 4. Upload Foto Bukti (Dukungan Kamera HP & Galeri) -->
            <div class="form-group" style="margin-top: 14px;">
                <label class="form-label">
                    Unggah Foto Bukti Kerusakan <span style="color: #ef4444;">*</span>
                </label>
                <div class="image-drop-area" id="imageDropArea" onclick="document.getElementById('photo_evidence').click()">
                    <i class="bi bi-camera-fill" style="font-size: 38px; color: #5c1a2a;"></i>
                    <div style="font-size: 14px; font-weight: 700; color: #0f172a; margin-top: 8px;">
                        Klik untuk Ambil Foto (Kamera HP) atau Pilih Galeri
                    </div>
                    <div style="font-size: 12px; color: #64748b; margin-top: 4px;">
                        Format gambar: JPG, JPEG, PNG, atau WEBP (Maksimal 3MB)
                    </div>

                    <input type="file" name="photo_evidence" id="photo_evidence" accept="image/*" capture="environment" style="display: none;" onchange="handlePhotoChange(this)" required>

                    <!-- Preview Badge File Info -->
                    <div id="fileSelectedBadge" style="display: none; margin-top: 12px; background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 8px; padding: 8px 14px; color: #065f46; font-size: 12.5px; font-weight: 600; text-align: center;">
                        <i class="bi bi-check-circle-fill" style="color: #10b981;"></i> Foto terpilih: <span id="fileNameLabel"></span>
                        <button type="button" onclick="clearPhoto(event)" style="background: none; border: none; color: #dc2626; margin-left: 10px; font-size: 12px; font-weight: 700; text-decoration: underline; cursor: pointer;">
                            <i class="bi bi-trash"></i> Hapus / Ganti
                        </button>
                    </div>

                    <img id="imagePreview" alt="Pratinjau Foto Kerusakan">
                </div>
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; border-top: 1px solid #e2e8f0; padding-top: 18px; flex-wrap: wrap;">
                <a href="{{ route('complaints.index') }}" class="btn-outline" style="padding: 10px 20px;">Batalkan</a>
                <button type="submit" id="submitBtn" class="btn-primary" style="padding: 11px 28px; font-size: 14px; font-weight: 700; box-shadow: 0 4px 12px rgba(92, 26, 42, 0.25);">
                    <i class="bi bi-send-fill"></i> Kirim Laporan Pengaduan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Popup Peringatan Form Belum Lengkap -->
<dialog class="app-confirm-dialog" id="formValidationModal" aria-labelledby="formValidationTitle" style="max-width: 460px; padding: 26px;">
    <div class="confirm-dialog-content">
        <div class="confirm-icon-box" style="background-color: #fef3c7; color: #d97706; width: 66px; height: 66px; font-size: 30px; margin-bottom: 14px;">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </div>
        
        <h3 id="formValidationTitle" class="confirm-dialog-title" style="color: #350713; font-size: 20px; margin-bottom: 6px;">
            Formulir Belum Lengkap!
        </h3>
        
        <p class="confirm-dialog-desc" style="color: #64748b; font-size: 13px; line-height: 1.5; margin-bottom: 18px;">
            Laporan pengaduan <strong>tidak dapat dikirim</strong> karena masih ada isian wajib yang belum lengkap. Mohon lengkapi bagian berikut:
        </p>

        <!-- Dynamic List of Missing Fields -->
        <div style="background-color: #fff9f6; border: 1px solid #fed7aa; border-radius: 10px; padding: 14px 16px; margin-bottom: 22px; text-align: left;">
            <div style="font-size: 11.5px; font-weight: 800; color: #c2410c; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px; display: flex; align-items: center; gap: 6px;">
                <i class="bi bi-clipboard-x"></i> Bagian yang Masih Perlu Diisi:
            </div>
            <ul id="missingFieldsList" style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 9px; font-size: 13px;">
                <!-- Diisi secara otomatis oleh JavaScript -->
            </ul>
        </div>

        <div class="confirm-dialog-actions" style="justify-content: center;">
            <button type="button" class="btn-primary" id="btnDismissModal" style="width: 100%; justify-content: center; padding: 12px 20px; font-size: 14px; font-weight: 700; background: linear-gradient(135deg, #5c1a2a, #350713); border: none; box-shadow: 0 4px 12px rgba(92, 26, 42, 0.3);">
                <i class="bi bi-pencil-square"></i> Lengkapi Sekarang
            </button>
        </div>
    </div>
</dialog>
@endsection

@section('scripts')
<script>
    const complaintForm = document.getElementById('complaintForm');
    const titleInput = document.getElementById('title');
    const categorySelect = document.getElementById('category_id');
    const locationSelect = document.getElementById('location_id');
    const descriptionText = document.getElementById('description');
    const descCharCount = document.getElementById('descCharCount');
    const photoInput = document.getElementById('photo_evidence');
    const imageDropArea = document.getElementById('imageDropArea');
    const imagePreview = document.getElementById('imagePreview');
    const fileSelectedBadge = document.getElementById('fileSelectedBadge');
    const fileNameLabel = document.getElementById('fileNameLabel');
    const submitBtn = document.getElementById('submitBtn');

    const validationModal = document.getElementById('formValidationModal');
    const missingFieldsList = document.getElementById('missingFieldsList');
    const btnDismissModal = document.getElementById('btnDismissModal');

    let firstInvalidElement = null;

    // Real-time character counter for description
    function updateCharCounter() {
        const len = descriptionText.value.trim().length;
        descCharCount.textContent = len + ' / 10 karakter';
        
        if (len >= 10) {
            descCharCount.style.backgroundColor = '#ecfdf5';
            descCharCount.style.color = '#059669';
            descriptionText.classList.remove('field-invalid');
        } else if (len > 0) {
            descCharCount.style.backgroundColor = '#fef3c7';
            descCharCount.style.color = '#d97706';
        } else {
            descCharCount.style.backgroundColor = '#f1f5f9';
            descCharCount.style.color = '#94a3b8';
        }
    }

    descriptionText.addEventListener('input', updateCharCounter);
    updateCharCounter();

    // Remove invalid style on input
    titleInput.addEventListener('input', () => {
        if (titleInput.value.trim() !== '') {
            titleInput.classList.remove('field-invalid');
        }
    });

    categorySelect.addEventListener('change', () => {
        if (categorySelect.value !== '') {
            categorySelect.classList.remove('field-invalid');
        }
    });

    locationSelect.addEventListener('change', () => {
        if (locationSelect.value !== '') {
            locationSelect.classList.remove('field-invalid');
        }
    });

    // Handle Photo change
    function handlePhotoChange(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            
            // Check file size (max 3MB = 3 * 1024 * 1024)
            if (file.size > 3 * 1024 * 1024) {
                openWarningPopup([{
                    id: 'photo_evidence',
                    label: 'Foto Bukti Kerusakan',
                    reason: 'Ukuran file melebihi 3MB (' + (file.size / (1024 * 1024)).toFixed(1) + 'MB). Silakan pilih foto dengan resolusi lebih kecil.'
                }]);
                clearPhoto();
                return;
            }

            imageDropArea.classList.remove('drop-invalid');

            const reader = new FileReader();
            reader.onload = function (e) {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
            };
            reader.readAsDataURL(file);

            // Show file selected info badge
            const sizeInMb = (file.size / (1024 * 1024)).toFixed(2);
            fileNameLabel.textContent = file.name + ' (' + sizeInMb + ' MB)';
            fileSelectedBadge.style.display = 'inline-block';
        } else {
            clearPhoto();
        }
    }

    function clearPhoto(e) {
        if (e) e.stopPropagation();
        photoInput.value = '';
        imagePreview.src = '';
        imagePreview.style.display = 'none';
        fileSelectedBadge.style.display = 'none';
        fileNameLabel.textContent = '';
    }

    // Modal popup helper
    function openWarningPopup(missingItems) {
        missingFieldsList.innerHTML = '';
        firstInvalidElement = null;

        missingItems.forEach(item => {
            const li = document.createElement('li');
            li.className = 'missing-item-row';
            li.innerHTML = `
                <i class="bi bi-x-circle-fill missing-item-icon"></i>
                <div>
                    <span class="missing-item-label">${item.label}:</span>
                    <span class="missing-item-reason">${item.reason}</span>
                </div>
            `;
            missingFieldsList.appendChild(li);

            // Track first missing element to focus
            if (!firstInvalidElement && item.id) {
                firstInvalidElement = document.getElementById(item.id);
            }
        });

        if (typeof validationModal.showModal === 'function') {
            validationModal.showModal();
        } else {
            validationModal.style.display = 'block';
        }
    }

    function closeWarningPopup() {
        if (typeof validationModal.close === 'function') {
            validationModal.close();
        } else {
            validationModal.style.display = 'none';
        }

        // Focus and scroll to first missing element
        if (firstInvalidElement) {
            if (firstInvalidElement.id === 'photo_evidence') {
                imageDropArea.scrollIntoView({ behavior: 'smooth', block: 'center' });
                imageDropArea.classList.add('drop-invalid');
            } else {
                firstInvalidElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstInvalidElement.focus();
            }
        }
    }

    btnDismissModal.addEventListener('click', closeWarningPopup);
    validationModal.addEventListener('click', (event) => {
        if (event.target === validationModal) {
            closeWarningPopup();
        }
    });

    // Form submission validation
    complaintForm.addEventListener('submit', function (event) {
        const missingItems = [];

        // 1. Title validation
        if (titleInput.value.trim() === '') {
            titleInput.classList.add('field-invalid');
            missingItems.push({
                id: 'title',
                label: 'Nama Barang / Fasilitas',
                reason: 'Wajib diisi dengan jelas'
            });
        } else {
            titleInput.classList.remove('field-invalid');
        }

        // 2. Category validation
        if (categorySelect.value === '') {
            categorySelect.classList.add('field-invalid');
            missingItems.push({
                id: 'category_id',
                label: 'Kategori Sarana',
                reason: 'Wajib dipilih sesuai jenis fasilitas'
            });
        } else {
            categorySelect.classList.remove('field-invalid');
        }

        // 3. Location validation
        if (locationSelect.value === '') {
            locationSelect.classList.add('field-invalid');
            missingItems.push({
                id: 'location_id',
                label: 'Lokasi / Ruangan',
                reason: 'Wajib dipilih tempat fasilitas berada'
            });
        } else {
            locationSelect.classList.remove('field-invalid');
        }

        // 4. Description validation
        const descLength = descriptionText.value.trim().length;
        if (descLength === 0) {
            descriptionText.classList.add('field-invalid');
            missingItems.push({
                id: 'description',
                label: 'Deskripsi Detail Kerusakan',
                reason: 'Wajib diisi untuk menjelaskan kendala'
            });
        } else if (descLength < 10) {
            descriptionText.classList.add('field-invalid');
            missingItems.push({
                id: 'description',
                label: 'Deskripsi Detail Kerusakan',
                reason: `Minimal 10 karakter (baru terisi ${descLength} karakter)`
            });
        } else {
            descriptionText.classList.remove('field-invalid');
        }

        // 5. Photo evidence validation
        if (!photoInput.files || photoInput.files.length === 0) {
            imageDropArea.classList.add('drop-invalid');
            missingItems.push({
                id: 'photo_evidence',
                label: 'Foto Bukti Kerusakan',
                reason: 'Wajib melampirkan foto bukti kondisi fisik'
            });
        } else {
            const photoFile = photoInput.files[0];
            if (photoFile.size > 3 * 1024 * 1024) {
                imageDropArea.classList.add('drop-invalid');
                missingItems.push({
                    id: 'photo_evidence',
                    label: 'Foto Bukti Kerusakan',
                    reason: 'Ukuran foto melebihi batas 3MB'
                });
            } else {
                imageDropArea.classList.remove('drop-invalid');
            }
        }

        // If there are any missing items, STOP SUBMISSION & SHOW POPUP WARNING
        if (missingItems.length > 0) {
            event.preventDefault();
            event.stopPropagation();
            openWarningPopup(missingItems);
            return false;
        }

        // If all valid, lock button and show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengirim Laporan...';
    });
</script>
@endsection
