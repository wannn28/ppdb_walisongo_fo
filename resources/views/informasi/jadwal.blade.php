@extends('layouts.app')

@section('content')
    <div class="container mx-auto my-4">
        <h1 class="text-2xl font-bold mb-4">Jadwal</h1>
    </div>
    <div id="jadwalContainer" class="space-y-4">
        <!-- Gambar-gambar akan ditampilkan di sini -->
    </div>
    <div id="noJadwalMessage" class="text-center text-gray-500 py-8 hidden">
        <p class="text-lg">Belum ada jadwal yang tersedia saat ini.</p>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const container = document.getElementById('jadwalContainer');
            let imageUrls = [];

            // Cek apakah data sudah ada di cache dan masih valid
            if (!isCacheExpired('cachedJadwal', 'cachedJadwalTimestamp')) {
                // Gunakan data dari cache
                imageUrls = JSON.parse(localStorage.getItem('cachedJadwal'));
            } else {
                // Jika tidak ada di cache atau sudah expired, fetch langsung
                const res = await AwaitFetchApi('user/media/jadwal', 'GET', null);

                // Ambil gambar jika tersedia
                if (res.meta?.code === 200) {
                    // Struktur API: {meta: {...}, data: [{id, jenjang_sekolah, url, ...}]}
                    if (Array.isArray(res.data) && res.data.length > 0) {
                        imageUrls = res.data.map(item => item.url);
                    }
                } else {
                    // Fallback
                    // imageUrls = ['assets/img/jadwal.png'];
                }

                if (imageUrls.length === 0) {
                    // imageUrls = ['assets/img/jadwal.png'];
                }

                // Cache the data
                localStorage.setItem('cachedJadwal', JSON.stringify(imageUrls));
                localStorage.setItem('cachedJadwalTimestamp', new Date().getTime().toString());
            }
            const noJadwalMessage = document.getElementById('noJadwalMessage');
            if (imageUrls.length === 0) {
               noJadwalMessage.classList.remove('hidden');
            } else {
                imageUrls.forEach(url => {
                    const img = document.createElement('img');
                    img.src = url.startsWith('http') ? url : `{{ asset('') }}` + url;
                    img.alt = "Jadwal";
                    img.className = "w-full";
                    container.appendChild(img);
                });
            }
        });
    </script>
@endsection
