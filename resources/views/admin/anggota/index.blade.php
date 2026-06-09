@extends('layouts.admin')

@section('title', 'Verifikasi & Manajemen Anggota')
@section('subtitle', 'Cari, filter, dan verifikasi pendaftaran anggota baru PMRJ.')

@section('content')
<div class="card-premium">
    <div class="card-title-container">
        <h2 class="card-title"><i class="fa-solid fa-users" style="color: var(--primary); margin-right: 8px;"></i>Daftar Anggota</h2>
    </div>
    
    <!-- Filter and Search Bar -->
    <form action="{{ route('admin.anggota.index') }}" method="GET" class="filter-bar">
        <input type="text" name="search" class="filter-input" placeholder="Cari nama, NIK, email..." value="{{ request('search') }}">
        
        <select name="status" class="filter-input" style="cursor: pointer;">
            <option value="">-- Semua Status --</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        
        <select name="ikk" class="filter-input" style="cursor: pointer;">
            <option value="">-- Semua IKK --</option>
            @foreach($ikkList as $ikk)
                <option value="{{ $ikk->nama }}" {{ request('ikk') === $ikk->nama ? 'selected' : '' }}>{{ $ikk->nama }}</option>
            @endforeach
        </select>
        
        <button type="submit" class="btn-action btn-add" style="border-radius: 8px; cursor: pointer;">
            <i class="fa-solid fa-filter"></i> Filter
        </button>
        
        <a href="{{ route('admin.anggota.export', request()->query()) }}" class="btn-action btn-view" style="border-radius: 8px; border-color: var(--primary); color: var(--primary); display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem;" title="Unduh data dalam format CSV">
            <i class="fa-solid fa-file-excel"></i> Ekspor CSV
        </a>
        
        @if(request()->anyFilled(['search', 'status', 'ikk']))
            <a href="{{ route('admin.anggota.index') }}" class="btn-action btn-view" style="border-radius: 8px; display: inline-flex; align-items: center; justify-content: center;">
                <i class="fa-solid fa-xmark"></i> Reset
            </a>
        @endif
    </form>
    
    <!-- Table -->
    <div class="table-responsive">
        <table class="table-premium">
            <thead>
                <tr>
                    <th>No. Anggota</th>
                    <th>Nama Lengkap</th>
                    <th>NIK</th>
                    <th>Asal IKK</th>
                    <th>Kota / Wilayah</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($anggotaList as $anggota)
                <tr>
                    <td style="font-family: monospace; font-size: 0.9rem; font-weight: 600; color: var(--primary);">
                        {{ $anggota->no_anggota ?: 'BELUM TERBIT' }}
                    </td>
                    <td style="font-weight: 600; color: var(--text-primary);">
                        {{ $anggota->nama_lengkap }}
                        @if($anggota->is_verified)
                            <i class="fa-solid fa-circle-check" style="color: #059669; margin-left: 4px; font-size: 0.85rem;" title="Terverifikasi KTP"></i>
                        @endif
                    </td>
                    <td>{{ $anggota->nik ?: '-' }}</td>
                    <td>{{ $anggota->asal_ikk }}</td>
                    <td>{{ $anggota->kota_bagian ?: '-' }}</td>
                    <td>
                        <span class="badge {{ $anggota->status }}">
                            {{ $anggota->status }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.anggota.show', $anggota->id) }}" class="btn-action btn-view" title="Periksa / Detail">
                            <i class="fa-solid fa-magnifying-glass"></i> Periksa
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-secondary); padding: 3rem;">
                        Tidak ditemukan data anggota yang cocok dengan pencarian/filter.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="pagination-wrapper">
        {{ $anggotaList->withQueryString()->links() }}
    </div>
</div>
@endsection
