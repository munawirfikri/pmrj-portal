<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Admin - PMRJ Portal</title>
    
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
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <img src="{{ asset('assets/img/logo-pmrj-black.svg') }}" alt="Logo PMRJ" style="height: 75px; width: auto; margin-bottom: 1.25rem;">
                <div class="auth-logo" style="font-size: 1.6rem; font-weight: 800; letter-spacing: 1px;">PMRJ PORTAL</div>
                <p class="auth-subtitle">Masuk sebagai Administrator Back Office</p>
            </div>
            
            <form action="{{ route('login') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="admin@pmrj.or.id" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                    @error('password')
                        <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group" style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 2rem;">
                    <input type="checkbox" id="remember" name="remember" style="accent-color: var(--riau-gold); width: 16px; height: 16px;">
                    <label for="remember" style="color: var(--text-secondary); font-size: 0.9rem; cursor: pointer; user-select: none;">Ingat saya di perangkat ini</label>
                </div>
                
                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-right-to-bracket" style="margin-right: 8px;"></i>Masuk Ke Dashboard
                </button>
            </form>
        </div>
    </div>
</body>
</html>
