@extends('layouts.started')

@section('started')
    <div id="beritaContainer" class="space-y-4">
        <!-- Gambar-gambar akan ditampilkan di sini -->
    </div>

    <div class="w-full flex justify-center">
        <button id="loginBtn" class="w-48 h-12 bg-gradient-to-r from-[#51C2FF] to-[#1E88E5] rounded-lg text-white font-bold cursor-pointer shadow-md hover:shadow-lg transform hover:scale-[1.02] transition-all duration-200 flex items-center justify-center fixed bottom-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V3zm1 4h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V8a1 1 0 011-1zm0 4h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1v-2a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Join Sekarang
        </button>
    </div>

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

        const loginBtn = document.getElementById('loginBtn');
        loginBtn.addEventListener('click', () => {
            window.location.href = '/login';
        });
    </script>
@endsection
