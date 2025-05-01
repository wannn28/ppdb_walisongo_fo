@extends('layouts.app')

@section('content')
    <div class="text-xl flex w-full justify-center font-bold">Riwayat</div>
    
    <div id="riwayat-container" class="w-full font-normal text-xs">
        <!-- Data riwayat akan dimasukkan di sini lewat JavaScript -->
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            const container = document.getElementById('riwayat-container');
            
            try {
                const response = await AwaitFetchApi('user/riwayat', 'GET', null);
                
                if (response.meta?.code === 200 && response.data) {
                    // Grouping transaksi by date
                    const transaksiByDate = {};
                    
                    response.data.forEach(transaksi => {
                        // Extract date part only (YYYY-MM-DD)
                        const date = transaksi.created_at ? transaksi.created_at.split('T')[0] : 'undefined';
                        
                        if (!transaksiByDate[date]) {
                            transaksiByDate[date] = [];
                        }
                        
                        transaksiByDate[date].push(transaksi);
                    });
                    
                    // Clear loading indicator
                    container.innerHTML = '';
                    
                    // Check if there are any transactions
                    if (Object.keys(transaksiByDate).length === 0) {
                        container.innerHTML = '<div class="p-4 text-center text-gray-500">Tidak ada riwayat transaksi</div>';
                        return;
                    }
                    
                    // Sort dates in descending order (newest first)
                    const sortedDates = Object.keys(transaksiByDate).sort((a, b) => new Date(b) - new Date(a));
                    
                    // Build HTML for each date group
                    sortedDates.forEach(date => {
                        const transaksiGroup = transaksiByDate[date];
                        
                        // Format date in Indonesian
                        const formattedDate = new Date(date).toLocaleDateString('id-ID', {
                            day: 'numeric',
                            month: 'long',
                            year: 'numeric'
                        });
                        
                        // Add date header
                        let html = `
                            <span class="bg-[#E5E5E5] w-full flex p-2 font-normal text-gray mt-2">
                                ${formattedDate}
                            </span>
                        `;
                        
                        // Add transactions for this date
                        transaksiGroup.forEach(transaksi => {
                            // Format currency
                            const formattedAmount = new Intl.NumberFormat('id-ID').format(transaksi.total || 0);
                            const namaTagihan = transaksi.tagihan?.nama_tagihan || '';
                            
                            // Remove underscores and capitalize first letter of each word
                            const formattedNamaTagihan = namaTagihan.replace(/_/g, ' ').replace(/\b\w/g, char => char.toUpperCase());
                            
                            // Format time (hours and minutes)
                            let timeString = '';
                            if (transaksi.created_at) {
                                const datetime = new Date(transaksi.created_at);
                                timeString = datetime.toLocaleTimeString('id-ID', {
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    hour12: false
                                });
                            }
                            
                            html += `
                                <div class="flex justify-between border-b border-gray-400 relative pb-2 pl-2">
                                    <div class="flex flex-col">
                                        <span class="font-medium text-sm">${formattedNamaTagihan}</span>
                                        <span>${transaksi.ref_no || 'No. ' + transaksi.id}</span>
                                        <span>${transaksi.method || 'Pembayaran'}</span>
                                    </div>
                                    <div class="absolute bottom-2 right-2">
                                        <span class="font-normal text-sm">Rp${formattedAmount}</span>
                                    </div>
                                    <span class="absolute top-1 right-2 text-[10px] text-gray-500">${timeString}</span>
                                </div>
                            `;
                        });
                        
                        container.innerHTML += html;
                    });
                } else {
                    container.innerHTML = '<div class="p-4 text-center text-gray-500">Tidak ada riwayat transaksi</div>';
                }
            } catch (error) {
                print.error('Error fetching transaction history:', error);
                container.innerHTML = '<div class="p-4 text-center text-red-500">Terjadi kesalahan saat memuat data. Silakan coba lagi nanti.</div>';
            }
        });
    </script>
@endsection