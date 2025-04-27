@extends('layouts.app')

@section('content')
   <div id="jadwalContainer" class="space-y-4">
      <!-- Gambar-gambar akan ditampilkan di sini -->
   </div>

   <script>
      document.addEventListener('DOMContentLoaded', async () => {
         const container = document.getElementById('jadwalContainer');

         const res = await AwaitFetchApi('user/media', 'GET', null);

         let imageUrls = [];

         // Ambil gambar jika tersedia
         if (res.meta?.code === 200 && Array.isArray(res.data?.data) && res.data.data.length > 0) {
            imageUrls = res.data.data.map(item => item.url || item); // Ambil properti 'url' jika ada
         } else {
            // Fallback
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