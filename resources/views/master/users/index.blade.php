@extends('layouts.app')

@section('title', 'Master Data Pengguna')
@section('page-title', 'Pengelolaan Data Pengguna')

@section('styles')
<style>
    .stats-row {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 20px;
    }

    .users-toolbar,
    .users-filters {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .users-toolbar {
        flex: 1 1 540px;
        flex-wrap: wrap;
    }

    .users-filters {
        flex: 1 1 400px;
        flex-wrap: wrap;
    }

    .users-search {
        flex: 1 1 220px;
        min-width: 160px;
    }

    .users-role {
        flex: 0 1 160px;
        min-width: 135px;
    }

    .users-table {
        min-width: 900px;
    }

    .users-table .user-email {
        min-width: 190px;
    }

    .user-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0 12px;
    }

    /* Desktop View vs Mobile Cards View */
    .users-desktop-view {
        display: block;
    }

    .users-mobile-view {
        display: none;
    }

    /* Mobile User Card Styling */
    .user-mobile-card {
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

    .user-mobile-card:hover {
        border-color: #d1c5be;
        box-shadow: 0 4px 10px rgba(53, 7, 19, 0.05);
    }

    .user-card-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid #f1f5f9;
    }

    .user-identity {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
    }

    .user-avatar-circle {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }

    .user-avatar-circle.role-admin {
        background-color: #fef3c7;
        color: #b45309;
    }

    .user-avatar-circle.role-guru {
        background-color: #e0e7ff;
        color: #4338ca;
    }

    .user-avatar-circle.role-siswa {
        background-color: #dcfce7;
        color: #15803d;
    }

    .user-card-name {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .user-card-name strong {
        font-size: 14.5px;
        color: #0f172a;
    }

    .user-card-email {
        font-size: 12px;
        color: #64748b;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 190px;
    }

    .user-badge-self {
        font-size: 10px;
        background-color: #eadde0;
        color: #4d1b2a;
        padding: 2px 6px;
        border-radius: 4px;
        font-weight: 700;
    }

    .user-card-meta {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px 12px;
        background-color: #f8fafc;
        border: 1px solid #f1f5f9;
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 12px;
    }

    .meta-item {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .meta-item .meta-label {
        font-size: 11px;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .meta-item .meta-label i {
        color: #7c4253;
    }

    .meta-item .meta-val {
        font-size: 12.5px;
        font-weight: 600;
        color: #1e293b;
        word-break: break-all;
    }

    .user-card-actions {
        display: flex;
        gap: 8px;
        padding-top: 4px;
    }

    .user-btn-mobile {
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
        .users-desktop-view {
            display: none !important;
        }

        .users-mobile-view {
            display: flex !important;
            flex-direction: column;
            gap: 12px;
            padding: 16px;
        }

        .users-main-card {
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
        }

        .users-main-card > .card-header {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            margin-bottom: 14px;
        }

        .users-toolbar {
            width: 100%;
        }

        .users-filters {
            width: 100%;
        }

        .stats-row {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 600px) {
        .stats-row,
        .user-form-grid {
            grid-template-columns: 1fr;
        }

        .users-filters,
        .users-filters .form-control,
        .users-toolbar > .btn-primary {
            width: 100%;
        }

        .users-filters .btn-primary {
            flex: 1 0 100%;
            justify-content: center;
        }

        .card-header > .btn-primary {
            width: 100%;
            justify-content: center;
        }

        .user-card-meta {
            grid-template-columns: 1fr;
        }

        .user-card-email {
            max-width: 160px;
        }
    }

    .stat-box {
        background-color: #ffffff;
        border-radius: 10px;
        padding: 16px;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 12px;
    }
</style>
@endsection

@section('content')
<!-- Stats Row -->
<div class="stats-row" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
    <div class="stat-box">
        <div style="width: 40px; height: 40px; border-radius: 8px; background-color: #f2e8e5; color: #5c1a2a; display: flex; align-items: center; justify-content: center; font-size: 18px;">
            <i class="bi bi-people-fill"></i>
        </div>
        <div>
            <h3 style="font-size: 18px; font-weight: 800;">{{ $stats['total'] }}</h3>
            <p style="font-size: 12px; color: #64748b;">Total Semua Pengguna</p>
        </div>
    </div>

    <div class="stat-box">
        <div style="width: 40px; height: 40px; border-radius: 8px; background-color: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 18px;">
            <i class="bi bi-shield-lock-fill"></i>
        </div>
        <div>
            <h3 style="font-size: 18px; font-weight: 800;">{{ $stats['admin'] }}</h3>
            <p style="font-size: 12px; color: #64748b;">Petugas / Admin Sarpras</p>
        </div>
    </div>

    <div class="stat-box">
        <div style="width: 40px; height: 40px; border-radius: 8px; background-color: #e0e7ff; color: #4338ca; display: flex; align-items: center; justify-content: center; font-size: 18px;">
            <i class="bi bi-person-workspace"></i>
        </div>
        <div>
            <h3 style="font-size: 18px; font-weight: 800;">{{ $stats['guru'] }}</h3>
            <p style="font-size: 12px; color: #64748b;">Guru</p>
        </div>
    </div>

    <div class="stat-box">
        <div style="width: 40px; height: 40px; border-radius: 8px; background-color: #dcfce7; color: #166534; display: flex; align-items: center; justify-content: center; font-size: 18px;">
            <i class="bi bi-mortarboard-fill"></i>
        </div>
        <div>
            <h3 style="font-size: 18px; font-weight: 800;">{{ $stats['siswa'] }}</h3>
            <p style="font-size: 12px; color: #64748b;">Siswa / Warga Sekolah</p>
        </div>
    </div>
</div>

<div class="card users-main-card">
    <div class="card-header">
        <form action="{{ route('admin.users.index') }}" method="GET" class="users-toolbar">
            <div class="users-filters">
                <input type="text" name="search" class="form-control users-search" style="height: 40px; font-size: 13px;" placeholder="Cari nama, email, NIS / NIP..." value="{{ request('search') }}">
                <select name="role" class="form-control users-role" style="height: 40px; font-size: 13px;" onchange="this.form.submit()">
                    <option value="">-- Semua Role --</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="guru" {{ request('role') === 'guru' ? 'selected' : '' }}>Guru</option>
                    <option value="siswa" {{ request('role') === 'siswa' ? 'selected' : '' }}>Siswa</option>
                </select>
                <button type="submit" class="btn-primary" style="height: 40px; padding: 0 14px;" aria-label="Cari pengguna"><i class="bi bi-search"></i></button>
                @if(request()->hasAny(['search', 'role']))
                    <a href="{{ route('admin.users.index') }}" class="btn-outline" style="height: 40px; padding: 8px 12px;" title="Reset Filter">
                        <i class="bi bi-x"></i>
                    </a>
                @endif
            </div>
        </form>

        <button type="button" class="btn-primary" onclick="openAddModal()">
            <i class="bi bi-person-plus-fill"></i> Tambah Pengguna
        </button>
    </div>

    <!-- Desktop Table View (>= 851px) -->
    <div class="users-desktop-view">
        <div class="table-responsive">
            <table class="table-custom users-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No.</th>
                        <th>Nama Pengguna</th>
                        <th>Alamat Email</th>
                        <th>Role Akses</th>
                        <th>NIS / NIP</th>
                        <th>No. HP / WA</th>
                        <th>Jumlah Aduan</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                        <tr>
                            <td>{{ $users->firstItem() + $index }}</td>
                            <td>
                                <strong>{{ $user->name }}</strong>
                                @if($user->id === auth()->id())
                                    <span class="user-badge-self">(Anda)</span>
                                @endif
                            </td>
                            <td class="user-email" style="color: #475569;">{{ $user->email }}</td>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="badge" style="background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a;">
                                        <i class="bi bi-shield-fill"></i> Admin
                                    </span>
                                @elseif($user->role === 'guru')
                                    <span class="badge" style="background-color: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe;">
                                        <i class="bi bi-person-workspace"></i> Guru
                                    </span>
                                @else
                                    <span class="badge" style="background-color: #dcfce7; color: #166534; border: 1px solid #bbf7d0;">
                                        <i class="bi bi-mortarboard-fill"></i> Siswa
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($user->nis)
                                    <strong style="color: #1e293b; font-family: monospace;">{{ $user->nis }}</strong>
                                    <div style="font-size: 11px; color: #64748b;">
                                        {{ $user->role === 'guru' ? 'NIP Guru' : ($user->role === 'siswa' ? 'NIS Siswa' : 'Identitas') }}
                                    </div>
                                @else
                                    <span style="color: #94a3b8;">-</span>
                                @endif
                            </td>
                            <td>{{ $user->phone ?? '-' }}</td>
                            <td>
                                <span class="badge" style="background-color: #f1f5f9; color: #334155;">
                                    {{ $user->complaints_count }} aduan
                                </span>
                            </td>
                            <td style="text-align: right; white-space: nowrap;">
                                <button type="button" class="btn-outline" style="padding: 5px 10px; font-size: 12px;" onclick="openEditModal({{ json_encode($user) }})">
                                    <i class="bi bi-pencil-fill"></i> Edit
                                </button>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Hapus pengguna ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger" style="padding: 5px 10px; font-size: 12px;">
                                            <i class="bi bi-trash-fill"></i> Hapus
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 30px; color: #64748b;">
                                Tidak ditemukan data pengguna.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Cards View (<= 850px) -->
    <div class="users-mobile-view">
        @forelse($users as $index => $user)
            <div class="user-mobile-card">
                <div class="user-card-header">
                    <div class="user-identity">
                        <div class="user-avatar-circle role-{{ $user->role }}">
                            <i class="bi {{ $user->role === 'admin' ? 'bi-shield-fill' : ($user->role === 'guru' ? 'bi-person-workspace' : 'bi-mortarboard-fill') }}"></i>
                        </div>
                        <div>
                            <div class="user-card-name">
                                <strong>{{ $user->name }}</strong>
                                @if($user->id === auth()->id())
                                    <span class="user-badge-self">(Anda)</span>
                                @endif
                            </div>
                            <div class="user-card-email">{{ $user->email }}</div>
                        </div>
                    </div>
                    <div>
                        @if($user->role === 'admin')
                            <span class="badge" style="background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; font-size: 11px;">
                                Admin
                            </span>
                        @elseif($user->role === 'guru')
                            <span class="badge" style="background-color: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe; font-size: 11px;">
                                Guru
                            </span>
                        @else
                            <span class="badge" style="background-color: #dcfce7; color: #166534; border: 1px solid #bbf7d0; font-size: 11px;">
                                Siswa
                            </span>
                        @endif
                    </div>
                </div>

                <div class="user-card-meta">
                    <div class="meta-item">
                        <span class="meta-label">
                            <i class="bi bi-card-text"></i> {{ $user->role === 'guru' ? 'NIP Guru' : ($user->role === 'siswa' ? 'NIS Siswa' : 'NIS / NIP') }}
                        </span>
                        <span class="meta-val font-mono">{{ $user->nis ?? '-' }}</span>
                    </div>

                    <div class="meta-item">
                        <span class="meta-label">
                            <i class="bi bi-telephone"></i> No. HP / WA
                        </span>
                        <span class="meta-val">{{ $user->phone ?? '-' }}</span>
                    </div>

                    <div class="meta-item" style="grid-column: 1 / -1;">
                        <span class="meta-label">
                            <i class="bi bi-chat-left-text"></i> Riwayat Aduan Sarana
                        </span>
                        <span class="meta-val">
                            <span class="badge" style="background-color: #f1f5f9; color: #334155; font-size: 11px;">
                                {{ $user->complaints_count }} laporan aduan
                            </span>
                        </span>
                    </div>
                </div>

                <div class="user-card-actions">
                    <button type="button" class="btn-outline user-btn-mobile" onclick="openEditModal({{ json_encode($user) }})">
                        <i class="bi bi-pencil-fill"></i> Edit
                    </button>
                    @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="flex: 1;" onsubmit="return confirm('Hapus pengguna ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger user-btn-mobile" style="width: 100%;">
                                <i class="bi bi-trash-fill"></i> Hapus
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div style="background-color: #ffffff; border: 1px solid var(--border-color); border-radius: 12px; padding: 36px 20px; text-align: center; color: #64748b;">
                <i class="bi bi-people" style="font-size: 36px; display: block; margin-bottom: 8px; color: #94a3b8;"></i>
                Tidak ditemukan data pengguna yang cocok.
            </div>
        @endforelse
    </div>

    <div style="margin-top: 18px; padding: 0 4px;">
        {{ $users->links() }}
    </div>
</div>

<!-- Modal Form User -->
<div class="modal-backdrop" id="userModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3 id="modalTitle" style="font-size: 16px; font-weight: 700; color: #0f172a;">Tambah Pengguna</h3>
            <button type="button" onclick="closeModal()" style="background: none; border: none; font-size: 20px; cursor: pointer; color: #94a3b8;">&times;</button>
        </div>
        <form id="userForm" method="POST">
            @csrf
            <div id="methodField"></div>

            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label" for="u_name">Nama Lengkap <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="name" id="u_name" class="form-control" required>
                </div>

                <div class="user-form-grid">
                    <div class="form-group">
                        <label class="form-label" for="u_email">Email <span style="color: #ef4444;">*</span></label>
                        <input type="email" name="email" id="u_email" class="form-control" pattern="[^@\s]+@gmail\.com" title="Gunakan alamat @gmail.com" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="u_role">Role Akses <span style="color: #ef4444;">*</span></label>
                        <select name="role" id="u_role" class="form-control" required onchange="handleModalRoleChange(this.value)">
                            <option value="siswa">Siswa</option>
                            <option value="guru">Guru</option>
                            <option value="admin">Admin / Petugas</option>
                        </select>
                    </div>
                </div>

                <div class="user-form-grid">
                    <div class="form-group">
                        <label class="form-label" for="u_nis" id="u_nis_label">NIS / NIP</label>
                        <input type="text" name="nis" id="u_nis" class="form-control" placeholder="Nomor NIS atau NIP">
                        <small id="u_nis_hint" style="color: #64748b; font-size: 11px;">Nomor identitas pengguna.</small>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="u_phone">No. HP / WA</label>
                        <input type="text" name="phone" id="u_phone" class="form-control" placeholder="08xxxxxxxxxx">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="u_password">Kata Sandi <span id="passReq" style="color: #ef4444;">*</span></label>
                    <input type="password" name="password" id="u_password" class="form-control" placeholder="Minimal 6 karakter">
                    <small id="passHint" style="color: #64748b; font-size: 11px; display: none;">Kosongkan jika tidak ingin mengubah password.</small>
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
    const modal = document.getElementById('userModal');
    const form = document.getElementById('userForm');
    const methodField = document.getElementById('methodField');
    const modalTitle = document.getElementById('modalTitle');
    const passReq = document.getElementById('passReq');
    const passHint = document.getElementById('passHint');
    const passInput = document.getElementById('u_password');
    const nisLabel = document.getElementById('u_nis_label');
    const nisInput = document.getElementById('u_nis');
    const nisHint = document.getElementById('u_nis_hint');

    function handleModalRoleChange(role) {
        if (role === 'guru') {
            nisLabel.innerHTML = 'NIP (Nomor Induk Pegawai Guru)';
            nisInput.placeholder = 'Contoh: 198501012010011001';
            nisHint.textContent = 'Nomor Induk Pegawai untuk Guru (Opsional).';
        } else if (role === 'siswa') {
            nisLabel.innerHTML = 'NIS (Nomor Induk Siswa)';
            nisInput.placeholder = 'Contoh: 12345678';
            nisHint.textContent = 'Nomor Induk Siswa.';
        } else {
            nisLabel.innerHTML = 'NIS / NIP (Opsional)';
            nisInput.placeholder = 'Nomor identitas (opsional)';
            nisHint.textContent = 'Nomor identitas untuk Petugas / Admin.';
        }
    }

    function openAddModal() {
        modalTitle.innerText = 'Tambah Pengguna Baru';
        form.action = "{{ route('admin.users.store') }}";
        methodField.innerHTML = '';
        document.getElementById('u_name').value = '';
        document.getElementById('u_email').value = '';
        document.getElementById('u_role').value = 'siswa';
        handleModalRoleChange('siswa');
        document.getElementById('u_nis').value = '';
        document.getElementById('u_phone').value = '';
        passInput.value = '';
        passInput.required = true;
        passReq.style.display = 'inline';
        passHint.style.display = 'none';
        modal.classList.add('show');
    }

    function openEditModal(user) {
        modalTitle.innerText = 'Edit Pengguna: ' + user.name;
        form.action = `/admin/users/${user.id}`;
        methodField.innerHTML = `@method('PUT')`;
        document.getElementById('u_name').value = user.name;
        document.getElementById('u_email').value = user.email;
        document.getElementById('u_role').value = user.role;
        handleModalRoleChange(user.role);
        document.getElementById('u_nis').value = user.nis || '';
        document.getElementById('u_phone').value = user.phone || '';
        passInput.value = '';
        passInput.required = false;
        passReq.style.display = 'none';
        passHint.style.display = 'block';
        modal.classList.add('show');
    }

    function closeModal() {
        modal.classList.remove('show');
    }
</script>
@endsection
