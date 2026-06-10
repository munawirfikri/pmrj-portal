@extends('layouts.admin')

@section('title', 'Detail Profil Anggota')
@section('subtitle', 'Periksa kelengkapan data NIK dan dokumen KTP anggota.')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('admin.anggota.index') }}" class="btn-action btn-view">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
    </a>
</div>

<div class="profile-grid">
    <!-- Left Column: Avatar & KTP -->
    <div>
        <div class="profile-card-left" style="margin-bottom: 1.5rem;">
            <div class="profile-avatar">
                @if($anggota->foto)
                    <img src="{{ rtrim(config('services.anggota.url'), '/') }}/storage/app/public/photos/{{ basename($anggota->foto) }}" alt="Foto Profil">
                @else
                    <i class="fa-solid fa-user"></i>
                @endif
            </div>
            <h2 style="font-size: 1.4rem; font-weight: 700; margin-bottom: 0.25rem;">{{ $anggota->nama_lengkap }}</h2>
            <p style="color: var(--text-secondary); font-family: monospace; font-size: 0.95rem; margin-bottom: 1rem;">
                {{ $anggota->no_anggota ?: 'BELUM MEMILIKI NO. ANGGOTA' }}
            </p>
            
            <div style="margin-top: 0.75rem; display: flex; justify-content: center; gap: 0.5rem; flex-wrap: wrap;">
                <span class="badge {{ $anggota->status }}" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">
                    {{ strtoupper($anggota->status) }}
                </span>
                @if($anggota->is_verified)
                    <span class="badge active" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; background: rgba(16, 185, 129, 0.1); color: #059669; border: 1px solid rgba(16, 185, 129, 0.2);">
                        <i class="fa-solid fa-circle-check" style="margin-right: 4px;"></i> VERIFIED
                    </span>
                @else
                    <span class="badge pending" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; background: rgba(245, 158, 11, 0.1); color: #d97706; border: 1px solid rgba(245, 158, 11, 0.2);">
                        <i class="fa-solid fa-clock" style="margin-right: 4px;"></i> UNVERIFIED
                    </span>
                @endif
            </div>
        </div>
        
        <div class="profile-card-left">
            <h3 style="font-size: 1.1rem; font-weight: 600; text-align: left; margin-bottom: 1rem; color: var(--primary); border-bottom: 1px solid var(--card-border); padding-bottom: 0.5rem;">
                <i class="fa-solid fa-address-card"></i> Scan KTP
            </h3>
            
            @if($anggota->foto_ktp)
                <div class="ktp-preview-container">
                    @php
                        $ktpFilename = basename($anggota->foto_ktp);
                    @endphp
                    <a href="{{ rtrim(config('services.anggota.url'), '/') }}/storage/app/public/ktp/{{ $ktpFilename }}" target="_blank">
                        <img src="{{ rtrim(config('services.anggota.url'), '/') }}/storage/app/public/ktp/{{ $ktpFilename }}" alt="Scan KTP" class="ktp-preview" title="Klik untuk perbesar">
                    </a>
                </div>
                <p style="color: var(--text-secondary); font-size: 0.8rem; margin-top: 0.5rem; text-align: center;">
                    Klik gambar untuk melihat ukuran penuh.
                </p>
            @else
                <div style="background: #f8fafc; padding: 2rem; border-radius: 12px; color: var(--text-secondary); text-align: center; border: 1px dashed var(--card-border);">
                    <i class="fa-solid fa-image-slash" style="font-size: 2rem; margin-bottom: 0.75rem;"></i>
                    <p>Foto KTP Belum Diunggah</p>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Right Column: Detail Table & Actions -->
    <div>
        <div class="card-premium" style="margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 1.5rem; color: var(--primary); border-left: 3px solid var(--primary); padding-left: 0.75rem;">
                Data Lengkap Anggota
            </h3>
            
            <table class="detail-table">
                <tr>
                    <td class="detail-label">Nama Lengkap</td>
                    <td style="font-weight: 600; color: var(--text-primary);">{{ $anggota->nama_lengkap }}</td>
                </tr>
                <tr>
                    <td class="detail-label">Email</td>
                    <td>{{ $anggota->email ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="detail-label">Hak Akses (Role)</td>
                    <td>
                        @if($anggota->role === 'admin')
                            <span class="badge" style="background: rgba(57, 133, 195, 0.1); color: #3985c3; border: 1px solid rgba(57, 133, 195, 0.2); font-weight: 600; padding: 0.2rem 0.6rem; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="fa-solid fa-user-shield"></i> ADMINISTRATOR
                            </span>
                        @else
                            <span class="badge" style="background: rgba(100, 116, 139, 0.1); color: #64748b; border: 1px solid rgba(100, 116, 139, 0.2); font-weight: 600; padding: 0.2rem 0.6rem; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="fa-solid fa-user"></i> ANGGOTA BIASA
                            </span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="detail-label">No. HP / Telepon</td>
                    <td>{{ $anggota->no_hp ?: $anggota->no_telepon ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="detail-label">NIK (Nomor Induk Kependudukan)</td>
                    <td style="font-family: monospace; font-size: 1rem; color: var(--text-primary);">{{ $anggota->nik ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="detail-label">Asal IKK</td>
                    <td style="font-weight: 600; color: var(--primary);">{{ $anggota->asal_ikk }}</td>
                </tr>
                <tr>
                    <td class="detail-label">Tempat / Tanggal Lahir</td>
                    <td>{{ $anggota->tempat_lahir ?: '-' }}, {{ $anggota->tanggal_lahir ? $anggota->tanggal_lahir->format('d M Y') : '-' }}</td>
                </tr>
                <tr>
                    <td class="detail-label">Jenis Kelamin</td>
                    <td>{{ $anggota->jenis_kelamin }}</td>
                </tr>
                <tr>
                    <td class="detail-label">Golongan Darah</td>
                    <td><span style="font-weight: 600; background: rgba(255,255,255,0.05); padding: 0.2rem 0.5rem; border-radius: 4px;">{{ $anggota->golongan_darah ?: '-' }}</span></td>
                </tr>
                <tr>
                    <td class="detail-label">Pekerjaan</td>
                    <td>{{ $anggota->pekerjaan ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="detail-label">Status Rumah</td>
                    <td>{{ $anggota->status_rumah ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="detail-label">Kota / Wilayah Domisili</td>
                    <td>{{ $anggota->kota_bagian ?: '-' }}</td>
                </tr>
                <tr>
                    <td class="detail-label">Alamat Lengkap Jakarta</td>
                    <td style="line-height: 1.5; font-size: 0.95rem;">{{ $anggota->alamat_jakarta ?: $anggota->alamat ?: '-' }}</td>
                </tr>
            </table>
        </div>
        
        <!-- Actions Card -->
        <div class="card-premium">
            <h3 style="font-size: 1.15rem; font-weight: 700; margin-bottom: 1.5rem; color: var(--primary); border-left: 3px solid var(--primary); padding-left: 0.75rem;">
                Tindakan Administrasi
            </h3>
            
            <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                <!-- Edit Profil Button -->
                <a href="{{ route('admin.anggota.edit', $anggota->id) }}" class="btn-action btn-add" style="flex: 1; display: inline-flex; align-items: center; justify-content: center; padding: 0.75rem 1rem; border-radius: 8px; font-size: 0.9rem;">
                    <i class="fa-solid fa-user-pen"></i> Edit Profil
                </a>
                
                <!-- Verify KTP Button -->
                <form action="{{ route('admin.anggota.verify_ktp', $anggota->id) }}" method="POST" style="flex: 1; min-width: 180px;">
                    @csrf
                    <button type="submit" class="btn-action btn-view" style="width: 100%; display: inline-flex; align-items: center; justify-content: center; padding: 0.75rem 1rem; border-radius: 8px; font-size: 0.9rem; border-color: var(--primary); color: var(--primary); background: transparent;">
                        @if($anggota->is_verified)
                            <i class="fa-solid fa-user-slash"></i> Batalkan Verifikasi
                        @else
                            <i class="fa-solid fa-user-shield"></i> Verifikasi KTP
                        @endif
                    </button>
                </form>
                
                <!-- Suspend / Activate Button -->
                <form action="{{ route('admin.anggota.suspend', $anggota->id) }}" method="POST" style="flex: 1; min-width: 180px;">
                    @csrf
                    @if($anggota->status === 'active')
                        <button type="submit" class="btn-action btn-reject" style="width: 100%; display: inline-flex; align-items: center; justify-content: center; padding: 0.75rem 1rem; border-radius: 8px; font-size: 0.9rem;" onclick="return confirm('Apakah Anda yakin ingin menangguhkan keanggotaan ini?')">
                            <i class="fa-solid fa-user-xmark"></i> Tangguhkan Akun
                        </button>
                    @else
                        <button type="submit" class="btn-action btn-view" style="width: 100%; display: inline-flex; align-items: center; justify-content: center; padding: 0.75rem 1rem; border-radius: 8px; font-size: 0.9rem; border-color: var(--success); color: #059669; background: rgba(16, 185, 129, 0.05);" onclick="return confirm('Apakah Anda yakin ingin mengaktifkan kembali keanggotaan ini?')">
                            <i class="fa-solid fa-user-check"></i> Aktifkan Kembali
                        </button>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
