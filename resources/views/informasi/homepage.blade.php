@extends('layouts.started')

@section('started')
    <div id="beritaContainer" class="space-y-4">
        <!-- Gambar-gambar akan ditampilkan di sini -->
    </div>

    {{-- <div class="fixed bottom-0 left-0 w-full flex justify-center z-50">
        <div class="relative w-full max-w-md bg-white rounded-t-2xl shadow-lg flex items-center justify-between px-12 py-3">
            <!-- Left Button: Join Sekarang (Font Awesome User Plus Icon) -->
            <button id="loginBtn" class="flex flex-col items-center text-gray-500 hover:text-[#1E88E5] focus:outline-none">
                <i class="fas fa-user-plus fa-lg mb-1"></i>
                <span class="text-xs">Join Sekarang</span>
            </button>
            <!-- Cekungan SVG -->
            <div class="absolute left-1/2 -translate-x-1/2 -top-7 z-0 pointer-events-none" style="width: 80px; height: 40px;">
                <svg width="80" height="40" viewBox="0 0 80 40" fill="none">
                    <path d="M0,40 Q40,0 80,40" fill="white"/>
                </svg>
            </div>
            <!-- Center Floating Button: Home Icon (Font Awesome) -->
            <button class="absolute left-1/2 -translate-x-1/2 -top-10 bg-[#51C2FF] hover:bg-[#1E88E5] text-white rounded-full w-20 h-20 flex items-center justify-center shadow-lg border-4 border-white focus:outline-none z-10">
                <i class="fas fa-home fa-2x"></i>
            </button>
            <!-- Right Button: Hubungi Kami (Font Awesome WhatsApp Icon) -->
            <button class="flex flex-col items-center text-gray-500 hover:text-[#1E88E5] focus:outline-none">
                <i class="fab fa-whatsapp fa-lg mb-1"></i>
                <span class="text-xs">Hubungi Kami</span>
            </button>
        </div>
    </div> --}}

    <script>

        document.addEventListener('DOMContentLoaded', async () => {
            const container = document.getElementById('beritaContainer');

            const res = await AwaitFetchApi('home', 'GET', null, true);

            let imageUrls = [];

            // Ambil gambar jika tersedia
            if (res.meta?.code === 200 && Array.isArray(res.data?.data) && res.data.data.length > 0) {
                imageUrls = res.data.data.map(item => item.url || item); // Ambil properti 'url' jika ada
            } else {
                // Fallback
                imageUrls = ['assets/img/berita.png'];
            }

            imageUrls.forEach(url => {
                const img = document.createElement('img');
                img.src = url.startsWith('http') ? url : `{{ asset('') }}` + url;
                img.alt = "Berita";
                img.className = "w-full";
                container.appendChild(img);
            });
        });
    </script>
@endsection
