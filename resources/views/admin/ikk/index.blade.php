@extends('layouts.admin')

@section('title', 'Data Master IKK')
@section('subtitle', 'Kelola data Ikatan Keluarga Kabupaten/Kota (IKK) PMRJ.')

@section('content')
<div class="card-premium">
    <div class="card-title-container">
        <h2 class="card-title"><i class="fa-solid fa-folder-tree" style="color: var(--primary); margin-right: 8px;"></i>Daftar IKK</h2>
        <a href="{{ route('admin.ikk.create') }}" class="btn-action btn-add">
            <i class="fa-solid fa-plus"></i> Tambah IKK Baru
        </a>
    </div>
    
    <div class="table-responsive">
        <table class="table-premium">
            <thead>
                <tr>
                    <th style="width: 100px;">ID</th>
                    <th style="width: 150px;">Kode IKK</th>
                    <th>Nama IKK</th>
                    <th>Tanggal Ditambahkan</th>
                    <th style="width: 200px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ikkList as $ikk)
                <tr>
                    <td>{{ $ikk->id }}</td>
                    <td style="font-family: monospace; font-size: 1rem; font-weight: 700; color: var(--primary);">{{ $ikk->kode }}</td>
                    <td style="font-weight: 600; color: var(--text-primary);">{{ $ikk->nama }}</td>
                    <td>{{ $ikk->created_at ? $ikk->created_at->format('d M Y') : '-' }}</td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('admin.ikk.edit', $ikk->id) }}" class="btn-action btn-view" style="border-color: rgba(57, 133, 195, 0.3); color: var(--primary);" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </a>
                            <form action="{{ route('admin.ikk.destroy', $ikk->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus IKK ini? Anggota yang menggunakan IKK ini mungkin akan terpengaruh.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-reject" title="Hapus">
                                    <i class="fa-solid fa-trash-can"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: var(--text-secondary); padding: 3rem;">
                        Belum ada data IKK terdaftar.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
