@extends('layouts.app')

@section('title', 'Master Data Kategori Sarana')
@section('page-title', 'Kategori Sarana & Prasarana')

@section('styles')
<style>
    .master-table {
        min-width: 800px;
    }

    .category-description {
        min-width: 250px;
        white-space: normal;
    }

    .master-actions {
        display: flex;
        justify-content: flex-end;
        gap: 6px;
        white-space: nowrap;
    }

    /* Views Switching */
    .categories-desktop-view {
        display: block;
    }

    .categories-mobile-view {
        display: none;
    }

    /* Mobile Category Card */
    .category-mobile-card {
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

    .category-mobile-card:hover {
        border-color: #d1c5be;
        box-shadow: 0 4px 10px rgba(53, 7, 19, 0.05);
    }

    .category-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid #f1f5f9;
    }

    .category-card-title {
        font-size: 15px;
        font-weight: 700;
        color: #350713;
        margin: 0;
    }

    .category-icon-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background-color: #f1f5f9;
        color: #475569;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-family: monospace;
    }

    .category-card-body {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .category-card-desc {
        font-size: 13px;
        color: #475569;
        margin: 0;
        line-height: 1.45;
    }

    .category-card-actions {
        display: flex;
        gap: 8px;
        padding-top: 4px;
    }

    .category-btn-mobile {
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
        .categories-desktop-view {
            display: none !important;
        }

        .categories-mobile-view {
            display: flex !important;
            flex-direction: column;
            gap: 12px;
            padding: 16px;
        }

        .categories-main-card {
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
        }

        .categories-main-card > .card-header {
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
    }
</style>
@endsection

@section('content')
<div class="card categories-main-card">
    <div class="card-header master-card-header">
        <h3><i class="bi bi-tags-fill text-blue-600"></i> Daftar Kategori Sarana Sekolah</h3>
        <button type="button" class="btn-primary" onclick="openAddModal()">
            <i class="bi bi-plus-lg"></i> Tambah Kategori
        </button>
    </div>

    <!-- Desktop Table View (>= 851px) -->
    <div class="categories-desktop-view">
        <div class="table-responsive">
            <table class="table-custom master-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">No.</th>
                        <th>Nama Kategori</th>
                        <th>Ikon / Simbol</th>
                        <th>Deskripsi Kategori</th>
                        <th style="text-align: center;">Jumlah Aduan</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $index => $category)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong style="color: #350713; font-size: 14px;">{{ $category->name }}</strong>
                            </td>
                            <td>
                                <span style="display: inline-flex; align-items: center; gap: 6px; background-color: #f1f5f9; padding: 4px 10px; border-radius: 6px; font-size: 12.5px;">
                                    <i class="bi {{ $category->icon }}"></i> {{ $category->icon }}
                                </span>
                            </td>
                            <td class="category-description" style="color: #475569;">
                                {{ $category->description ?? '-' }}
                            </td>
                            <td style="text-align: center;">
                                <span class="badge" style="background-color: #f2e8e5; color: #4d1b2a;">
                                    {{ $category->complaints_count }} aduan
                                </span>
                            </td>
                            <td>
                                <div class="master-actions">
                                    <button type="button" class="btn-outline" style="padding: 5px 10px; font-size: 12px;" onclick="openEditModal({{ json_encode($category) }})">
                                        <i class="bi bi-pencil-fill"></i> Edit
                                    </button>
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Hapus kategori ini?')">
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
                            <td colspan="6" style="text-align: center; padding: 30px; color: #64748b;">
                                Belum ada data kategori sarana.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Cards View (<= 850px) -->
    <div class="categories-mobile-view">
        @forelse($categories as $index => $category)
            <div class="category-mobile-card">
                <div class="category-card-header">
                    <h4 class="category-card-title">{{ $category->name }}</h4>
                    <span class="category-icon-badge">
                        <i class="bi {{ $category->icon }}"></i> {{ $category->icon }}
                    </span>
                </div>

                <div class="category-card-body">
                    <p class="category-card-desc">
                        {{ $category->description ?: 'Tidak ada deskripsi cakupan sarana.' }}
                    </p>

                    <div>
                        <span class="badge" style="background-color: #f2e8e5; color: #4d1b2a; font-size: 11.5px;">
                            <i class="bi bi-chat-left-dots-fill"></i> {{ $category->complaints_count }} aduan terkait
                        </span>
                    </div>
                </div>

                <div class="category-card-actions">
                    <button type="button" class="btn-outline category-btn-mobile" onclick="openEditModal({{ json_encode($category) }})">
                        <i class="bi bi-pencil-fill"></i> Edit Kategori
                    </button>
                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="flex: 1;" onsubmit="return confirm('Hapus kategori ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger category-btn-mobile" style="width: 100%;">
                            <i class="bi bi-trash-fill"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div style="background-color: #ffffff; border: 1px solid var(--border-color); border-radius: 12px; padding: 36px 20px; text-align: center; color: #64748b;">
                <i class="bi bi-tags" style="font-size: 36px; display: block; margin-bottom: 8px; color: #94a3b8;"></i>
                Belum ada data kategori sarana.
            </div>
        @endforelse
    </div>
</div>

<!-- Modal Form (Tambah / Edit) -->
<div class="modal-backdrop" id="categoryModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3 id="modalTitle" style="font-size: 16px; font-weight: 700; color: #0f172a;">Tambah Kategori Sarana</h3>
            <button type="button" onclick="closeModal()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #94a3b8;">&times;</button>
        </div>
        <form id="categoryForm" method="POST">
            @csrf
            <div id="methodField"></div>

            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="cat_name">Nama Kategori <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="name" id="cat_name" class="form-control" placeholder="Contoh: Elektronik & Proyektor" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="cat_icon">Ikon Bootstrap (Opsional)</label>
                    <input type="text" name="icon" id="cat_icon" class="form-control" placeholder="bi-projector, bi-display, bi-droplet-half, dll">
                </div>

                <div class="form-group">
                    <label class="form-label" for="cat_desc">Deskripsi Cakupan Sarana</label>
                    <textarea name="description" id="cat_desc" class="form-control" placeholder="Contoh: LCD Proyektor, Smart TV, Sound System, dll"></textarea>
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
    const modal = document.getElementById('categoryModal');
    const form = document.getElementById('categoryForm');
    const methodField = document.getElementById('methodField');
    const modalTitle = document.getElementById('modalTitle');

    function openAddModal() {
        modalTitle.innerText = 'Tambah Kategori Sarana Baru';
        form.action = "{{ route('admin.categories.store') }}";
        methodField.innerHTML = '';
        document.getElementById('cat_name').value = '';
        document.getElementById('cat_icon').value = 'bi-box-seam';
        document.getElementById('cat_desc').value = '';
        modal.classList.add('show');
    }

    function openEditModal(category) {
        modalTitle.innerText = 'Edit Kategori Sarana: ' + category.name;
        form.action = `/admin/categories/${category.id}`;
        methodField.innerHTML = `@method('PUT')`;
        document.getElementById('cat_name').value = category.name;
        document.getElementById('cat_icon').value = category.icon || '';
        document.getElementById('cat_desc').value = category.description || '';
        modal.classList.add('show');
    }

    function closeModal() {
        modal.classList.remove('show');
    }
</script>
@endsection
