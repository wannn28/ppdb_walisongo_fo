@extends('layouts.started')
@section('started')
    <div class="p-4 space-y-4 bg-[#F8F8F8] min-h-screen font-semibold pt-16">
        <div class="text-2xl flex w-full justify-center text-[#048FBD] font-bold">Daftar Akun</div>

        <div class="text-xs">
            Nama Siswa
            <input type="text" class="w-full h-8 pl-3 pr-4 border rounded-lg focus:outline-none" id="nama">
        </div>

        <div class="text-xs">
            Nomor HP
            <div class="flex items-center border rounded-lg overflow-hidden">
                <div class="bg-gray-100 px-2 py-1 text-gray-700">+62</div>
                <input type="tel" pattern="[0-9]*" inputmode="numeric" class="w-full h-8 pl-2 pr-4 focus:outline-none" id="no_telp" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
            </div>
        </div>

        <div class="text-xs">
            Jenis Kelamin
            <select id="jenis_kelamin" class="w-full h-8 pl-2 pr-4 border rounded-lg focus:outline-none">
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
            </select>
        </div>

        <div class="text-xs">
            Pilihan Sekolah
            <select id="jenjang_sekolah" class="w-full h-8 pl-2 pr-4 border rounded-lg focus:outline-none">
                <option value="SMA">SMA</option>
                <option value="SMK">SMK</option>
                <option value="SMP">SMP</option>
                {{-- <option value="SMP 2">SMP 2</option> --}}
                {{-- <option value="SD">SD</option> --}}
            </select>
        </div>

        <button id="btn-daftar" class="w-full h-10 bg-[#51C2FF] rounded-lg text-white cursor-pointer">Daftar</button>

        <div class="flex text-xs justify-center">
            Sudah Punya Akun ?
            <a href="login" class="text-[#048FBD] pl-1 cursor-pointer"> Login</a>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // showQRModal('https://payment-link.com');
            const daftarBtn = document.getElementById('btn-daftar');
            const phoneInput = document.getElementById('no_telp');
            
            // Format phone number to remove leading zero
            phoneInput.addEventListener('input', function() {
                if (this.value.startsWith('0')) {
                    this.value = this.value.substring(1);
                }
            });
            
            // Additional input validation for phone number
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
            
            // Tampilkan modal QRIS
            daftarBtn.addEventListener('click', async () => {
                const nama = document.getElementById('nama').value.trim();
                const no_telp = phoneInput.value.trim();
                const jenis_kelamin = document.getElementById('jenis_kelamin').value;
                const jenjang_sekolah = document.getElementById('jenjang_sekolah').value;
               
                // Validate input
                if (!nama) {
                    showNotification("Nama siswa wajib diisi!", "error");
                    return;
                }
                
                if (!no_telp) {
                    showNotification("Nomor HP wajib diisi!", "error");
                    return;
                }
            

                const data = {
                    nama,
                    no_telp: `62${no_telp}`, // Remove the + and just use 62 prefix
                    jenis_kelamin,
                    jenjang_sekolah
                };

                try {
                    const response = await buttonAPI('auth/register', 'POST', data, true, daftarBtn, 'Mendaftar...');
                    
                    if (response.meta?.code === 201) {
                        showNotification("Registrasi berhasil! Silahkan lakukan pembayaran", "success");
                        if (response.data && response.data.qr_data) {
                            showQRModal(response.data.qr_data);  // Tampilkan modal QR
                        } else {
                            // Jika tidak ada QR data, tampilkan pesan
                            showNotification("QR Code untuk pembayaran tidak tersedia. Silahkan hubungi admin.", "warning");
                        }
                    } else if (response.meta?.code === 200) {
                        // Handling for 200 status but with error message
                        showNotification(response.meta.message || "Registrasi gagal", "warning");
                    }
                } catch (error) {
                    console.error("Register error:", error);
                    showNotification("Terjadi kesalahan pada server. Silahkan coba lagi nanti atau hubungi admin.", "error");
                }
            });
        });
    </script>
@endsection
