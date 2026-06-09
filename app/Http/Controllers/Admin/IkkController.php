<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ikk;
use Illuminate\Http\Request;

class IkkController extends Controller
{
    public function index()
    {
        $ikkList = Ikk::orderBy('kode')->get();
        return view('admin.ikk.index', compact('ikkList'));
    }

    public function create()
    {
        return view('admin.ikk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:10|unique:ikk,kode',
            'nama' => 'required|string|max:255|unique:ikk,nama'
        ], [
            'kode.required' => 'Kode IKK wajib diisi.',
            'kode.unique' => 'Kode IKK ini sudah digunakan.',
            'nama.required' => 'Nama IKK wajib diisi.',
            'nama.unique' => 'Nama IKK ini sudah terdaftar.'
        ]);

        Ikk::create([
            'kode' => $request->kode,
            'nama' => $request->nama
        ]);

        return redirect()->route('admin.ikk.index')
            ->with('success', 'IKK baru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $ikk = Ikk::findOrFail($id);
        return view('admin.ikk.edit', compact('ikk'));
    }

    public function update(Request $request, $id)
    {
        $ikk = Ikk::findOrFail($id);

        $request->validate([
            'kode' => 'required|string|max:10|unique:ikk,kode,' . $id,
            'nama' => 'required|string|max:255|unique:ikk,nama,' . $id
        ], [
            'kode.required' => 'Kode IKK wajib diisi.',
            'kode.unique' => 'Kode IKK ini sudah digunakan oleh IKK lain.',
            'nama.required' => 'Nama IKK wajib diisi.',
            'nama.unique' => 'Nama IKK ini sudah terdaftar.'
        ]);

        $ikk->update([
            'kode' => $request->kode,
            'nama' => $request->nama
        ]);

        return redirect()->route('admin.ikk.index')
            ->with('success', 'Data IKK berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $ikk = Ikk::findOrFail($id);
        $ikk->delete();

        return redirect()->route('admin.ikk.index')
            ->with('success', 'IKK berhasil dihapus.');
    }
}
