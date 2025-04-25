@extends('layouts.app')

@section('content')
<div class="text-xl flex w-full justify-center font-bold mb-4">Pesan</div>
<div id="container-pesan" class="w-full font-thin text-xs space-y-2 px-2">
    <!-- Pesan akan dimasukkan di sini lewat JavaScript -->
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        AwaitFetchApi('user/pesan', 'GET', null).then(res => {
            const container = document.getElementById('container-pesan');
            if (res && res.data && Array.isArray(res.data)) {
                res.data.forEach(pesan => {
                    const createdAt = new Date(pesan.created_at);
                    const tanggalFormatted = createdAt.toLocaleDateString('id-ID', {
                        day: 'numeric', month: 'long', year: 'numeric',
                        hour: '2-digit', minute: '2-digit'
                    });

                    const pesanHTML = `
                        <span class="w-full flex text-[12px] pl-2 pr-2">${tanggalFormatted}</span>
                        <div class="flex justify-between p-2 border-b border-gray-400">
                            <div class="flex flex-col pr-2">
                                <span class="font-bold text-sm">${pesan.judul}</span>
                                <span class="font-medium">${pesan.deskripsi}</span>
                            </div>
                            ${pesan.is_read === 0 ? `
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                                </div>
                            ` : ''}
                        </div>
                    `;
                    container.insertAdjacentHTML('beforeend', pesanHTML);
                });
            } else {
                container.innerHTML = '<div class="text-center py-4">Tidak ada pesan</div>';
            }
        }).catch(error => {
            console.error('Error fetching messages:', error);
            document.getElementById('container-pesan').innerHTML = '<div class="text-center py-4 text-red-500">Gagal memuat pesan</div>';
        });
    });
</script>
@endpush