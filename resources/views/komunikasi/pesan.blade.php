@extends('layouts.app')

@section('content')
<div class="text-xl flex w-full justify-center font-bold mb-4">Pesan</div>
<div id="container-pesan" class="w-full font-thin text-xs space-y-2 px-2">
    <!-- Pesan akan dimasukkan di sini lewat JavaScript -->
</div>

<!-- Modal Detail Pesan -->
<div id="pesan-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg w-11/12 max-w-md p-4 max-h-[80vh] overflow-y-auto">
        <div class="flex justify-between items-center border-b pb-2 mb-3">
            <h3 id="modal-judul" class="text-lg font-bold text-[#0267B2]"></h3>
            <button id="close-modal" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="text-xs text-gray-500 mb-2" id="modal-tanggal"></div>
        <div id="modal-deskripsi" class="text-sm"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const modal = document.getElementById('pesan-modal');
        const closeModal = document.getElementById('close-modal');
        const modalJudul = document.getElementById('modal-judul');
        const modalDeskripsi = document.getElementById('modal-deskripsi');
        const modalTanggal = document.getElementById('modal-tanggal');
        
        // Function to open modal and show message details
        function showMessageDetail(id, judul, deskripsi, tanggal, isRead) {
            modalJudul.textContent = judul;
            modalDeskripsi.innerHTML = deskripsi;
            modalTanggal.textContent = tanggal;
            modal.classList.remove('hidden');
            
            // Mark as read if not already read
            if (!isRead) {
                markAsRead(id);
            }
        }
        
        // Function to mark message as read
        function markAsRead(id) {
            AwaitFetchApi(`user/pesan/${id}`, 'GET', null)
                .then(res => {
                    if (res && res.meta?.code === 200) {
                        // Update UI to show message is read
                        const unreadIndicator = document.querySelector(`#unread-${id}`);
                        if (unreadIndicator) {
                            unreadIndicator.classList.add('hidden');
                        }
                        
                        // Update the message counter in navbar if exists
                        const navbarBadge = document.getElementById('navbar-unread-count');
                        if (navbarBadge && !navbarBadge.classList.contains('hidden')) {
                            const currentCount = parseInt(navbarBadge.textContent) || 0;
                            if (currentCount > 1) {
                                navbarBadge.textContent = currentCount - 1;
                            } else {
                                navbarBadge.classList.add('hidden');
                            }
                        }
                    }
                })
                .catch(error => {
                    console.error('Error marking message as read:', error);
                });
        }
        
        // Close modal when clicking the close button
        closeModal.addEventListener('click', function() {
            modal.classList.add('hidden');
        });
        
        // Close modal when clicking outside of it
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                modal.classList.add('hidden');
            }
        });

        // Fetch messages
        AwaitFetchApi('user/pesan', 'GET', null)
            .then(res => {
                const container = document.getElementById('container-pesan');
                if (res && res.data && Array.isArray(res.data)) {
                    res.data.forEach(pesan => {
                        const createdAt = new Date(pesan.created_at);
                        const tanggalFormatted = createdAt.toLocaleDateString('id-ID', {
                            day: 'numeric', month: 'long', year: 'numeric',
                            hour: '2-digit', minute: '2-digit'
                        });

                        const pesanHTML = `
                            <div class="cursor-pointer hover:bg-gray-100 rounded-lg" 
                                 onclick="showMessageDetail(${pesan.id}, '${pesan.judul.replace(/'/g, "\\'")}', '${pesan.deskripsi.replace(/'/g, "\\'")}', '${tanggalFormatted}', ${pesan.is_read === 1})">
                                <div class="flex justify-between p-3 border-b border-gray-400">
                                    <div class="flex flex-col pr-2">
                                        <span class="font-medium text-sm">${pesan.judul}</span>
                                        <span class="text-xs text-gray-500">${tanggalFormatted}</span>
                                    </div>
                                    <div id="unread-${pesan.id}" class="flex items-center ${pesan.is_read === 1 ? 'hidden' : ''}">
                                        <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                                    </div>
                                </div>
                            </div>
                        `;
                        container.insertAdjacentHTML('beforeend', pesanHTML);
                    });
                    
                    // Make showMessageDetail function available globally
                    window.showMessageDetail = showMessageDetail;
                } else {
                    container.innerHTML = '<div class="text-center py-4">Tidak ada pesan</div>';
                }
            })
            .catch(error => {
                console.error('Error fetching messages:', error);
                document.getElementById('container-pesan').innerHTML = '<div class="text-center py-4 text-red-500">Gagal memuat pesan</div>';
            });
    });
</script>
@endpush