@extends('layouts.admin')

@section('title', 'Overview Dashboard')
@section('subtitle', 'Pantau statistik keanggotaan dan demografi warga PMRJ secara realtime.')

@section('content')
<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon green">
            <i class="fa-solid fa-user-check"></i>
        </div>
        <div>
            <div class="stat-number">{{ $totalActive }}</div>
            <div class="stat-label">Anggota Aktif</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon gold">
            <i class="fa-solid fa-user-clock"></i>
        </div>
        <div>
            <div class="stat-number">{{ $totalPending }}</div>
            <div class="stat-label">Butuh Verifikasi</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon red">
            <i class="fa-solid fa-user-xmark"></i>
        </div>
        <div>
            <div class="stat-number">{{ $totalInactive }}</div>
            <div class="stat-label">Ditangguhkan</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon blue">
            <i class="fa-solid fa-house-chimney-window"></i>
        </div>
        <div>
            <div class="stat-number">{{ $totalIkk }}</div>
            <div class="stat-label">Total IKK Terdaftar</div>
        </div>
    </div>
</div>

<!-- Charts Grid -->
<div class="charts-grid">
    <div class="chart-card">
        <h3 class="chart-title">Penyebaran Anggota Berdasarkan IKK</h3>
        <div style="height: 300px; position: relative;">
            <canvas id="ikkChart"></canvas>
        </div>
    </div>
    
    <div class="chart-card">
        <h3 class="chart-title">Penyebaran Anggota Berdasarkan Wilayah Jabodetabek</h3>
        <div style="height: 300px; position: relative;">
            <canvas id="areaChart"></canvas>
        </div>
    </div>
</div>

<!-- Latest Members List -->
<div class="card-premium">
    <div class="card-title-container">
        <h2 class="card-title"><i class="fa-solid fa-user-plus" style="color: var(--primary); margin-right: 8px;"></i>Pendaftaran Anggota Terbaru</h2>
        <a href="{{ route('admin.anggota.index') }}" class="btn-action btn-view">Lihat Semua Anggota</a>
    </div>
    
    <div class="table-responsive">
        <table class="table-premium">
            <thead>
                <tr>
                    <th>Nama Lengkap</th>
                    <th>Email</th>
                    <th>Asal IKK</th>
                    <th>Wilayah Tinggal</th>
                    <th>Tanggal Daftar</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($latestMembers as $anggota)
                <tr>
                    <td style="font-weight: 600; color: var(--text-primary);">{{ $anggota->nama_lengkap }}</td>
                    <td>{{ $anggota->email ?: '-' }}</td>
                    <td>{{ $anggota->asal_ikk }}</td>
                    <td>{{ $anggota->kota_bagian ?: '-' }}</td>
                    <td>{{ $anggota->created_at->format('d M Y H:i') }}</td>
                    <td>
                        <span class="badge {{ $anggota->status }}">
                            {{ $anggota->status }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.anggota.show', $anggota->id) }}" class="btn-action btn-view" title="Detail / Verifikasi">
                            <i class="fa-solid fa-magnifying-glass"></i> Periksa
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-secondary); padding: 2rem;">
                        Belum ada anggota yang mendaftar.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<!-- ChartJS CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Global Chart Configuration
    Chart.defaults.color = '#94a3b8';
    Chart.defaults.font.family = "'Outfit', sans-serif";

    // IKK Distribution Chart
    const ikkCtx = document.getElementById('ikkChart').getContext('2d');
    const ikkLabels = {!! json_encode($ikkLabels) !!};
    const ikkData = {!! json_encode($ikkData) !!};

    if (ikkData.length === 0) {
        // Render empty state
        ikkCtx.font = "16px sans-serif";
        ikkCtx.fillStyle = "#94a3b8";
        ikkCtx.textAlign = "center";
        ikkCtx.fillText("Belum ada data anggota", 225, 150);
    } else {
        new Chart(ikkCtx, {
            type: 'bar',
            data: {
                labels: ikkLabels,
                datasets: [{
                    label: 'Jumlah Anggota',
                    data: ikkData,
                    backgroundColor: 'rgba(57, 133, 195, 0.75)', // Brand blue with opacity
                    borderColor: '#3985c3',
                    borderWidth: 1.5,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        grid: { color: '#e2e8f0' },
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // Area Distribution Chart
    const areaCtx = document.getElementById('areaChart').getContext('2d');
    const areaLabels = {!! json_encode($areaLabels) !!};
    const areaData = {!! json_encode($areaData) !!};

    if (areaData.length === 0) {
        areaCtx.font = "16px sans-serif";
        areaCtx.fillStyle = "#94a3b8";
        areaCtx.textAlign = "center";
        areaCtx.fillText("Belum ada data wilayah", 225, 150);
    } else {
        new Chart(areaCtx, {
            type: 'doughnut',
            data: {
                labels: areaLabels,
                datasets: [{
                    data: areaData,
                    backgroundColor: [
                        '#6366f1', // Indigo
                        '#10b981', // Green
                        '#f59e0b', // Gold
                        '#f43f5e', // Red
                        '#06b6d4', // Cyan
                        '#a855f7', // Purple
                        '#ec4899', // Pink
                        '#3b82f6', // Blue
                        '#f97316', // Orange
                        '#84cc16', // Lime
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 12,
                            padding: 15
                        }
                    }
                }
            }
        });
    }
</script>
@endsection
