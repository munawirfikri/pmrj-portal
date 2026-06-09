@extends('layouts.admin')

@section('title', 'Tambah IKK Baru')
@section('subtitle', 'Tambahkan Ikatan Keluarga Kabupaten/Kota baru di bawah naungan PMRJ.')

@section('content')
<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('admin.ikk.index') }}" class="btn-action btn-view">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
    </a>
</div>

<div class="card-premium" style="max-width: 600px;">
    <h2 class="card-title" style="margin-bottom: 1.5rem;"><i class="fa-solid fa-folder-plus" style="color: var(--riau-gold); margin-right: 8px;"></i>Form IKK Baru</h2>
    
    <form action="{{ route('admin.ikk.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="kode" class="form-label">Kode IKK</label>
            <input type="text" id="kode" name="kode" class="form-control" placeholder="Contoh: 01, 02, 12" value="{{ old('kode') }}" required autofocus>
            <p style="color: var(--text-secondary); font-size: 0.8rem; margin-top: 0.25rem;">
                Kode ini digunakan sebagai identitas pada nomor keanggotaan (misal: PMRJ-[Kode IKK]-[No Urut]).
            </p>
            @error('kode')
                <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group" style="margin-bottom: 2rem;">
            <label for="nama" class="form-label">Nama IKK</label>
            <input type="text" id="nama" name="nama" class="form-control" placeholder="Contoh: IKK Kampar, IKK Bengkalis" value="{{ old('nama') }}" required>
            @error('nama')
                <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
            @enderror
        </div>
        
        <button type="submit" class="btn-action btn-add" style="width: 100%; display: flex; align-items: center; justify-content: center; padding: 0.85rem; border-radius: 10px; cursor: pointer;">
            <i class="fa-solid fa-floppy-disk" style="margin-right: 8px;"></i> Simpan Data IKK
        </button>
    </form>
</div>
@endsection
