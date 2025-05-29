function generateQRCode(elementId, data, options = {}, vaNumber = null, onlyVa = null) {
    // Default options
    const defaultOptions = {
        size: 250, // Reduced size for better mobile display
        level: 'H', // Error correction level (L, M, Q, H)
        background: '#ffffff',
        foreground: '#000000'
    };

    // Merge default options with custom options
    const finalOptions = { ...defaultOptions, ...options };

    // Create new QR code instance
    const qr = new QRious({
        value: data,
        size: finalOptions.size,
        level: finalOptions.level,
        background: finalOptions.background,
        foreground: finalOptions.foreground
    });

    // Get canvas element
    const canvas = document.getElementById(elementId);
    if (!canvas) {
        print.error('Element with ID ' + elementId + ' not found');
        return;
    }

    // Set canvas dimensions
    canvas.width = finalOptions.size;
    canvas.height = finalOptions.size;

    // Draw QR code to canvas
    const ctx = canvas.getContext('2d');
    ctx.drawImage(qr.canvas, 0, 0);
}

// Fungsi untuk download QR code
function downloadQRCode() {
    const canvas = document.getElementById('qrCanvas');
    if (!canvas) {
        showNotification('Canvas QR code tidak ditemukan', 'error');
        return;
    }

    // Convert canvas to data URL
    const dataURL = canvas.toDataURL('image/png');
    
    // Create temporary link element
    const link = document.createElement('a');
    link.href = dataURL;
    link.download = 'QRIS_Pembayaran.png';
    
    // Append to body, click and remove
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Fungsi untuk menutup modal
function closeModal() {
    const modal = document.getElementById('qrisModal');
    if (modal) {
        modal.remove();
        document.body.style.overflow = '';
    }
}

// Fungsi untuk cek status pembayaran
let qrData = '';
let vaNumberData = '';

function showQRModal(data, vaNumber = null, onlyVa = null, nominalVa = null) {
    print.log(data);
    qrData = data; // Simpan qr_data untuk digunakan saat cek status
    
    if (vaNumber) {
        vaNumberData = vaNumber; // Simpan vaNumber untuk digunakan saat cek status
    }
    
    // Buat elemen modal
    const modal = document.createElement('div');
    modal.id = 'qrisModal';
    modal.style.cssText = `
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        display: flex;
        justify-content: center;
        align-items: flex-start; 
        z-index: 1000;
        overflow-y: auto;
        padding: 20px 0;
    `;

    // Konten modal
    let modalContent = `
    <div class="bg-white p-4 rounded-xl shadow-2xl max-w-md w-full mx-4 my-auto">
        <div class="text-center space-y-4">
            <h2 class="text-xl font-bold text-gray-800">Pembayaran</h2>`;
            
    // Display nominal at the top if available
    if (nominalVa) {
        modalContent += `
            <p class="text-gray-800 font-bold text-lg">Total: Rp ${new Intl.NumberFormat('id-ID').format(nominalVa)}</p>`;
    }

    // Tambahkan tab jika memiliki kedua metode pembayaran dan bukan hanya VA
    if (vaNumber && !onlyVa) {
        modalContent += `
            <div class="flex border-b border-gray-200 mb-4">
                <button id="qrisTab" onclick="switchPaymentTab('qris')" 
                    class="flex-1 py-2 px-4 text-center font-medium border-b-2 border-blue-500 text-blue-600">
                    QRIS
                </button>
                <button id="vaTab" onclick="switchPaymentTab('va')" 
                    class="flex-1 py-2 px-4 text-center font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700">
                    Virtual Account
                </button>
            </div>`;
    } else if (onlyVa) {
        modalContent += `
            <h3 class="font-medium text-gray-700">Virtual Account Payment</h3>`;
    }

    // Konten QRIS
    modalContent += `
            <div id="qrisContent" class="${vaNumber && onlyVa ? 'hidden' : ''}">
                <div class="p-2 bg-gray-50 rounded-lg">
                    <canvas id="qrCanvas" class="mx-auto"></canvas>
                </div>
                ${nominalVa ? `<p class="text-gray-800 font-bold text-lg mt-2">Rp ${new Intl.NumberFormat('id-ID').format(nominalVa)}</p>` : ''}
                <button onclick="downloadQRCode()" 
                    class="mt-2 text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Download QR Code
                </button>
                
                <div class="bg-blue-50 p-3 rounded-lg text-left mt-3">
                    <button onclick="togglePaymentSteps('qris')" class="w-full flex items-center justify-between font-bold text-blue-800 mb-0">
                        <span>Langkah-Langkah Pembayaran QRIS</span>
                        <svg id="qrisStepsArrow" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="qrisStepsContent" class="hidden mt-2">
                        <ol class="list-decimal list-inside text-sm text-gray-700 space-y-1">
                            <li>Unduh atau screenshot kode QRIS di atas.</li>
                            <li>Buka aplikasi dompet digital atau mobile banking yang Anda gunakan.</li>
                            <li>Pindai atau unggah kode QRIS melalui aplikasi tersebut.</li>
                            <li>Lakukan pembayaran sesuai dengan nominal yang tertera.</li>
                            <li>Periksa status pembayaran Anda pada halaman ini setelah transaksi selesai.</li>
                        </ol>
                    </div>
                </div>
            </div>`;

    // Konten VA jika tersedia
    if (vaNumber) {
        modalContent += `
            <div id="vaContent" class="${onlyVa ? '' : 'hidden'}">
                <div class="bg-gray-50 p-4 rounded-lg text-center mb-4">
                    <p class="text-gray-600 text-sm mb-1">Nomor Virtual Account</p>
                    <p class="text-xl font-bold text-gray-800 mb-2">${vaNumber}</p>
                    <button onclick="copyVANumber('${vaNumber}')" 
                        class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center justify-center mx-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                        </svg>
                        Salin Nomor VA
                    </button>
                </div>
                
                <div class="bg-blue-50 p-3 rounded-lg text-left mt-3">
                    <button onclick="togglePaymentSteps('va')" class="w-full flex items-center justify-between font-bold text-blue-800 mb-0">
                        <span>Langkah-Langkah Pembayaran VA</span>
                        <svg id="vaStepsArrow" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="vaStepsContent" class="hidden mt-2">
                        <ol class="list-decimal list-inside text-sm text-gray-700 space-y-1">
                            <li>Lakukan pembayaran melalui Transfer Antar Bank.</li>
                            <li>Pilih bank tujuan BANK MUAMALAT INDONESIA.</li>
                            <li>Masukkan Nomor Rekening berupa Nomor Virtual Account yang muncul di atas.</li>
                            <li>Masukkan nominal sesuai jumlah pembayaran yang harus dilakukan.</li>
                            <li>Klik Kirim atau Lanjutkan Pembayaran.</li>
                            <li>Periksa status pembayaran Anda pada halaman ini setelah transaksi selesai.</li>
                        </ol>
                    </div>
                </div>
            </div>`;
    }

    modalContent += `
            <div class="flex flex-col space-y-2 mt-3 pb-2">
                <button onclick="checkPaymentStatus()" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                    Cek Status Pembayaran
                </button>
                <button onclick="closeModal()" 
                    class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded-lg transition duration-200">
                    Tutup
                </button>
            </div>
        </div>
    </div>
    `;

    // Tambahkan modal ke body
    modal.innerHTML = modalContent;
    document.body.appendChild(modal);

    // Generate QR code jika bukan hanya VA
    if (!onlyVa) {
        generateQRCode('qrCanvas', data);
    }
    
    // Ensure modal is scrollable on mobile
    document.body.style.overflow = 'hidden';
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });
}

// Fungsi untuk toggle langkah-langkah pembayaran
function togglePaymentSteps(tab) {
    const content = document.getElementById(`${tab}StepsContent`);
    const arrow = document.getElementById(`${tab}StepsArrow`);
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        arrow.classList.add('rotate-180');
    } else {
        content.classList.add('hidden');
        arrow.classList.remove('rotate-180');
    }
}

// Fungsi untuk beralih antara tab QRIS dan VA
function switchPaymentTab(tab) {
    // Update tab buttons
    document.getElementById('qrisTab').classList.toggle('border-blue-500', tab === 'qris');
    document.getElementById('qrisTab').classList.toggle('text-blue-600', tab === 'qris');
    document.getElementById('qrisTab').classList.toggle('border-transparent', tab !== 'qris');
    document.getElementById('qrisTab').classList.toggle('text-gray-500', tab !== 'qris');
    
    document.getElementById('vaTab').classList.toggle('border-blue-500', tab === 'va');
    document.getElementById('vaTab').classList.toggle('text-blue-600', tab === 'va');
    document.getElementById('vaTab').classList.toggle('border-transparent', tab !== 'va');
    document.getElementById('vaTab').classList.toggle('text-gray-500', tab !== 'va');
    
    // Show/hide content
    document.getElementById('qrisContent').classList.toggle('hidden', tab !== 'qris');
    document.getElementById('vaContent').classList.toggle('hidden', tab !== 'va');
}

// Fungsi untuk menyalin nomor VA
function copyVANumber(vaNumber) {
    navigator.clipboard.writeText(vaNumber)
        .then(() => {
            showNotification('Nomor VA berhasil disalin', 'success');
        })
        .catch(err => {
            showNotification('Gagal menyalin nomor VA: ' + err, 'error');
        });
}

async function checkPaymentStatus() {
    try {
        let requestData = {};
        
        if (qrData) {
            requestData.qr_data = qrData;
        }
        
        if (vaNumberData) {
            requestData.va_number = vaNumberData;
        }
        
        if (Object.keys(requestData).length === 0) {
            showNotification('Data pembayaran tidak valid', 'error');
            return;
        }

        const result = await AwaitFetchApi('check-status', 'POST', requestData, true);

        if (result.meta?.code === 200) {
            showNotification('Pembayaran berhasil!', 'success');
            closeModal();
            // Add 3-second delay before redirecting to login page
            setTimeout(() => {
                window.location.href = '/login';
            }, 3000);
        } else if (result.meta?.code === 400) {
            showNotification('Pembayaran belum berhasil', 'warning');
        } else {
            showNotification(result.meta?.message || 'Terjadi kesalahan', 'error');
        }
    } catch (error) {
        print.error('Error:', error);
        showNotification('Gagal memeriksa status pembayaran: ' + (error.message || 'Terjadi kesalahan'), 'error');
    }
}

// Fungsi untuk menampilkan modal pembayaran
function showPaymentModal(data) {
    print.log('Payment data:', data);
    
    // Jika data dalam bentuk objek yang berisi qr_data dan va_number
    if (typeof data === 'object') {
        const qrData = data.qr_data || null;
        const vaNumber = data.va_number || null;
        const nominal = data.nominal || null;
        
        // Jika hanya memiliki VA tanpa QRIS
        if (vaNumber && !qrData) {
            showQRModal(null, vaNumber, true, nominal);
        } 
        // Jika memiliki keduanya (QRIS dan VA)
        else if (qrData && vaNumber) {
            showQRModal(qrData, vaNumber, false, nominal);
        }
        // Jika hanya memiliki QRIS tanpa VA
        else if (qrData && !vaNumber) {
            showQRModal(qrData, null, null, nominal);
        }
        else {
            showNotification('Data pembayaran tidak valid', 'error');
        }
    } 
    // Jika data berupa string (diasumsikan sebagai QRIS)
    else if (typeof data === 'string') {
        showQRModal(data);
    }
    else {
        showNotification('Data pembayaran tidak valid', 'error');
    }
}