<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Ikk;
use Illuminate\Http\Request;

class AnggotaController extends Controller
{
    public function index(Request $request)
    {
        $query = Anggota::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%' . $search . '%')
                  ->orWhere('nik', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by IKK
        if ($request->filled('ikk')) {
            $query->where('asal_ikk', $request->ikk);
        }

        $anggotaList = $query->orderBy('created_at', 'desc')->paginate(10);
        $ikkList = Ikk::orderBy('nama')->get();

        return view('admin.anggota.index', compact('anggotaList', 'ikkList'));
    }

    public function show($id)
    {
        $anggota = Anggota::findOrFail($id);
        return view('admin.anggota.show', compact('anggota'));
    }

    public function edit($id)
    {
        $anggota = Anggota::findOrFail($id);
        $ikkList = Ikk::orderBy('nama')->get();
        return view('admin.anggota.edit', compact('anggota', 'ikkList'));
    }

    public function update(Request $request, $id)
    {
        $anggota = Anggota::findOrFail($id);

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:anggota,email,' . $id,
            'nik' => 'nullable|string|size:16|unique:anggota,nik,' . $id,
            'asal_ikk' => 'required|exists:ikk,nama',
            'no_hp' => 'nullable|string|max:15',
            'tanggal_lahir' => 'nullable|date',
            'tempat_lahir' => 'nullable|string|max:255',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'golongan_darah' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'pekerjaan' => 'nullable|string|max:255',
            'status_rumah' => 'nullable|in:Rumah Tetap,Rumah Kontrak',
            'kota_bagian' => 'nullable|in:Jakarta Utara,Jakarta Selatan,Jakarta Barat,Jakarta Timur,Jakarta Pusat,Kota Tangerang,Kabupaten Tangerang,Tangerang Selatan,Depok,Bekasi,Bogor',
            'alamat_jakarta' => 'nullable|string',
            'role' => 'required|in:member,admin',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'nik.size' => 'NIK harus 16 digit.',
            'nik.unique' => 'NIK sudah terdaftar.',
            'asal_ikk.exists' => 'Asal IKK tidak valid.',
            'role.required' => 'Hak akses wajib diisi.',
            'role.in' => 'Pilihan hak akses tidak valid.'
        ]);

        if ($anggota->id === auth()->id() && $request->role !== 'admin') {
            return back()->withErrors(['role' => 'Anda tidak dapat menurunkan hak akses (demote) akun Anda sendiri untuk mencegah terkunci dari system.'])->withInput();
        }

        $anggota->update($request->all());

        return redirect()->route('admin.anggota.show', $id)->with('success', 'Profil anggota berhasil diperbarui.');
    }

    public function verifyKtp($id)
    {
        $anggota = Anggota::findOrFail($id);
        $anggota->is_verified = !$anggota->is_verified;
        $anggota->save();

        $message = $anggota->is_verified ? 'KTP Anggota berhasil diverifikasi!' : 'Verifikasi KTP Anggota dibatalkan.';
        return redirect()->route('admin.anggota.show', $id)->with('success', $message);
    }

    public function suspend($id)
    {
        $anggota = Anggota::findOrFail($id);
        $anggota->status = ($anggota->status === 'active') ? 'inactive' : 'active';
        $anggota->save();

        $message = ($anggota->status === 'inactive') ? 'Keanggotaan berhasil ditangguhkan.' : 'Keanggotaan berhasil diaktifkan kembali.';
        return redirect()->route('admin.anggota.show', $id)->with('success', $message);
    }

    public function exportCsv(Request $request)
    {
        $query = Anggota::query();

        // Apply same filters as list page
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%' . $search . '%')
                  ->orWhere('nik', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('ikk')) {
            $query->where('asal_ikk', $request->ikk);
        }

        $anggotaList = $query->orderBy('created_at', 'desc')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="data_anggota_pmrj_' . date('Ymd_His') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function () use ($anggotaList) {
            $file = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($file, [
                'No. Anggota', 
                'Nama Lengkap', 
                'Email', 
                'NIK', 
                'No. HP', 
                'IKK', 
                'Tempat Lahir', 
                'Tanggal Lahir', 
                'Jenis Kelamin', 
                'Golongan Darah', 
                'Pekerjaan', 
                'Status Rumah', 
                'Kota Domisili', 
                'Alamat', 
                'Status', 
                'Terverifikasi KTP',
                'Tanggal Terdaftar'
            ]);

            // CSV Data
            foreach ($anggotaList as $anggota) {
                fputcsv($file, [
                    $anggota->no_anggota ?: '-',
                    $anggota->nama_lengkap,
                    $anggota->email ?: '-',
                    $anggota->nik ?: '-',
                    $anggota->no_hp ?: $anggota->no_telepon ?: '-',
                    $anggota->asal_ikk,
                    $anggota->tempat_lahir ?: '-',
                    $anggota->tanggal_lahir ? $anggota->tanggal_lahir->format('Y-m-d') : '-',
                    $anggota->jenis_kelamin ?: '-',
                    $anggota->golongan_darah ?: '-',
                    $anggota->pekerjaan ?: '-',
                    $anggota->status_rumah ?: '-',
                    $anggota->kota_bagian ?: '-',
                    $anggota->alamat_jakarta ?: $anggota->alamat ?: '-',
                    $anggota->status,
                    $anggota->is_verified ? 'Ya' : 'Tidak',
                    $anggota->created_at ? $anggota->created_at->format('Y-m-d H:i:s') : '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function serveMedia($folder, $filename)
    {
        $path = "/Users/munawir.fikri/playground/pmrj/pmrj-anggota/storage/app/public/{$folder}/{$filename}";
        
        if (!file_exists($path)) {
            abort(404);
        }

        $file = file_get_contents($path);
        $type = mime_content_type($path);

        return response($file, 200)->header("Content-Type", $type);
    }
}
