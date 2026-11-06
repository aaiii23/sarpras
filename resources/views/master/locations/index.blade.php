@extends('layouts.app')

@section('title', 'Master Data Lokasi & Ruangan')
@section('page-title', 'Lokasi & Ruangan Sekolah')

@section('styles')
<style>
    .master-table {
        min-width: 800px;
    }

    .location-description {
        min-width: 220px;
        white-space: normal;
    }

    .master-actions {
        display: flex;
        justify-content: flex-end;
        gap: 6px;
        white-space: nowrap;
    }

    .location-fields {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0 14px;
    }

    /* Views Switching */
    .locations-desktop-view {
        display: block;
    }

    .locations-mobile-view {
        display: none;
    }

    /* Mobile Location Card */
    .location-mobile-card {
        background-color: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .location-mobile-card:hover {
        border-color: #d1c5be;
        box-shadow: 0 4px 10px rgba(53, 7, 19, 0.05);
    }

    .location-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid #f1f5f9;
    }

    .location-card-title {
        font-size: 15px;
        font-weight: 700;
        color: #350713;
        margin: 0;
    }

    .location-card-body {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .location-meta-pills {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .meta-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background-color: #f1f5f9;
        color: #334155;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }

    .meta-pill i {
        color: #7c4253;
    }

    .location-card-desc {
        font-size: 13px;
        color: #64748b;
        margin: 0;
        line-height: 1.45;
    }

    .location-card-actions {
        display: flex;
        gap: 8px;
        padding-top: 4px;
    }

    .location-btn-mobile {
        flex: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 9px 12px;
        font-size: 12.5px;
        font-weight: 600;
        border-radius: 8px;
    }

    @media (max-width: 850px) {
        .locations-desktop-view {
            display: none !important;
        }

        .locations-mobile-view {
            display: flex !important;
            flex-direction: column;
            gap: 12px;
            padding: 16px;
        }

        .locations-main-card {
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
        }

        .locations-main-card > .card-header {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            margin-bottom: 14px;
        }
    }

    @media (max-width: 600px) {
        .master-card-header > .btn-primary {
            width: 100%;
            justify-content: center;
        }

        .location-fields {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="card locations-main-card">
    <div class="card-header master-card-header">
        <h3><i class="bi bi-geo-alt-fill text-blue-600"></i> Daftar Lokasi &amp; Ruangan Sekolah</h3>
        <button type="button" class="btn-primary" onclick="openAddModal()">
            <i class="bi bi-plus-lg"></i> Tambah Lokasi / Ruang
        </button>
    </div>

    <!-- Desktop Table View (>= 851px) -->
    <div class="locations-desktop-view">
        <div class="table-responsive">
            <table class="table-custom master-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">No.</th>
                        <th>Nama Ruangan / Area</th>
                        <th>Gedung</th>
                        <th>Lantai</th>
                        <th>Keterangan</th>
                        <th style="text-align: center;">Jumlah Aduan</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($locations as $index => $location)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong style="color: #350713; font-size: 14px;">{{ $location->name }}</strong>
                            </td>
                            <td>
                                <span style="background-color: #f1f5f9; padding: 4px 8px; border-radius: 6px; font-weight: 600;">
                                    {{ $location->building ?? '-' }}
                                </span>
                            </td>
                            <td>{{ $location->floor ?? '-' }}</td>
                            <td class="location-description" style="color: #475569;">{{ $location->description ?? '-' }}</td>
                            <td style="text-align: center;">
                                <span class="badge" style="background-color: #f2e8e5; color: #4d1b2a;">
                                    {{ $location->complaints_count }} aduan
                                </span>
                            </td>
                            <td>
                                <div class="master-actions">
                                    <button type="button" class="btn-outline" style="padding: 5px 10px; font-size: 12px;" onclick="openEditModal({{ json_encode($location) }})">
                                        <i class="bi bi-pencil-fill"></i> Edit
                                    </button>
                                    <form action="{{ route('admin.locations.destroy', $location->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Hapus lokasi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger" style="padding: 5px 10px; font-size: 12px;">
                                            <i class="bi bi-trash-fill"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 30px; color: #64748b;">
                                Belum ada data lokasi/ruangan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Cards View (<= 850px) -->
    <div class="locations-mobile-view">
        @forelse($locations as $index => $location)
            <div class="location-mobile-card">
                <div class="location-card-header">
                    <h4 class="location-card-title">{{ $location->name }}</h4>
                    <span class="badge" style="background-color: #f2e8e5; color: #4d1b2a; font-size: 11.5px;">
                        <i class="bi bi-chat-left-dots-fill"></i> {{ $location->complaints_count }} aduan
                    </span>
                </div>

                <div class="location-card-body">
                    <div class="location-meta-pills">
                        <span class="meta-pill">
                            <i class="bi bi-building"></i> {{ $location->building ?: 'Gedung -' }}
                        </span>
                        <span class="meta-pill">
                            <i class="bi bi-layers"></i> {{ $location->floor ?: 'Lantai -' }}
                        </span>
                    </div>

                    <p class="location-card-desc">
                        {{ $location->description ?: 'Tidak ada keterangan tambahan.' }}
                    </p>
                </div>

                <div class="location-card-actions">
                    <button type="button" class="btn-outline location-btn-mobile" onclick="openEditModal({{ json_encode($location) }})">
                        <i class="bi bi-pencil-fill"></i> Edit Lokasi
                    </button>
                    <form action="{{ route('admin.locations.destroy', $location->id) }}" method="POST" style="flex: 1;" onsubmit="return confirm('Hapus lokasi ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger location-btn-mobile" style="width: 100%;">
                            <i class="bi bi-trash-fill"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div style="background-color: #ffffff; border: 1px solid var(--border-color); border-radius: 12px; padding: 36px 20px; text-align: center; color: #64748b;">
                <i class="bi bi-geo-alt" style="font-size: 36px; display: block; margin-bottom: 8px; color: #94a3b8;"></i>
                Belum ada data lokasi/ruangan.
            </div>
        @endforelse
    </div>
</div>

<!-- Modal Form (Tambah / Edit) -->
<div class="modal-backdrop" id="locationModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3 id="modalTitle" style="font-size: 16px; font-weight: 700; color: #0f172a;">Tambah Lokasi Ruangan</h3>
            <button type="button" onclick="closeModal()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #94a3b8;">&times;</button>
        </div>
        <form id="locationForm" method="POST">
            @csrf
            <div id="methodField"></div>

            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="loc_name">Nama Ruangan / Area <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="name" id="loc_name" class="form-control" placeholder="Contoh: Lab Rekayasa Perangkat Lunak 1" required>
                </div>

                <div class="location-fields">
                    <div class="form-group">
                        <label class="form-label" for="loc_building">Nama Gedung</label>
                        <input type="text" name="building" id="loc_building" class="form-control" placeholder="Contoh: Gedung RPL">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="loc_floor">Lantai</label>
                        <input type="text" name="floor" id="loc_floor" class="form-control" placeholder="Contoh: Lantai 2">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="loc_desc">Keterangan Tambahan</label>
                    <textarea name="description" id="loc_desc" class="form-control" placeholder="Kapasitas, letak lorong, atau keterangan lain..."></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-outline" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn-primary">Simpan Data</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const modal = document.getElementById('locationModal');
    const form = document.getElementById('locationForm');
    const methodField = document.getElementById('methodField');
    const modalTitle = document.getElementById('modalTitle');

    function openAddModal() {
        modalTitle.innerText = 'Tambah Lokasi / Ruangan Baru';
        form.action = "{{ route('admin.locations.store') }}";
        methodField.innerHTML = '';
        document.getElementById('loc_name').value = '';
        document.getElementById('loc_building').value = '';
        document.getElementById('loc_floor').value = '';
        document.getElementById('loc_desc').value = '';
        modal.classList.add('show');
    }

    function openEditModal(location) {
        modalTitle.innerText = 'Edit Lokasi / Ruang: ' + location.name;
        form.action = `/admin/locations/${location.id}`;
        methodField.innerHTML = `@method('PUT')`;
        document.getElementById('loc_name').value = location.name;
        document.getElementById('loc_building').value = location.building || '';
        document.getElementById('loc_floor').value = location.floor || '';
        document.getElementById('loc_desc').value = location.description || '';
        modal.classList.add('show');
    }

    function closeModal() {
        modal.classList.remove('show');
    }
</script>
@endsection
