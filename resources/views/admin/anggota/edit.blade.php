@extends('layouts.admin')

@section('title', 'Ubah Profil Anggota')
@section('subtitle', 'Edit data diri anggota ' . $anggota->nama_lengkap . '.')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('admin.anggota.show', $anggota->id) }}" class="btn-action btn-view">
        <i class="fa-solid fa-arrow-left"></i> Batal & Kembali
    </a>
</div>

<div class="card-premium" style="max-width: 800px; margin: 0 auto;">
    <h2 class="card-title" style="margin-bottom: 1.75rem; color: var(--primary); border-left: 3px solid var(--primary); padding-left: 0.75rem;">
        <i class="fa-solid fa-user-pen" style="margin-right: 8px;"></i> Formulir Ubah Data Anggota
    </h2>
    
    <form action="{{ route('admin.anggota.update', $anggota->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-grid">
            <!-- Left Grid Column -->
            <div>
                <div class="form-group">
                    <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap', $anggota->nama_lengkap) }}" required>
                    @error('nama_lengkap')
                        <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $anggota->email) }}">
                    @error('email')
                        <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="no_hp" class="form-label">Nomor HP</label>
                    <input type="text" id="no_hp" name="no_hp" class="form-control" value="{{ old('no_hp', $anggota->no_hp ?: $anggota->no_telepon) }}">
                    @error('no_hp')
                        <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="nik" class="form-label">NIK (Nomor Induk Kependudukan)</label>
                    <input type="text" id="nik" name="nik" class="form-control" value="{{ old('nik', $anggota->nik) }}" maxlength="16">
                    @error('nik')
                        <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="asal_ikk" class="form-label">Asal IKK</label>
                    <select id="asal_ikk" name="asal_ikk" class="form-control" required style="cursor: pointer;">
                        @foreach($ikkList as $ikk)
                            <option value="{{ $ikk->nama }}" {{ old('asal_ikk', $anggota->asal_ikk) === $ikk->nama ? 'selected' : '' }}>{{ $ikk->nama }}</option>
                        @endforeach
                    </select>
                    @error('asal_ikk')
                        <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                    <select id="jenis_kelamin" name="jenis_kelamin" class="form-control" style="cursor: pointer;">
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="Laki-laki" {{ old('jenis_kelamin', $anggota->jenis_kelamin) === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin', $anggota->jenis_kelamin) === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="role" class="form-label">Hak Akses (Role)</label>
                    <select id="role" name="role" class="form-control" style="cursor: pointer;" required>
                        <option value="member" {{ old('role', $anggota->role) === 'member' ? 'selected' : '' }}>Anggota Biasa (Member)</option>
                        <option value="admin" {{ old('role', $anggota->role) === 'admin' ? 'selected' : '' }}>Administrator (Admin)</option>
                    </select>
                    @error('role')
                        <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <!-- Right Grid Column -->
            <div>
                <div class="form-group form-grid-half">
                    <div>
                        <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                        <input type="text" id="tempat_lahir" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $anggota->tempat_lahir) }}">
                        @error('tempat_lahir')
                            <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $anggota->tanggal_lahir ? $anggota->tanggal_lahir->format('Y-m-d') : '') }}">
                        @error('tanggal_lahir')
                            <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="golongan_darah" class="form-label">Golongan Darah</label>
                    <select id="golongan_darah" name="golongan_darah" class="form-control" style="cursor: pointer;">
                        <option value="">-- Pilih Golongan Darah --</option>
                        @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $gol)
                            <option value="{{ $gol }}" {{ old('golongan_darah', $anggota->golongan_darah) === $gol ? 'selected' : '' }}>{{ $gol }}</option>
                        @endforeach
                    </select>
                    @error('golongan_darah')
                        <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="pekerjaan" class="form-label">Pekerjaan</label>
                    <input type="text" id="pekerjaan" name="pekerjaan" class="form-control" value="{{ old('pekerjaan', $anggota->pekerjaan) }}">
                    @error('pekerjaan')
                        <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group form-grid-half">
                    <div>
                        <label for="status_rumah" class="form-label">Status Kepemilikan Rumah</label>
                        <select id="status_rumah" name="status_rumah" class="form-control" style="cursor: pointer;">
                            <option value="">-- Pilih --</option>
                            <option value="Rumah Tetap" {{ old('status_rumah', $anggota->status_rumah) === 'Rumah Tetap' ? 'selected' : '' }}>Rumah Tetap</option>
                            <option value="Rumah Kontrak" {{ old('status_rumah', $anggota->status_rumah) === 'Rumah Kontrak' ? 'selected' : '' }}>Rumah Kontrak</option>
                        </select>
                        @error('status_rumah')
                            <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label for="kota_bagian" class="form-label">Wilayah Domisili</label>
                        <select id="kota_bagian" name="kota_bagian" class="form-control" style="cursor: pointer;">
                            <option value="">-- Pilih --</option>
                            @foreach(['Jakarta Utara', 'Jakarta Selatan', 'Jakarta Barat', 'Jakarta Timur', 'Jakarta Pusat', 'Kota Tangerang', 'Kabupaten Tangerang', 'Tangerang Selatan', 'Depok', 'Bekasi', 'Bogor'] as $kota)
                                <option value="{{ $kota }}" {{ old('kota_bagian', $anggota->kota_bagian) === $kota ? 'selected' : '' }}>{{ $kota }}</option>
                            @endforeach
                        </select>
                        @error('kota_bagian')
                            <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="alamat_jakarta" class="form-label">Alamat Lengkap Jakarta</label>
                    <textarea id="alamat_jakarta" name="alamat_jakarta" class="form-control" rows="3" placeholder="Masukkan alamat lengkap tinggal di Jakarta..." style="resize: vertical;">{{ old('alamat_jakarta', $anggota->alamat_jakarta ?: $anggota->alamat) }}</textarea>
                    @error('alamat_jakarta')
                        <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        
        <div class="form-actions">
            <a href="{{ route('admin.anggota.show', $anggota->id) }}" class="btn-action btn-view" style="padding: 0.75rem 1.5rem; border-radius: 8px;">
                Batal
            </a>
            <button type="submit" class="btn-action btn-add" style="padding: 0.75rem 2rem; border-radius: 8px; font-weight: 600; cursor: pointer;">
                <i class="fa-solid fa-cloud-arrow-up" style="margin-right: 6px;"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
