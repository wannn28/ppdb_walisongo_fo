@extends('layouts.app')

@section('content')
    <div class="container mx-auto my-4">
        <h1 class="text-2xl font-bold mb-4">Berita</h1>
    </div>
    <div id="beritaContainer" class="space-y-4">
        <!-- Gambar-gambar akan ditampilkan di sini -->
    </div>
    <div id="noBeritaMessage" class="text-center text-gray-500 py-8 hidden">
      <p class="text-lg">Belum ada berita yang tersedia saat ini.</p>
   </div>
   

    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const container = document.getElementById('beritaContainer');
            let imageUrls = [];

            // Cek apakah data sudah ada di cache dan masih valid
            if (!isCacheExpired('cachedBerita', 'cachedBeritaTimestamp')) {
                // Gunakan data dari cache
                imageUrls = JSON.parse(localStorage.getItem('cachedBerita'));
            } else {
                // Jika tidak ada di cache atau sudah expired, fetch langsung
                const res = await AwaitFetchApi('user/berita', 'GET', null);

                // Ambil gambar jika tersedia
                if (res.meta?.code === 200 && Array.isArray(res.data?.data) && res.data.data.length > 0) {
                    imageUrls = res.data.data.map(item => item.url || item);
                } else {
                    // Fallback
                    // imageUrls = ['assets/img/logo.png'];
                }

                // Cache the data
                localStorage.setItem('cachedBerita', JSON.stringify(imageUrls));
                localStorage.setItem('cachedBeritaTimestamp', new Date().getTime().toString());
            }
            const noBeritaMessage = document.getElementById('noBeritaMessage');
            if (imageUrls.length === 0) {
                noBeritaMessage.classList.remove('hidden');
            } else {
                imageUrls.forEach(url => {
                    const img = document.createElement('img');
                    img.src = url.startsWith('http') ? url : `{{ asset('') }}` + url;
                    img.alt = "Berita";
                    img.className = "w-full";
                    container.appendChild(img);
                });
            }
        });
    </script>
@endsection
