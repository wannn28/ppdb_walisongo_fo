<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPDB WALISONGO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script src="{{ asset('js/app.js') }}?v={{ filemtime(public_path('js/app.js')) }}" defer></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ filemtime(public_path('css/style.css')) }}">
    <script src="{{ asset('js/logger.js') }}?v={{ filemtime(public_path('js/logger.js')) }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    {{-- @yield('header') --}}
    @yield('started')

    <!-- Navbar khusus halaman started -->
    <div class="fixed bottom-0 left-0 w-full flex justify-center z-50">
        <div class="relative w-full max-w-md bg-white rounded-t-2xl shadow-lg flex items-center justify-between px-12 py-3">
            <!-- Left Button: Join Sekarang (Font Awesome User Plus Icon) -->
            <a href="/login" class="flex flex-col items-center text-gray-500 hover:text-[#1E88E5] focus:outline-none">
                <i class="fas fa-user-plus fa-lg mb-1"></i>
                <span class="text-xs">Join Sekarang</span>
            </a>
            <!-- Cekungan SVG -->
            <div class="absolute left-1/2 -translate-x-1/2 -top-7 z-0 pointer-events-none" style="width: 80px; height: 40px;">
                <svg width="80" height="40" viewBox="0 0 80 40" fill="none">
                    <path d="M0,40 Q40,0 80,40" fill="white"/>
                </svg>
            </div>
            <!-- Center Floating Button: Home Icon (Font Awesome) -->
            <a href="/" class="absolute left-1/2 -translate-x-1/2 -top-10 bg-[#51C2FF] hover:bg-[#1E88E5] text-white rounded-full w-20 h-20 flex items-center justify-center shadow-lg border-4 border-white focus:outline-none z-10">
                <i class="fas fa-home fa-2x"></i>
            </a>
            <!-- Right Button: Hubungi Kami (Font Awesome WhatsApp Icon) -->
            <a href="https://wa.me/{{ config('app.wa_contact','6281234567890') }}" target="_blank" class="flex flex-col items-center text-gray-500 hover:text-[#1E88E5] focus:outline-none">
                <i class="fab fa-whatsapp fa-lg mb-1"></i>
                <span class="text-xs">Hubungi Kami</span>
            </a>
        </div>
    </div>

    @stack('scripts')
    <div id="global-loading"
        class="fixed inset-0 z-[9999999999999999999999999999999] bg-black bg-opacity-40 flex items-center justify-center hidden">
        <div class="minimal-loading-container">
            <div class="loading-dots">
                <div class="loading-dot"></div>
                <div class="loading-dot"></div>
                <div class="loading-dot"></div>
            </div>
        </div>
    </div>
</body>

</html>
