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
            <div class="w-1/3">
                <img id="profile-img" class="w-full rounded-md" src="{{ asset('assets/img/profile_default.png') }}"
                    alt="Profile">
            </div>
            <div class="flex flex-col justify-center w-3/3 pl-2">
                <!-- Elemen untuk diupdate dengan data peserta -->
                <p id="nama" class="font-semibold text-sm"></p>
                <p id="nisn" class="text-xs"></p>
                <p id="role" class="font-bold text-xs"></p>
            </div>
        </div>
        <div class="text-xs px-4 pb-2 font-normal">
            <div class="flex justify-between">
                <span id="jenis_kelamin">Jenis Kelamin : </span>
                {{-- <span id="jurusan1">Program : IPA</span> --}}
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
        <div class="grid grid-cols-4 py-4 gap-8">
            <a id="data-siswa-link" href="#"
                class="text-center @if (request()->routeIs('data-siswa')) text-ppdb-green @else text-gray-500 @endif">
                <div class="flex flex-col items-center">
                    <img src="{{ asset('assets/svg/Icon Data Siswa.svg') }}" alt="data siswa">
                </div>
            </a>
            <a href="{{ route('peringkat') }}" class="text-center text-gray-500">
                <div class="flex flex-col items-center">
                    <img src="{{ asset('assets/svg/Icon Peringkat.svg') }}" alt="Jadwal">
                </div>
            </a>
            <a href="{{ route('riwayat') }}" class="text-center text-gray-500">
                <div class="flex flex-col items-center">
                    <img src="{{ asset('assets/svg/Icon Riwayat.svg') }}" alt="Berita">
                </div>
            </a>
            <a href="{{ route('pesan') }}" class="text-center relative">
                <div class="flex flex-col items-center">
                    <span id="navbar-unread-count"
                        class="absolute -top-2 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold z-10 hidden"></span>
                    <img src="{{ asset('assets/svg/Icon Pesan.svg') }}" alt="Account">
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

        <img src="{{ asset('assets/svg/Line-done.svg') }}" alt="Line" class="self-center" id="line1">

        <!-- Langkah 2: Unggah Berkas -->
        <div class="flex flex-col items-center gap-1 text-center" id="step-unggah-berkas">
            <img id="img-unggah-berkas" src="{{ asset('assets/svg/Now Progress 2.svg') }}" alt="Current">
            <span class="text-[10px] font-semibold">Unggah Berkas</span>
        </div>

        <img src="{{ asset('assets/svg/Line-undone.svg') }}" alt="Line" class="self-center" id="line2">

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

            // Panggil API untuk mendapatkan data peserta dan progress
            AwaitFetchApi('user/home', 'GET', null)
                .then((res) => {
                    // print.log(res);
                    if (res && res.data) {
                        // Update data peserta
                        const peserta = res.data.peserta;
                        document.getElementById('nama').innerText = peserta.nama;
                        document.getElementById('nisn').innerText = peserta.nisn ?? 'NISN BELUM DIISI';
                        document.getElementById('role').innerText = "Siswa";
                        document.getElementById('jenis_kelamin').innerText =
                            `Jenis Kelamin : ${peserta.jenis_kelamin}`;
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

                            } else if (progress === "3") {
                                // Sembunyikan container progress steps dan tampilkan payment progress
                                progressStepsContainer.classList.add('hidden');
                                paymentProgressContainer.classList.remove('hidden');

                                // Data dummy untuk progress pembayaran
                                // Dalam implementasi sebenarnya, data ini akan diambil dari backend
                                const totalAmount = 10000000; // 10 juta
                                const amountPaid = 2000000; // 2 juta
                                const percentage = Math.round((amountPaid / totalAmount) * 100);

                                // Update payment progress UI
                                document.getElementById('payment-percentage').innerText = `${percentage}%`;
                                document.getElementById('payment-progress-bar').style.width =
                                    `${percentage}%`;
                                document.getElementById('amount-paid').innerText =
                                    `Rp ${formatRupiah(amountPaid)}`;
                                document.getElementById('total-amount').innerText =
                                    `Rp ${formatRupiah(totalAmount)}`;

                                // Link: Pembayaran
                                document.getElementById('link-unggah').setAttribute('href', 'tes');
                                document.getElementById('text-link-utama').innerText =
                                    'Lanjutkan Pembayaran';
                                document.getElementById('img-link').src =
                                    '{{ asset('assets/svg/Upload To FTP.svg') }}';
                                document.getElementById('text-link-1').innerText = 'Lanjutkan Pembayaran';
                                document.getElementById('text-link-2').innerText =
                                    'Selesaikan Proses Pembayaran Anda';
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
                        }
                    }
                })
                .catch((error) => {
                    print.error('Error fetching peserta:', error);
                });
        });
    </script>
@endpush
