@extends('layouts.started')
@section('started')
    <div class="p-4 space-y-4 bg-[#F8F8F8] min-h-screen font-semibold pt-32">
        <div class="text-2xl flex w-full justify-center text-[#048FBD] font-bold">Login</div>
        <div class="text-xs">
            Nomor HP
            <div class="flex items-center border rounded-lg overflow-hidden">
                <div class="bg-gray-100 px-2 py-1 text-gray-700">+62</div>
                <input id="phoneInput" type="tel" pattern="[0-9]*" inputmode="numeric"
                    class="w-full h-8 pl-2 pr-4 focus:outline-none" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
            </div>
        </div>
        <button id="loginBtn" class="w-full h-10 bg-[#51C2FF] rounded-lg text-white cursor-pointer">Login</button>
        <div class="flex text-xs justify-center">
            Belum Punya Akun ?
            <a href="register" class="text-[#048FBD] pl-1 cursor-pointer">Daftar segera</a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            localStorage.removeItem('token');
            localStorage.removeItem('cachedBerita');
            localStorage.removeItem('cachedBeritaTimestamp');
            localStorage.removeItem('cachedJadwal');
            localStorage.removeItem('cachedJadwalTimestamp');
            const loginBtn = document.getElementById('loginBtn');
            const phoneInput = document.getElementById('phoneInput');

            // Format phone number to remove leading zero
            phoneInput.addEventListener('input', function() {
                if (this.value.startsWith('0')) {
                    this.value = this.value.substring(1);
                }
            });

            // Kosongkan token saat membuka halaman login
            localStorage.setItem('token', '');

            // Additional input validation
            phoneInput.addEventListener('keypress', function(e) {
                // Allow only number keys (0-9)
                const charCode = e.which ? e.which : e.keyCode;
                if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                    e.preventDefault();
                    return false;
                }
            });

            // Also validate on paste events
            phoneInput.addEventListener('paste', function(e) {
                // Get pasted data
                let pastedData = (e.clipboardData || window.clipboardData).getData('text');
                if (!/^\d+$/.test(pastedData)) {
                    // If pasted data contains non-numeric characters, prevent the paste
                    e.preventDefault();
                    return false;
                }
            });

            loginBtn.addEventListener('click', async () => {
                const no_telp = phoneInput.value;

                if (!no_telp) {
                    showNotification("Nomor HP wajib diisi!", "error");
                    return;
                }

                const response = await buttonAPI('auth/login', 'POST', {
                    no_telp: `62${no_telp}`
                }, true, loginBtn, 'Masuk...');
                if (response && response.meta?.code === 200) {
                    if (response.data.token && response.data.token !== '') {
                        showNotification("Login Berhasil!", "success");
                        localStorage.setItem('token', response.data.token); // notifikasi tersimpan
                        window.location.href = '/home';
                    } else if (response.meta.message === "Harap Membayar biaya pendaftaran akun" &&
                        response.data.qr_data) {
                        // Tampilkan notifikasi dan modal QR untuk pembayaran
                        showNotification(
                            "Harap selesaikan pembayaran biaya pendaftaran terlebih dahulu",
                            "warning");
                        showQRModal(response.data.qr_data);
                    }
                }
            });
        });
    </script>
@endsection
