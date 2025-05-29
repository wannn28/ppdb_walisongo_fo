@extends('layouts.app')

@section('header')
    @include('components.header')
@endsection

@section('navbar')
    @include('components.navbar')
@endsection

@section('content')
    <div class="rounded-xl overflow-hidden text-white font-sans"
        style="background-image: url('{{ asset('assets/svg/background_card_name.svg') }}'); background-size: cover; background-position: center;">
        <div class="flex p-4">
            <div class="w-1/4">
                <img id="profile-img" class="w-full rounded-md" src="{{ asset('assets/img/profile_default.png') }}"
                    alt="Profile">
            </div>
            <div class="flex flex-col justify-center w-3/3 pl-2">
                <!-- Elemen untuk diupdate dengan data peserta -->
                <p id="nama" class="font-semibold text-sm"></p>
                <p id="nis" class="text-xs"></p>
                <p id="role" class="font-bold text-xs"></p>
            </div>
        </div>
        <div class="text-xs px-4 pb-2 font-normal">
            <div class="flex justify-between">
                <span id="jenis_kelamin">Jenis Kelamin : </span>
                <span id="status">Verifikasi Berkas : </span>
            </div>
        </div>
        <div id="jenjang_sekolah" class="bg-transparent text-white text-sm font-bold px-4 pb-4 pt-2">
            <!-- Akan diupdate: misalnya "SMA WALISONGO SEMARANG" -->
        </div>
    </div>

    <!-- Notifikasi Form -->
    <div id="form-notification"
        class="hidden bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-2 mb-4 text-xs rounded" role="alert">
        <p class="font-bold">Perhatian!</p>
        <p>Silahkan isi form pendaftaran terlebih dahulu.</p>
    </div>

    <!-- Konten lainnya tetap sama -->
    <div class="flex w-full max-w-sm justify-center">
        <div id="navbar-grid" class="grid grid-cols-3 py-4 gap-8">
            <a id="data-siswa-link" href="#"
                class="text-center @if (request()->routeIs('data-siswa')) text-ppdb-green @else  @endif">
                <div class="flex flex-col items-center">
                    <img src="{{ asset('assets/svg/Icon Data Siswa.svg') }}" alt="data siswa">
                    <span id="data-siswa-text" class="text-xs mt-1">Data Siswa</span>
                </div>
            </a>
            <a id="peringkat-link" href="{{ route('peringkat') }}" class="text-center  hidden">
                <div class="flex flex-col items-center">
                    <img src="{{ asset('assets/svg/Icon Peringkat.svg') }}" alt="Jadwal">
                       <span id="peringkat-text" class="text-xs mt-1">Peringkat</span>
                </div>
            </a>
            <a href="{{ route('riwayat') }}" class="text-center ">
                <div class="flex flex-col items-center">
                    <img src="{{ asset('assets/svg/Icon Riwayat.svg') }}" alt="Berita">
                    <span id="riwayat-text" class="text-xs mt-1">Riwayat</span>
                </div>
            </a>
            <a href="{{ route('pesan') }}" class="text-center relative">
                <div class="flex flex-col items-center">
                    <span id="navbar-unread-count"
                        class="absolute -top-2 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold z-10 hidden"></span>
                    <img src="{{ asset('assets/svg/Icon Pesan.svg') }}" alt="Account">
                    <span id="pesan-text" class="text-xs mt-1">Pesan</span>
                </div>
            </a>
        </div>
    </div>

    <!-- Tampilan progress -->
    <div class="flex w-full max-w-sm items-center justify-center" id="progress-steps-container">
        <!-- Langkah 1: Isi Form -->
        <div class="flex flex-col items-center gap-1 text-center" id="step-isi-form">
            <img id="img-isi-form" src="{{ asset('assets/svg/done-progress-icon.svg') }}" alt="Done">
            <span class="text-[10px] font-semibold">Isi Form</span>
        </div>

        <img src="{{ asset('assets/svg/Line-done.svg') }}" alt="Line" class="self-center -mt-8" id="line1">

        <!-- Langkah 2: Unggah Berkas -->
        <div class="flex flex-col items-center gap-1 text-center" id="step-unggah-berkas">
            <img id="img-unggah-berkas" src="{{ asset('assets/svg/Now Progress 2.svg') }}" alt="Current">
            <span class="text-[10px] font-semibold">Unggah Berkas</span>
        </div>

        <img src="{{ asset('assets/svg/Line-undone.svg') }}" alt="Line" class="self-center -mt-8" id="line2">

        <!-- Langkah 3: Pengajuan Biaya -->
        <div class="flex flex-col items-center gap-1 text-center" id="step-pengajuan-biaya">
            <img id="img-pengajuan-biaya" src="{{ asset('assets/svg/before-progress-icon-3.svg') }}" alt="Undone">
            <span class="text-[10px]">Pengajuan Biaya</span>
        </div>
    </div>

    <!-- Payment Progress Display - Hidden by default -->
    <div id="payment-progress-container" class="hidden flex flex-col w-full gap-4 mt-2 mb-4">
        <div class="flex justify-between items-center">
            <div class="text-sm font-semibold">Progress Pembayaran</div>
            <div id="payment-percentage" class="text-sm font-bold text-[#0267B2]">0%</div>
        </div>

        <div class="relative w-full h-4 bg-gray-200 rounded-full overflow-hidden">
            <div id="payment-progress-bar" class="absolute top-0 left-0 h-full bg-[#0267B2] rounded-full" style="width: 0%">
            </div>
        </div>

        <div class="flex justify-between items-center text-xs">
            <div>
                <span class="font-semibold">Total Dibayar:</span>
                <span id="amount-paid" class="font-bold">Rp 0</span>
            </div>
            <div>
                <span class="font-semibold">Total Tagihan:</span>
                <span id="total-amount" class="font-bold">Rp 0</span>
            </div>
        </div>
    </div>

    <!-- Link yang diubah dinamis sesuai progress -->
    <a href="{{ route('unggah-berkas') }}" id="link-unggah">
        <div class="text-xs font-semibold">
            <div class="pb-4" id="text-link-utama">Unggah Berkas</div>
            <div class="w-full bg-[#0267B2] p-2 rounded-lg h-16 flex">
                <img id="img-link" src="{{ asset('assets/svg/Upload To FTP.svg') }}" alt="">
                <div class="flex flex-col justify-center pl-2 text-white">
                    <span id="text-link-1">Unggah Berkas</span>
                    <span id="text-link-2" class="text-[10px]">Upload Dokumen Pendukung</span>
                </div>
            </div>
        </div>
    </a>

    <!-- Payment Modal -->
    <div id="payment-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg p-6 w-11/12 max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Lakukan Pembayaran</h3>
                <button id="close-payment-modal" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div id="payment-error" class="hidden bg-red-100 border-l-4 border-red-500 text-red-700 p-2 mb-4 text-xs rounded">
                <p class="font-bold">Error!</p>
                <p id="payment-error-message">Error message will appear here.</p>
            </div>

            <div id="payment-form-container">
                <form id="payment-form">
                    <input type="hidden" id="payment-type" value="">
                    <div class="mb-5">
                        <div class="flex justify-between items-center mb-2">
                            <label for="payment-amount" class="block text-sm font-medium text-gray-700">Nominal Pembayaran</label>
                            <span class="text-xs bg-blue-50 text-blue-700 px-2 py-1 rounded-full font-medium">Sisa Tagihan: <span id="unpaid-amount">Rp 0</span></span>
                        </div>
                        
                        <div class="relative mt-1 rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input 
                                type="number" 
                                id="payment-amount" 
                                class="w-full pl-12 pr-20 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-ppdb-green focus:border-ppdb-green text-lg" 
                                placeholder="0"
                                min="1"
                                required
                            >
                            <div class="absolute inset-y-0 right-0 flex items-center">
                                <button 
                                    type="button" 
                                    id="max-amount-btn" 
                                    class="h-full inline-flex items-center px-3 border-l border-gray-300 bg-gray-50 text-gray-700 text-sm rounded-r-md hover:bg-gray-100 focus:outline-none transition-colors duration-200"
                                >
                                    Maksimal
                                </button>
                            </div>
                        </div>
                        
                        <p class="text-xs text-gray-500 mt-2">Masukkan nominal pembayaran sesuai kemampuan Anda.</p>
                    </div>
                    
                    <div class="py-2">
                        <button 
                            type="submit" 
                            id="payment-submit-btn"
                            class="w-full bg-ppdb-green hover:bg-green-700 hover:text-white font-bold py-3 px-4 rounded-md focus:outline-none focus:shadow-outline transition duration-150 ease-in-out flex items-center justify-center"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Bayar Sekarang
                        </button>
                    </div>
                </form>
            </div>

            <div id="payment-loading" class="hidden">
                <div class="flex justify-center items-center py-4">
                    <svg class="animate-spin h-8 w-8 text-ppdb-green mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-gray-700">Memproses pembayaran...</span>
                </div>
            </div>

            <div id="payment-success" class="hidden">
                <div class="text-center py-4">
                    <svg class="mx-auto mb-4 w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Pembayaran Berhasil</h3>
                    <p id="payment-success-message" class="text-sm text-gray-600 mb-4">Tagihan telah berhasil dibuat.</p>
                    <button id="close-success" class="bg-ppdb-green hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Variabel URL asset untuk digunakan di JS
            var nowProgress1 = "{{ asset('assets/svg/Now Progress 1.svg') }}";
            var nowProgress2 = "{{ asset('assets/svg/Now Progress 2.svg') }}";
            var nowProgress3 = "{{ asset('assets/svg/Now Progress 3.svg') }}";
            var linedone = "{{ asset('assets/svg/Line-done.svg') }}";
            var linebefore = "{{ asset('assets/svg/Line-undone.svg') }}";
            var doneProgressIcon1 = "{{ asset('assets/svg/done-progress-icon.svg') }}";
            var doneProgressIcon2 = "{{ asset('assets/svg/done-progress-icon-2.svg') }}";
            var doneProgressIcon3 = "{{ asset('assets/svg/done-progress-icon 3.svg') }}";
            var beforeProgressIcon2 = "{{ asset('assets/svg/before-progress-icon-2.svg') }}";
            var beforeProgressIcon3 = "{{ asset('assets/svg/before-progress-icon-3.svg') }}";

            // Fungsi untuk format angka ke format Rupiah
            function formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID').format(angka);
            }

            // Fungsi untuk fetch data jadwal dan berita sekali saja
            async function fetchAndCacheJadwal() {
                try {
                    // Check if cached data exists and is not expired (24 hours)
                    if (!isCacheExpired('cachedJadwal', 'cachedJadwalTimestamp')) {
                        return JSON.parse(localStorage.getItem('cachedJadwal'));
                    }
                    
                    // Fetch fresh data
                    const res = await AwaitFetchApi('user/media/jadwal', 'GET', null);
                    
                    let imageUrls = [];
                    
                    if (res.meta?.code === 200) {
                        if (Array.isArray(res.data) && res.data.length > 0) {
                            imageUrls = res.data.map(item => item.url);
                        }
                    } else {
                        // imageUrls = ['assets/img/jadwal.png'];
                    }
                    
                    if (imageUrls.length === 0) {
                        // imageUrls = ['assets/img/jadwal.png'];
                    }
                    
                    // Cache the data
                    localStorage.setItem('cachedJadwal', JSON.stringify(imageUrls));
                    localStorage.setItem('cachedJadwalTimestamp', new Date().getTime().toString());
                    
                    return imageUrls;
                } catch (error) {
                   
                    return  print.error('Error fetching jadwal:', error);
                }
            }
            
            async function fetchAndCacheBerita() {
                try {
                    // Check if cached data exists and is not expired (24 hours)
                    if (!isCacheExpired('cachedBerita', 'cachedBeritaTimestamp')) {
                        return JSON.parse(localStorage.getItem('cachedBerita'));
                    }
                    
                    // Fetch fresh data
                    const res = await AwaitFetchApi('user/berita', 'GET', null);
                    
                    let imageUrls = [];
                    
                    if (res.meta?.code === 200 && Array.isArray(res.data?.data) && res.data.data.length > 0) {
                        imageUrls = res.data.data.map(item => item.url || item);
                    } else {
                        // imageUrls = ['assets/img/berita.png'];
                    }
                    
                    // Cache the data
                    localStorage.setItem('cachedBerita', JSON.stringify(imageUrls));
                    localStorage.setItem('cachedBeritaTimestamp', new Date().getTime().toString());
                    
                    return imageUrls;
                } catch (error) {
                    return print.error('Error fetching berita:', error);
                }
            }

            // Panggil API untuk mendapatkan data peserta dan progress
            AwaitFetchApi('user/home', 'GET', null)
                .then((res) => {
                    // print.log(res);
                    if (res && res.data) {
                        // Update data peserta
                        const peserta = res.data.peserta;
                        document.getElementById('nama').innerText = peserta.nama;
                        document.getElementById('nis').innerText = peserta.nis ?? 'NIS';
                        document.getElementById('role').innerText = "Siswa";
                        document.getElementById('jenis_kelamin').innerText =
                            `Jenis Kelamin : ${peserta.jenis_kelamin}`;
                        document.getElementById('status').innerText =
                            `Verifikasi Berkas : ${peserta.status ? peserta.status.charAt(0).toUpperCase() + peserta.status.slice(1) : '-'}`;
                        document.getElementById('jenjang_sekolah').innerText =
                            `${peserta.jenjang_sekolah} WALISONGO SEMARANG`;

                        // Update badge jumlah pesan di navbar bawah
                        if (res.data.pesan !== undefined) {
                            const unreadCount = res.data.pesan;
                            const navbarBadge = document.getElementById('navbar-unread-count');

                            if (navbarBadge) {
                                if (unreadCount > 0) {
                                    navbarBadge.textContent = unreadCount;
                                    navbarBadge.classList.remove('hidden');
                                } else {
                                    navbarBadge.classList.add('hidden');
                                }
                            }
                        }

                        // Cek progressUser
                        const dataSiswaLink = document.getElementById('data-siswa-link');
                        const formNotification = document.getElementById('form-notification');
                        const progressStepsContainer = document.getElementById('progress-steps-container');
                        const paymentProgressContainer = document.getElementById('payment-progress-container');

                        // Check jurusan type for peringkat text
                        const isReguler = peserta.jurusan1 && peserta.jurusan1.jurusan === "reguler";
                        const peringkatText = document.getElementById('peringkat-text');
                        if (peringkatText) {
                            peringkatText.innerText = isReguler ? "Peserta" : "Peringkat";
                        }
                        
                        // Update link destination based on jurusan type
                        const peringkatLink = document.getElementById('peringkat-link');
                        if (peringkatLink) {
                            peringkatLink.href = isReguler ? "{{ route('daftar-peserta') }}" : "{{ route('peringkat') }}";
                        }

                        if (res.data.progressUser) {
                            // Jika sudah ada progress, aktifkan link data siswa
                            dataSiswaLink.href = "{{ route('data-siswa') }}";
                            dataSiswaLink.classList.remove('cursor-not-allowed', 'opacity-50');
                            formNotification.classList.add('hidden');

                            const progress = res.data.progressUser.progress;

                            // Logika penentuan tampilan progress bar
                            if (progress === "0") {
                                // Tampilkan container progress steps
                                progressStepsContainer.classList.remove('hidden');
                                paymentProgressContainer.classList.add('hidden');

                                // Jika progressUser null, nonaktifkan link data siswa dan tampilkan notifikasi
                                dataSiswaLink.href = "#";
                                dataSiswaLink.classList.add('cursor-not-allowed', 'opacity-50');
                                formNotification.classList.remove('hidden');

                                // Tambahkan event listener untuk menampilkan pesan saat diklik
                                dataSiswaLink.addEventListener('click', function(e) {
                                    e.preventDefault();
                                    showNotification('Silahkan isi form pendaftaran terlebih dahulu.',
                                        'error');
                                });

                                // Jika progress == null
                                document.getElementById('img-isi-form').src = nowProgress1;
                                document.getElementById('img-unggah-berkas').src = beforeProgressIcon2;
                                document.getElementById('img-pengajuan-biaya').src = beforeProgressIcon3;
                                document.getElementById('line1').src = linebefore;
                                document.getElementById('line2').src = linebefore;

                                // Link: Isi Form Pendaftaran
                                document.getElementById('link-unggah').setAttribute('href',
                                    '{{ route('form-pendaftaran') }}');
                                document.getElementById('text-link-utama').innerText = 'Isi Form';
                                document.getElementById('img-link').src =
                                    '{{ asset('assets/svg/Terms and Conditions.svg') }}';
                                document.getElementById('text-link-1').innerText = 'Isi Form';
                                document.getElementById('text-link-2').innerText = 'Lengkapi Data Pendaftaran';
                                
                                // Hide peringkat link
                                document.getElementById('peringkat-link').classList.add('hidden');
                                
                            } else if (progress === "1") {
                                // Tampilkan container progress steps
                                progressStepsContainer.classList.remove('hidden');
                                paymentProgressContainer.classList.add('hidden');

                                document.getElementById('img-isi-form').src = doneProgressIcon1;
                                document.getElementById('img-unggah-berkas').src = nowProgress2;
                                document.getElementById('img-pengajuan-biaya').src = beforeProgressIcon3;
                                document.getElementById('line1').src = linedone;
                                document.getElementById('line2').src = linebefore;

                                // Link: Unggah Berkas
                                document.getElementById('link-unggah').setAttribute('href',
                                    '{{ route('unggah-berkas') }}');
                                document.getElementById('text-link-utama').innerText = 'Unggah Berkas';
                                document.getElementById('img-link').src =
                                    '{{ asset('assets/svg/Upload To FTP.svg') }}';
                                document.getElementById('text-link-1').innerText = 'Unggah Berkas';
                                document.getElementById('text-link-2').innerText =
                                    'Upload Dokumen Pendukung';
                                    
                                // Hide peringkat link
                                document.getElementById('peringkat-link').classList.add('hidden');

                            } else if (progress === "2") {
                                // Tampilkan container progress steps
                                progressStepsContainer.classList.remove('hidden');
                                paymentProgressContainer.classList.add('hidden');

                                document.getElementById('img-isi-form').src = doneProgressIcon1;
                                document.getElementById('img-unggah-berkas').src = doneProgressIcon2;
                                document.getElementById('img-pengajuan-biaya').src = nowProgress3;
                                document.getElementById('line1').src = linedone;
                                document.getElementById('line2').src = linedone;

                                // Link: Pengajuan Biaya
                                document.getElementById('link-unggah').setAttribute('href',
                                    '{{ route('pengajuan-biaya') }}');
                                document.getElementById('text-link-utama').innerText = 'Pengajuan Biaya';
                                document.getElementById('img-link').src =
                                    '{{ asset('assets/svg/Upload To FTP.svg') }}';
                                document.getElementById('text-link-1').innerText = 'Pengajuan Biaya';
                                document.getElementById('text-link-2').innerText = 'Ajukan Pembiayaan Anda';
                                
                                // Hide peringkat link
                                document.getElementById('peringkat-link').classList.add('hidden');

                            } else if (progress === "3") {
                                // Sembunyikan container progress steps dan tampilkan payment progress
                                progressStepsContainer.classList.add('hidden');
                                paymentProgressContainer.classList.remove('hidden');
                                
                                // Show peringkat link when progress is 3
                                document.getElementById('peringkat-link').classList.remove('hidden');

                                // Change grid to 4 columns when progress is 3
                                document.getElementById('navbar-grid').classList.remove('grid-cols-3');
                                document.getElementById('navbar-grid').classList.add('grid-cols-4');

                                // Fetch payment progress data from API
                                AwaitFetchApi('user/progressPayment', 'GET', null)
                                    .then(response => {
                                        if (response && response.data) {
                                            const paymentData = response.data;
                                            print.log("Payment data:", paymentData); // Debug log
                                            
                                            // Get paid amount
                                            const amountPaid = paymentData.paid;
                                            // Get progress percentage from API
                                            const percentage = paymentData.progress || 0;
                                            
                                            // Calculate total amount based on the specific API response pattern
                                            let totalAmount;
                                            
                                            // The API response shows paid and unpaid are the same amount and progress is 100%
                                            // This means the total should be just the paid amount (not paid+unpaid)
                                            // if (percentage === 100 && paymentData.paid === paymentData.unpaid) {
                                            //     totalAmount = amountPaid; // Total is the same as the paid amount
                                            // } else if (paymentData.unpaid === 0) {
                                            //     // If unpaid is 0, total is just the paid amount
                                            //     totalAmount = amountPaid;
                                            // } else {
                                            //     // If both paid and unpaid have value and they're different, only then add them
                                            //     totalAmount = paymentData.unpaid - amountPaid;
                                            // }
                                            totalAmount = paymentData.unpaid;
                                            // Update payment progress UI
                                            document.getElementById('payment-percentage').innerText = `${percentage}%`;
                                            document.getElementById('payment-progress-bar').style.width = `${percentage}%`;
                                            document.getElementById('amount-paid').innerText = `Rp ${formatRupiah(amountPaid)}`;
                                            document.getElementById('total-amount').innerText = `Rp ${formatRupiah(totalAmount)}`;
                                            
                                            // Change the button appearance when progress is 100%
                                            if (percentage === 100) {
                                                const linkElement = document.getElementById('link-unggah');
                                                
                                                // Replace the link with a celebration UI
                                                linkElement.removeAttribute('href');
                                                linkElement.style.cursor = 'default';
                                                
                                                // Remove all event listeners to prevent any click actions
                                                linkElement.outerHTML = linkElement.outerHTML; // This clones and replaces the element, removing all listeners
                                                
                                                // Re-get the element since we replaced it
                                                const newLinkElement = document.getElementById('link-unggah');
                                                
                                                // Add click prevention
                                                newLinkElement.addEventListener('click', function(e) {
                                                    e.preventDefault();
                                                    e.stopPropagation();
                                                    return false;
                                                });
                                                
                                                // Change the text and icon
                                                document.getElementById('text-link-utama').innerText = 'Pembayaran Selesai';
                                                
                                                // Update the UI with celebration style
                                                const linkContainer = linkElement.querySelector('div.bg-\\[\\#0267B2\\]');
                                                if (linkContainer) {
                                                    linkContainer.className = 'w-full bg-gradient-to-r from-green-500 to-blue-500 p-2 rounded-lg h-16 flex relative overflow-hidden';
                                                    
                                                    // Add confetti effect to the container
                                                    linkContainer.innerHTML = `
                                                        <div class="absolute inset-0">
                                                            <div class="confetti-piece"></div>
                                                            <div class="confetti-piece"></div>
                                                            <div class="confetti-piece"></div>
                                                            <div class="confetti-piece"></div>
                                                            <div class="confetti-piece"></div>
                                                            <div class="confetti-piece"></div>
                                                            <div class="confetti-piece"></div>
                                                        </div>
                                                        <div class="z-10 flex items-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            <div class="flex flex-col justify-center pl-2 text-white">
                                                                <span class="font-bold">Pembayaran Selesai!</span>
                                                                <span class="text-[10px]">Terima kasih atas pembayaran Anda</span>
                                                            </div>
                                                        </div>
                                                    `;
                                                    
                                                    // Add the confetti style to the head
                                                    const style = document.createElement('style');
                                                    style.textContent = `
                                                        .confetti-piece {
                                                            position: absolute;
                                                            width: 10px;
                                                            height: 10px;
                                                            background: #ffd300;
                                                            top: 0;
                                                            opacity: 0;
                                                            animation: makeItRain 3s infinite ease-out;
                                                        }
                                                        .confetti-piece:nth-child(1) {
                                                            left: 7%;
                                                            transform: rotate(15deg);
                                                            background: #9b59b6;
                                                            animation-delay: 0s;
                                                        }
                                                        .confetti-piece:nth-child(2) {
                                                            left: 25%;
                                                            transform: rotate(45deg);
                                                            background: #3498db;
                                                            animation-delay: 0.2s;
                                                        }
                                                        .confetti-piece:nth-child(3) {
                                                            left: 40%;
                                                            transform: rotate(30deg);
                                                            background: #e74c3c;
                                                            animation-delay: 0.4s;
                                                        }
                                                        .confetti-piece:nth-child(4) {
                                                            left: 60%;
                                                            transform: rotate(60deg);
                                                            background: #2ecc71;
                                                            animation-delay: 0.6s;
                                                        }
                                                        .confetti-piece:nth-child(5) {
                                                            left: 75%;
                                                            transform: rotate(15deg);
                                                            background: #f1c40f;
                                                            animation-delay: 0.8s;
                                                        }
                                                        .confetti-piece:nth-child(6) {
                                                            left: 90%;
                                                            transform: rotate(45deg);
                                                            background: #1abc9c;
                                                            animation-delay: 1s;
                                                        }
                                                        .confetti-piece:nth-child(7) {
                                                            left: 20%;
                                                            transform: rotate(30deg);
                                                            background: #e67e22;
                                                            animation-delay: 1.2s;
                                                        }
                                                        @keyframes makeItRain {
                                                            0% {
                                                                opacity: 0;
                                                                transform: translateY(0) scale(0);
                                                            }
                                                            50% {
                                                                opacity: 1;
                                                                transform: translateY(20px) scale(1);
                                                            }
                                                            100% {
                                                                opacity: 0;
                                                                transform: translateY(40px) scale(0.5);
                                                            }
                                                        }
                                                    `;
                                                    document.head.appendChild(style);
                                                }
                                            }
                                        }
                                    })
                                    .catch(error => {
                                        print.error('Error fetching payment data:', error);
                                    });

                                // Link: Pembayaran
                                document.getElementById('link-unggah').setAttribute('href', '#');
                                document.getElementById('text-link-utama').innerText = 'Lakukan Pembayaran';
                                document.getElementById('img-link').src = '{{ asset('assets/svg/Upload To FTP.svg') }}';
                                document.getElementById('text-link-1').innerText = 'Lakukan Pembayaran';
                                document.getElementById('text-link-2').innerText = 'Selesaikan Proses Pembayaran Anda';
                                
                                // Check if status is "diproses", hide payment link and show notification
                                if (peserta.status && peserta.status.toLowerCase() === "diproses" || peserta.status && peserta.status.toLowerCase() === "ditolak") {
                                    // Hide the payment link
                                    document.getElementById('link-unggah').classList.add('pointer-events-none', 'opacity-50');
                                    
                                    // Add warning notification
                                    const warningDiv = document.createElement('div');
                                    warningDiv.className = 'bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-2 mt-4 text-xs rounded';
                                    warningDiv.innerHTML = `
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            <p class="font-bold">Menunggu persetujuan admin</p>
                                        </div>
                                        <p class="ml-6">Mohon tunggu konfirmasi dari admin untuk melakukan pembayaran.</p>
                                    `;
                                    
                                    // Insert warning after the link-unggah element
                                    document.getElementById('link-unggah').after(warningDiv);
                                } else {
                                    // First check if payment progress is already 100%
                                    AwaitFetchApi('user/progressPayment', 'GET', null)
                                        .then(initialResponse => {
                                            if (initialResponse && initialResponse.data && initialResponse.data.progress === 100) {
                                                // If progress is already 100%, directly update the UI to celebration state without adding click handler
                                                const linkElement = document.getElementById('link-unggah');
                                                
                                                // Replace the link with a celebration UI
                                                linkElement.removeAttribute('href');
                                                linkElement.style.cursor = 'default';
                                                
                                                // Remove all event listeners to prevent any click actions
                                                linkElement.outerHTML = linkElement.outerHTML;
                                                
                                                // Re-get the element since we replaced it
                                                const newLinkElement = document.getElementById('link-unggah');
                                                
                                                // Add click prevention
                                                newLinkElement.addEventListener('click', function(e) {
                                                    e.preventDefault();
                                                    e.stopPropagation();
                                                    return false;
                                                });
                                                
                                                // Change the text and icon
                                                document.getElementById('text-link-utama').innerText = 'Pembayaran Selesai';
                                                
                                                // Update the UI with celebration style
                                                const linkContainer = newLinkElement.querySelector('div.bg-\\[\\#0267B2\\]');
                                                if (linkContainer) {
                                                    linkContainer.className = 'w-full bg-gradient-to-r from-green-500 to-blue-500 p-2 rounded-lg h-16 flex relative overflow-hidden';
                                                    
                                                    // Add confetti effect to the container
                                                    linkContainer.innerHTML = `
                                                        <div class="absolute inset-0">
                                                            <div class="confetti-piece"></div>
                                                            <div class="confetti-piece"></div>
                                                            <div class="confetti-piece"></div>
                                                            <div class="confetti-piece"></div>
                                                            <div class="confetti-piece"></div>
                                                            <div class="confetti-piece"></div>
                                                            <div class="confetti-piece"></div>
                                                        </div>
                                                        <div class="z-10 flex items-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            <div class="flex flex-col justify-center pl-2 text-white">
                                                                <span class="font-bold">Pembayaran Selesai!</span>
                                                                <span class="text-[10px]">Terima kasih atas pembayaran Anda</span>
                                                            </div>
                                                        </div>
                                                    `;
                                                    
                                                    // Add the confetti style to the head
                                                    const style = document.createElement('style');
                                                    style.textContent = `
                                                        .confetti-piece {
                                                            position: absolute;
                                                            width: 10px;
                                                            height: 10px;
                                                            background: #ffd300;
                                                            top: 0;
                                                            opacity: 0;
                                                            animation: makeItRain 3s infinite ease-out;
                                                        }
                                                        .confetti-piece:nth-child(1) {
                                                            left: 7%;
                                                            transform: rotate(15deg);
                                                            background: #9b59b6;
                                                            animation-delay: 0s;
                                                        }
                                                        .confetti-piece:nth-child(2) {
                                                            left: 25%;
                                                            transform: rotate(45deg);
                                                            background: #3498db;
                                                            animation-delay: 0.2s;
                                                        }
                                                        .confetti-piece:nth-child(3) {
                                                            left: 40%;
                                                            transform: rotate(30deg);
                                                            background: #e74c3c;
                                                            animation-delay: 0.4s;
                                                        }
                                                        .confetti-piece:nth-child(4) {
                                                            left: 60%;
                                                            transform: rotate(60deg);
                                                            background: #2ecc71;
                                                            animation-delay: 0.6s;
                                                        }
                                                        .confetti-piece:nth-child(5) {
                                                            left: 75%;
                                                            transform: rotate(15deg);
                                                            background: #f1c40f;
                                                            animation-delay: 0.8s;
                                                        }
                                                        .confetti-piece:nth-child(6) {
                                                            left: 90%;
                                                            transform: rotate(45deg);
                                                            background: #1abc9c;
                                                            animation-delay: 1s;
                                                        }
                                                        .confetti-piece:nth-child(7) {
                                                            left: 20%;
                                                            transform: rotate(30deg);
                                                            background: #e67e22;
                                                            animation-delay: 1.2s;
                                                        }
                                                        @keyframes makeItRain {
                                                            0% {
                                                                opacity: 0;
                                                                transform: translateY(0) scale(0);
                                                            }
                                                            50% {
                                                                opacity: 1;
                                                                transform: translateY(20px) scale(1);
                                                            }
                                                            100% {
                                                                opacity: 0;
                                                                transform: translateY(40px) scale(0.5);
                                                            }
                                                        }
                                                    `;
                                                    document.head.appendChild(style);
                                                }
                                            } else {
                                                // Setup payment modal if status is not "diproses" and progress is not 100%
                                                const paymentType = peserta.jurusan1 && peserta.jurusan1.jurusan === "reguler" ? "reguler" : "wakaf";
                                                document.getElementById('payment-type').value = paymentType;
                                                
                                                // Fetch unpaid amount
                                                let unpaidAmount = 0;
                                                
                                                // Add click event to open payment modal only if progress is not 100%
                                                document.getElementById('link-unggah').addEventListener('click', function(e) {
                                                    e.preventDefault();
                                                    
                                                    // Show loading inside modal first
                                                    document.getElementById('payment-modal').classList.remove('hidden');
                                                    document.getElementById('payment-form-container').classList.add('hidden');
                                                    document.getElementById('payment-loading').classList.remove('hidden');
                                                    
                                                    // Fetch the latest payment progress data
                                                    AwaitFetchApi('user/progressPayment', 'GET', null)
                                                        .then(response => {
                                                            document.getElementById('payment-loading').classList.add('hidden');
                                                            document.getElementById('payment-form-container').classList.remove('hidden');
                                                            
                                                            if (response && response.data) {
                                                                const paymentData = response.data;
                                                                unpaidAmount = paymentData.unpaid;
                                                                
                                                                // Update UI with unpaid amount
                                                                document.getElementById('unpaid-amount').textContent = `${formatRupiah(unpaidAmount)}`;
                                                                
                                                                // Set max amount for input
                                                                const paymentInput = document.getElementById('payment-amount');
                                                                paymentInput.setAttribute('max', unpaidAmount);
                                                                
                                                                // If unpaid is 0, show message and disable form
                                                                if (unpaidAmount <= 0) {
                                                                    showError('Tidak ada tagihan yang perlu dibayar');
                                                                    paymentInput.disabled = true;
                                                                    document.querySelector('#payment-form button[type="submit"]').disabled = true;
                                                                    document.getElementById('max-amount-btn').disabled = true;
                                                                    document.getElementById('max-amount-btn').classList.add('opacity-50', 'cursor-not-allowed');
                                                                } else {
                                                                    paymentInput.disabled = false;
                                                                    document.querySelector('#payment-form button[type="submit"]').disabled = false;
                                                                    document.getElementById('max-amount-btn').disabled = false;
                                                                    document.getElementById('max-amount-btn').classList.remove('opacity-50', 'cursor-not-allowed');
                                                                }
                                                            } else {
                                                                showError('Gagal mendapatkan data tagihan');
                                                            }
                                                        })
                                                        .catch(error => {
                                                            document.getElementById('payment-loading').classList.add('hidden');
                                                            document.getElementById('payment-form-container').classList.remove('hidden');
                                                            showError('Terjadi kesalahan saat mengambil data tagihan');
                                                            print.error('Error fetching payment data:', error);
                                                        });
                                                });
                                                
                                                // Set max amount button click handler
                                                document.getElementById('max-amount-btn').addEventListener('click', function() {
                                                    const paymentInput = document.getElementById('payment-amount');
                                                    paymentInput.value = unpaidAmount;
                                                    
                                                    // Add animation to highlight the change
                                                    paymentInput.classList.add('ring-2', 'ring-ppdb-green');
                                                    setTimeout(() => {
                                                        paymentInput.classList.remove('ring-2', 'ring-ppdb-green');
                                                    }, 500);
                                                });
                                            }
                                        })
                                        .catch(error => {
                                            print.error('Error checking payment progress:', error);
                                        });
                                }
                            }
                        } else {
                            // Tampilkan container progress steps
                            progressStepsContainer.classList.remove('hidden');
                            paymentProgressContainer.classList.add('hidden');

                            // Jika progressUser null, nonaktifkan link data siswa dan tampilkan notifikasi
                            dataSiswaLink.href = "#";
                            dataSiswaLink.classList.add('cursor-not-allowed', 'opacity-50');
                            formNotification.classList.remove('hidden');

                            // Tambahkan event listener untuk menampilkan pesan saat diklik
                            dataSiswaLink.addEventListener('click', function(e) {
                                e.preventDefault();
                                showNotification('Silahkan isi form pendaftaran terlebih dahulu.',
                                    'error');
                            });

                            // Jika progress == null
                            document.getElementById('img-isi-form').src = nowProgress1;
                            document.getElementById('img-unggah-berkas').src = beforeProgressIcon2;
                            document.getElementById('img-pengajuan-biaya').src = beforeProgressIcon3;
                            document.getElementById('line1').src = linebefore;
                            document.getElementById('line2').src = linebefore;

                            // Link: Isi Form Pendaftaran
                            document.getElementById('link-unggah').setAttribute('href',
                                '{{ route('form-pendaftaran') }}');
                            document.getElementById('text-link-utama').innerText = 'Isi Form';
                            document.getElementById('img-link').src =
                                '{{ asset('assets/svg/Terms and Conditions.svg') }}';
                            document.getElementById('text-link-1').innerText = 'Isi Form';
                            document.getElementById('text-link-2').innerText = 'Lengkapi Data Pendaftaran';
                            
                            // Hide peringkat link
                            document.getElementById('peringkat-link').classList.add('hidden');
                        }
                    }
                })
                .catch((error) => {
                    print.error('Error fetching peserta:', error);
                });

            // Prefetch jadwal and berita data for caching
            fetchAndCacheJadwal();
            fetchAndCacheBerita();

            // Payment Modal Functionality
            const paymentModal = document.getElementById('payment-modal');
            const closePaymentModal = document.getElementById('close-payment-modal');
            const closeSuccess = document.getElementById('close-success');
            const paymentForm = document.getElementById('payment-form');
            const paymentFormContainer = document.getElementById('payment-form-container');
            const paymentLoading = document.getElementById('payment-loading');
            const paymentSuccess = document.getElementById('payment-success');
            const paymentError = document.getElementById('payment-error');
            const paymentErrorMessage = document.getElementById('payment-error-message');
            const paymentSuccessMessage = document.getElementById('payment-success-message');

            // Close modal when clicking the close button
            if (closePaymentModal) {
                closePaymentModal.addEventListener('click', function() {
                    paymentModal.classList.add('hidden');
                    resetPaymentModal();
                });
            }

            // Close modal when clicking the success close button
            if (closeSuccess) {
                closeSuccess.addEventListener('click', function() {
                    paymentModal.classList.add('hidden');
                    resetPaymentModal();
                    // Refresh the page to update payment status
                    window.location.reload();
                });
            }

            // Close modal when clicking outside the modal content
            if (paymentModal) {
                paymentModal.addEventListener('click', function(e) {
                    if (e.target === paymentModal) {
                        paymentModal.classList.add('hidden');
                        resetPaymentModal();
                    }
                });
            }

            // Handle payment form submission
            if (paymentForm) {
                paymentForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const amount = document.getElementById('payment-amount').value;
                    const paymentType = document.getElementById('payment-type').value;
                    const maxAmount = document.getElementById('payment-amount').getAttribute('max');
                    const submitButton = document.getElementById('payment-submit-btn');
                    
                    if (!amount || amount <= 0) {
                        showError('Nominal pembayaran harus lebih besar dari 0');
                        return;
                    }
                    
                    if (parseInt(amount) > parseInt(maxAmount)) {
                        showError('Nominal pembayaran tidak boleh melebihi sisa tagihan');
                        return;
                    }
                    
                    // Hide error message if any
                    paymentError.classList.add('hidden');
                    
                    // Determine endpoint based on payment type
                    let endpoint;
                    let payloadKey;
                    
                    if (paymentType === 'wakaf') {
                        endpoint = 'user/pengajuan-biaya/bayar/wakaf';
                        payloadKey = 'wakaf';
                    } else {
                        endpoint = 'user/pengajuan-biaya/bayar/reguler';
                        payloadKey = 'pengajuan_biaya';
                    }
                    
                    // Create payload
                    const payload = {};
                    payload[payloadKey] = parseInt(amount);
                    
                    // Submit payment using buttonAPI instead of direct AwaitFetchApi
                    buttonAPI(
                        endpoint, 
                        'POST', 
                        payload, 
                        false, 
                        submitButton, 
                        'Memproses...'
                    )
                    .then(response => {
                        if (response.meta.code === 200) {
                            // Check if response contains QR/VA data
                            if (response.data && (response.data.qr_data || response.data.va_number)) {
                                // Close current modal
                                paymentModal.classList.add('hidden');
                                resetPaymentModal();
                                const dataVa = {
                                    va_number: response.data.va_number,
                                };
                                // Show QR/VA modal using the function from window object
                                window.showPaymentModal(response.data);
                            } else {
                                // Just show success message if no QR/VA data
                                paymentSuccess.classList.remove('hidden');
                                paymentSuccessMessage.textContent = response.meta.message;
                            }
                        } else {
                            // Error handling is done automatically by buttonAPI/AwaitFetchApi
                            paymentFormContainer.classList.remove('hidden');
                        }
                    })
                    .catch(error => {
                        print.error('Payment error:', error);
                    });
                });
            }
            
            // Helper functions
            function showError(message) {
                paymentError.classList.remove('hidden');
                paymentErrorMessage.textContent = message;
            }
            
            function resetPaymentModal() {
                if (paymentForm) paymentForm.reset();
                if (paymentFormContainer) paymentFormContainer.classList.remove('hidden');
                if (paymentLoading) paymentLoading.classList.add('hidden');
                if (paymentSuccess) paymentSuccess.classList.add('hidden');
                if (paymentError) paymentError.classList.add('hidden');
            }
        });
    </script>
@endpush
