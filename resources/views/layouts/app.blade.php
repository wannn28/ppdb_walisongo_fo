<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPDB Walisongo</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ filemtime(public_path('css/style.css')) }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script src="{{ asset('js/app.js') }}?v={{ filemtime(public_path('js/app.js')) }}" defer></script>
    <script src="{{ asset('js/logger.js') }}?v={{ filemtime(public_path('js/logger.js')) }}" defer></script>
    <script src="{{ asset('js/generateqris.js') }}?v={{ filemtime(public_path('js/generateqris.js')) }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/qrious@4.0.2/dist/qrious.min.js"></script>
    <script>
        window.APP_DEBUG_VIEW = "{{ env('APP_DEBUG_VIEW', false) ? 'true' : 'false' }}";
        window.API_BASE_URL = '{{ config('app.api_url') }}';
    </script>
    <style>
        .loading-dots {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }
        
        .loading-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: #51C2FF;
            animation: dotPulse 1.5s infinite ease-in-out;
        }
        
        .loading-dot:nth-child(1) {
            animation-delay: 0s;
        }
        
        .loading-dot:nth-child(2) {
            animation-delay: 0.3s;
        }
        
        .loading-dot:nth-child(3) {
            animation-delay: 0.6s;
        }
        
        @keyframes dotPulse {
            0%, 100% {
                transform: scale(0.6);
                opacity: 0.5;
            }
            50% {
                transform: scale(1);
                opacity: 1;
            }
        }
        
        .minimal-loading-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            padding: 24px 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }
    </style>
</head>
@section('header')
    @include('components.header')
@endsection

<body class="max-w-sm mx-auto relative min-h-screen">

    @yield('header')
    <div class="p-4 space-y-4 bg-[#f8f8f8] font-semibold pb-24 min-h-screen pt-24">
        @yield('content')
    </div>
    @section('navbar')
        @include('components.navbar')
    @endsection
    @yield('navbar')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <!-- Global Loading Spinner -->
    <div id="global-loading"
        class="fixed inset-0 z-[9999] bg-black bg-opacity-40 flex items-center justify-center hidden">
        <div class="minimal-loading-container">
            <div class="loading-dots">
                <div class="loading-dot"></div>
                <div class="loading-dot"></div>
                <div class="loading-dot"></div>
            </div>
        </div>
    </div>
    @stack('scripts')
</body>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const token = localStorage.getItem('token');

        if (!token) {
            window.location.href = '/login';
            print.warn("Token tidak ditemukan di localStorage.");
            return Promise.resolve({
                message: 'Token tidak ditemukan'
            });
        }
    })
</script>

</html>
