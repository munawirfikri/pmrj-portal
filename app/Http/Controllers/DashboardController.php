<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anggota;
use App\Models\Ikk;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // General stats
        $totalActive = Anggota::where('status', 'active')->count();
        $totalPending = Anggota::where('status', 'pending')->count();
        $totalInactive = Anggota::where('status', 'inactive')->count();
        $totalIkk = Ikk::count();

        // Member distribution by IKK
        $ikkDistribution = Anggota::select('asal_ikk', DB::raw('count(*) as total'))
            ->groupBy('asal_ikk')
            ->orderBy('total', 'desc')
            ->get();

        $ikkLabels = [];
        $ikkData = [];
        foreach ($ikkDistribution as $item) {
            $ikkLabels[] = $item->asal_ikk ?: 'Tidak Diketahui';
            $ikkData[] = $item->total;
        }

        // Member distribution by Jabodetabek area (kota_bagian)
        $areaDistribution = Anggota::select('kota_bagian', DB::raw('count(*) as total'))
            ->groupBy('kota_bagian')
            ->orderBy('total', 'desc')
            ->get();

        $areaLabels = [];
        $areaData = [];
        foreach ($areaDistribution as $item) {
            $areaLabels[] = $item->kota_bagian ?: 'Belum Mengisi';
            $areaData[] = $item->total;
        }

        // Get latest 5 members registered
        $latestMembers = Anggota::orderBy('created_at', 'desc')->limit(5)->get();

        return view('admin.dashboard', compact(
            'totalActive',
            'totalPending',
            'totalInactive',
            'totalIkk',
            'ikkLabels',
            'ikkData',
            'areaLabels',
            'areaData',
            'latestMembers'
        ));
    }
}
