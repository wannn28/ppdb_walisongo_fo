@extends('layouts.app')

@section('content')
   <div id="jadwalContainer" class="space-y-4">
      <!-- Gambar-gambar akan ditampilkan di sini -->
   </div>

   <script>
      document.addEventListener('DOMContentLoaded', async () => {
         const container = document.getElementById('jadwalContainer');

         const res = await AwaitFetchApi('user/media/jadwal', 'GET', null);

         let imageUrls = [];

         console.log('API Response:', res); // Log untuk debugging

         // Ambil gambar jika tersedia
         if (res.meta?.code === 200) {
            // Struktur API: {meta: {...}, data: [{id, jenjang_sekolah, url, ...}]}
            if (Array.isArray(res.data) && res.data.length > 0) {
               imageUrls = res.data.map(item => item.url);
            }
         } else {
            // Fallback
            imageUrls = ['assets/img/jadwal.png'];
         }

         if (imageUrls.length === 0) {
            imageUrls = ['assets/img/jadwal.png'];
         }

         imageUrls.forEach(url => {
            const img = document.createElement('img');
            img.src = url.startsWith('http') ? url : `{{ asset('') }}` + url;
            img.alt = "Jadwal";
            img.className = "w-full";
            container.appendChild(img);
         });
      });
   </script>
@endsection