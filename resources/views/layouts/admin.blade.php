<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - PMRJ Portal</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/img/logo-pmrj-black.svg') }}">
    
    <!-- PWA Settings -->
    <meta name="theme-color" content="#3985c3">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="PMRJ Portal">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/portal.css') }}">
    
    <!-- Icons (FontAwesome) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @yield('styles')
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header" style="gap: 0.5rem; margin-bottom: 2rem;">
                <img src="{{ asset('assets/img/logo-pmrj-black.svg') }}" alt="Logo PMRJ" style="height: 38px; width: auto; filter: brightness(0) invert(1) drop-shadow(0 0 5px rgba(255,255,255,0.15));">
                <span class="sidebar-logo-text" style="font-size: 1.15rem; font-weight: 800; letter-spacing: 0.5px;">PMRJ Portal</span>
            </div>
            
            <ul class="sidebar-menu">
                <li class="sidebar-label" style="font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 1px; padding-left: 1rem;">Back Office</li>
                
                <li class="sidebar-item {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fa-solid fa-chart-pie"></i>
                        <span>Overview</span>
                    </a>
                </li>
                
                <li class="sidebar-item {{ Route::is('admin.anggota.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.anggota.index') }}">
                        <i class="fa-solid fa-users-gear"></i>
                        <span>Verifikasi Anggota</span>
                    </a>
                </li>
                
                <li class="sidebar-item {{ Route::is('admin.ikk.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.ikk.index') }}">
                        <i class="fa-solid fa-folder-tree"></i>
                        <span>Data Master IKK</span>
                    </a>
                </li>
            </ul>
            
            <div class="sidebar-footer">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            <!-- Content Header -->
            <header class="content-header">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <button type="button" id="sidebar-toggle" class="sidebar-toggle" aria-label="Buka Menu">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <div>
                        <h1 class="page-title" style="margin-bottom: 0;">@yield('title')</h1>
                        <p class="page-subtitle" style="margin-top: 0.25rem;">@yield('subtitle')</p>
                    </div>
                </div>
                
                <div style="display: flex; align-items: center; gap: 1rem; background: #ffffff; padding: 0.5rem 1rem; border-radius: 12px; border: 1px solid var(--card-border); box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                    <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--primary); color: #ffffff; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    </div>
                    <div>
                        <div style="font-size: 0.9rem; font-weight: 600; color: var(--text-primary);">{{ Auth::user()->name ?? 'Administrator' }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary);">Administrator</div>
                    </div>
                </div>
            </header>
            
            @if(session('success'))
                <div class="alert alert-success fade-in">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger fade-in">
                    <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
                </div>
            @endif
            
            <!-- Dynamic Content -->
            <div class="fade-in">
                @yield('content')
            </div>
        </main>
    </div>
    
    @yield('scripts')

    <!-- PWA & Responsive Sidebar JavaScript -->
    <script>
        // Register Service Worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/service-worker.js')
                    .then(reg => console.log('Service Worker registered successfully.', reg))
                    .catch(err => console.error('Service Worker registration failed.', err));
            });
        }

        // Toggle Sidebar and Overlay
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('sidebar-toggle');
            const sidebar = document.querySelector('.sidebar');
            
            if (toggleBtn && sidebar) {
                // Create overlay element
                const overlay = document.createElement('div');
                overlay.className = 'sidebar-overlay';
                document.body.appendChild(overlay);
                
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                    overlay.classList.toggle('active');
                });
                
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                });
            }
        });
    </script>
</body>
</html>
