@extends('layouts.app')

@section('content')
    <div>
        <div class="text-xl flex w-full justify-center font-bold ">Pengajuan Biaya</div>
        <div class="w-full justify-center font-medium flex text-[12px] text-[#757575]">Detail biaya pendaftaran</div>
    </div>
    <div id="regulerSection" class="hidden">
        <div id="regulerImageContainer" class="w-full mt-4"></div>
        <div class="flex w-full justify-between font-bold text-xs pb-2 py-4">
            <span>Total : </span>
            <span id="totalAmount">Rp4.300.000</span>
        </div>
        <button id="regulerPaymentBtn" class="mt-4 w-full h-10 bg-[#51C2FF] rounded-lg text-white cursor-pointer text-sm font-normal shadow-xl">
            Proses Pembayaran
        </button>
        <div class="text-[#757575] text-xs font-normal flex flex-col items-center justify-center text-center p-2">
            <div>
                <img src="{{ asset('assets/svg/notif-message.svg') }}" alt="" class="mx-auto">
            </div>
            <div class="mt-1">
                Biaya registrasi yang sudah dibayarkan tidak bisa kembali
            </div>
        </div>
    </div>

    <!-- Section Unggulan -->
    <div id="unggulanSection" class="hidden">
        <div id="bookVeeContainer" class="w-full flex justify-between gap-4 bg-[#51C2FF40] h-16 rounded-lg items-center p-2">
            <span id="bookVeeText">Booking Vee Rp-</span>
            <button id="bookVeeBtn"
                class="w-24 flex justify-center text-xs bg-[#51C2FF] text-white p-2 rounded-lg shadow-lg">Booking</button>
        </div>
        <div class="mt-4 flex flex-col">
            Jariah Perorangan
            <input type="number" id="wakafInput"
                class="w-full h-12 pl-3 pr-4 border rounded-lg font-normal focus:outline-none placeholder:text-sm placeholder:font-normal"
                placeholder="Masukkan Nominal" 
                min="0" 
                onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))"
                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
            <div class="font-thin text-justify text-[#757575] flex flex-col gap-2 text-xs py-2 pb-2">
                <div>Jariah perorang merupakan jariah periodik, jika nominal yang dimasukkan kurang dari nominal yang
                    dianjurkan
                    maka
                    akan dinyatakan jariah permanent melalui uang.</div>

                <div><span class="font-bold">Jariah Periodik</span> adalah jariah yang akan dikembalikan 100% setelah
                    kelulusan/3
                    tahun.</div>
                <div><span class="font-bold">Jariah Permanent</span> adalah jariah yang tidak bisa dikembalikan</div>
            </div>
            Bulanan (SPP/Infaq)
            <input type="number" id="sppInput"
                class="w-full h-12 pl-3 pr-4 border rounded-lg font-normal focus:outline-none placeholder:text-sm placeholder:font-normal"
                placeholder="Masukkan Nominal"
                min="0" 
                onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))"
                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
        </div>
        <button id="unggulanPaymentBtn" class="w-full h-10 mt-4 bg-[#51C2FF] rounded-lg text-white cursor-pointer text-sm font-normal shadow-xl">
            Proses Pembayaran
        </button>
        <div class="text-[#757575] text-xs font-normal flex flex-col items-center justify-center text-center  p-2">
            <div>
                <img src="{{ asset('assets/svg/notif-message.svg') }}" alt="" class="mx-auto">
            </div>
            <div class="mt-1">
                Biaya registrasi yang sudah dibayarkan tidak bisa kembali
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ini untuk menghapus book vee container sementara
            const bookVeeContainer = document.getElementById('bookVeeContainer');
            bookVeeContainer.classList.add('hidden')
            async function fetchPengajuanBiaya() {
                try {
                    const response = await AwaitFetchApi('user/pengajuan-biaya', 'GET');
                    print.log('API Response - pengajuan biaya:', response);
                    
                    if (response && response.data && response.data.jurusan) {
                        const jurusan = response.data.jurusan.toLowerCase();
                        
                        if (jurusan === 'reguler') {
                            const mediaResponse = await AwaitFetchApi('user/media/pengajuan-biaya', 'GET');
                            const regulerSection = document.getElementById('regulerSection');
                            const unggulanSection = document.getElementById('unggulanSection');
                            
                            if (regulerSection) regulerSection.classList.remove('hidden');
                            if (unggulanSection) unggulanSection.classList.add('hidden');
                            
                            // Update total amount with nominal from API response
                            if (response.data && response.data.nominal) {
                                const totalAmount = document.getElementById('totalAmount');
                                if (totalAmount) {
                                    const formattedAmount = new Intl.NumberFormat('id-ID').format(response.data.nominal);
                                    totalAmount.textContent = `Rp${formattedAmount}`;
                                }
                            }
                            
                            // Display the image for reguler students
                            if (mediaResponse && mediaResponse.data && mediaResponse.data.length > 0) {
                                const imageUrl = mediaResponse.data[0].url;
                                const imageContainer = document.getElementById('regulerImageContainer');
                                if (imageContainer && imageUrl) {
                                    const img = document.createElement('img');
                                    img.src = imageUrl;
                                    img.className = 'w-full rounded-lg';
                                    img.alt = 'Pengajuan Biaya';
                                    imageContainer.appendChild(img);
                                }
                            }
                        } else if (jurusan === 'unggulan') {
                            const regulerSection = document.getElementById('regulerSection');
                            const unggulanSection = document.getElementById('unggulanSection');
                            
                            if (regulerSection) regulerSection.classList.add('hidden');
                            if (unggulanSection) unggulanSection.classList.remove('hidden');
                            
                            // Update booking fee text with amount from API
                            if (response.data) {
                                const bookVeeText = document.getElementById('bookVeeText');
                                if (bookVeeText) {
                                    if (response.data.nominal) {
                                        const formattedAmount = new Intl.NumberFormat('id-ID').format(response.data.nominal);
                                        bookVeeText.textContent = `Booking Vee Rp${formattedAmount}`;
                                    } else {
                                        bookVeeText.textContent = `Booking Vee Sudah Dibayar`;
                                    }
                                }
                            }
                            
                            // Check if user has already paid for Book Vee
                            if (response.meta && response.meta.message === "peserta sudah membayar book vee") {
                                // Hide the Book Vee button and update the text
                                const bookVeeBtn = document.getElementById('bookVeeBtn');
                                if (bookVeeBtn) {
                                    bookVeeBtn.classList.add('hidden');
                                }
                                // Optionally update the text to indicate payment is complete
                                const bookVeeContainer = document.getElementById('bookVeeContainer');
                                if (bookVeeContainer) {
                                    if (response.data && response.data.nominal) {
                                        const formattedAmount = new Intl.NumberFormat('id-ID').format(response.data.nominal);
                                        bookVeeContainer.innerHTML = `<span>Booking Vee Rp${formattedAmount} (Sudah Dibayar)</span>`;
                                    } else {
                                        bookVeeContainer.innerHTML = `<span>Booking Vee Sudah Dibayar</span>`;
                                    }
                                }
                            }
                        }
                    }
                } catch (error) {
                    print.error('Error fetching pengajuan biaya data:', error);
                }
            }
            
            // Set up event listeners for payment buttons
            const regulerPaymentBtn = document.getElementById('regulerPaymentBtn');
            if (regulerPaymentBtn) {
                
                regulerPaymentBtn.addEventListener('click', async function() {
                    try {
                        // Fetch student data first
                        const studentRes = await AwaitFetchApi('user/peserta', 'GET', null);
                        if (!studentRes || !studentRes.data) {
                            showNotification('Gagal memuat data siswa', 'error');
                            return;
                        }
                        
                        const student = studentRes.data;
                        
                        // Show confirmation dialog with student data
                        Swal.fire({
                            title: 'Konfirmasi Pembayaran',
                            html: `
                                <div class="text-left">
                                    <p class="mb-2"><strong>Nama:</strong> ${student.nama || '-'}</p>
                                    <p class="mb-2"><strong>NISN:</strong> ${student.nisn || '-'}</p>
                                    <p class="mb-2"><strong>Jenjang:</strong> ${student.jenjang_sekolah || '-'}</p>
                                    <p class="mb-2"><strong>Jurusan:</strong> ${student.jurusan1?.jurusan || '-'}</p>
                                </div>
                            `,
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#51C2FF',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ya, Proses Pembayaran',
                            cancelButtonText: 'Batal'
                        }).then(async (result) => {
                            if (result.isConfirmed) {
                                const response = await AwaitFetchApi('user/pengajuan-biaya/reguler', 'PUT');
                                if (response && response.meta && response.meta.code === 200) {
                                    showNotification('Pembayaran reguler berhasil diproses');
                                    window.location.href = "/home";
                                } else {
                                    showNotification(response?.meta?.message || 'Terjadi kesalahan saat memproses pembayaran');
                                }
                            }
                        });
                    } catch (error) {
                        print.error('Error processing reguler payment:', error);
                        showNotification('Terjadi kesalahan saat memproses pembayaran', 'error');
                    }
                });
            }
            
            const unggulanPaymentBtn = document.getElementById('unggulanPaymentBtn');
            if (unggulanPaymentBtn) {
                unggulanPaymentBtn.addEventListener('click', async function() {
                    try {
                        const wakafInput = document.getElementById('wakafInput');
                        const sppInput = document.getElementById('sppInput');
                        
                        if (!wakafInput || !wakafInput.value) {
                            showNotification('Mohon isi nominal wakaf', 'error');
                            return;
                        }
                        
                        if (!sppInput || !sppInput.value) {
                            showNotification('Mohon isi nominal SPP/Infaq', 'error');
                            return;
                        }
                        
                        const wakafNominal = wakafInput.value;
                        const sppNominal = sppInput.value;
                        
                        // Fetch student data
                        const studentRes = await AwaitFetchApi('user/peserta', 'GET', null);
                        if (!studentRes || !studentRes.data) {
                            showNotification('Gagal memuat data siswa', 'error');
                            return;
                        }
                        
                        const student = studentRes.data;
                        const totalNominal = parseInt(wakafNominal) + parseInt(sppNominal);
                        
                        // Format numbers for display
                        const formattedWakaf = new Intl.NumberFormat('id-ID').format(wakafNominal);
                        const formattedSpp = new Intl.NumberFormat('id-ID').format(sppNominal);
                        const formattedTotal = new Intl.NumberFormat('id-ID').format(totalNominal);
                        
                        // Show confirmation dialog with student data
                        Swal.fire({
                            title: 'Konfirmasi Pembayaran',
                            html: `
                                <div class="text-left">
                                    <p class="mb-2"><strong>Nama:</strong> ${student.nama || '-'}</p>
                                    <p class="mb-2"><strong>NISN:</strong> ${student.nisn || '-'}</p>
                                    <p class="mb-2"><strong>Jenjang:</strong> ${student.jenjang_sekolah || '-'}</p>
                                    <p class="mb-2"><strong>Jurusan:</strong> ${student.jurusan1?.jurusan || '-'}</p>
                                    <hr class="my-2">
                                    <p class="mb-2"><strong>Wakaf:</strong> Rp${formattedWakaf}</p>
                                    <p class="mb-2"><strong>SPP/Infaq:</strong> Rp${formattedSpp}</p>
                                </div>
                            `,
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#51C2FF',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ya, Proses Pembayaran',
                            cancelButtonText: 'Batal'
                        }).then(async (result) => {
                            if (result.isConfirmed) {
                                // Process wakaf payment
                                const wakafResponse = await AwaitFetchApi('user/pengajuan-biaya/wakaf', 'PUT', {
                                    wakaf: parseInt(wakafNominal)
                                });
                                
                                if (!wakafResponse || wakafResponse.meta.code !== 200) {
                                    showNotification(wakafResponse?.meta?.message || 'Terjadi kesalahan saat memproses wakaf', 'error');
                                    return;
                                }
                                
                                // Process SPP payment
                                const sppResponse = await AwaitFetchApi('user/pengajuan-biaya/spp', 'PUT', {
                                    spp: parseInt(sppNominal)
                                });
                                
                                if (sppResponse && sppResponse.meta.code === 200) {
                                    showNotification('Pembayaran unggulan berhasil diproses');
                                    window.location.href = "/home";
                                } else {
                                    showNotification(sppResponse?.meta?.message || 'Terjadi kesalahan saat memproses SPP/Infaq', 'error');
                                }
                            }
                        });
                    } catch (error) {
                        print.error('Error processing unggulan payment:', error);
                        showNotification('Terjadi kesalahan saat memproses pembayaran', 'error');
                    }
                });
            }
            
            const bookVeeBtn = document.getElementById('bookVeeBtn');
            if (bookVeeBtn) {
                bookVeeBtn.classList.add('hidden');
                bookVeeBtn.addEventListener('click', async function() {
                    try {
                        const response = await AwaitFetchApi('user/pengajuan-biaya/book-vee', 'PUT');
                        
                        if (response && response.meta && response.meta.code === 200) {
                            if (response.data && response.data.qr_data) {
                                // Show QR modal with the QR data
                                showQRModal(response.data.qr_data, response.data.va_number, true);
                            } else {
                                showNotification('Booking Vee berhasil diproses');
                            }
                        } else {
                            showNotification(response?.meta?.message || 'Terjadi kesalahan saat memproses Booking Vee');
                        }
                    } catch (error) {
                        print.error('Error processing book vee:', error);
                        showNotification('Terjadi kesalahan saat memproses Booking Vee');
                    }
                });
            }
            
            fetchPengajuanBiaya();
        });
    </script>
@endsection
