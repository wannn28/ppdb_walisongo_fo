function generateQRCode(elementId, data, options = {}) {
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

function showQRModal(data) {
    print.log(data);
    qrData = data; // Simpan qr_data untuk digunakan saat cek status
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
    modal.innerHTML = `
    <div class="bg-white p-4 rounded-xl shadow-2xl max-w-md w-full mx-4 my-auto">
        <div class="text-center space-y-4">
            <h2 class="text-xl font-bold text-gray-800">Pembayaran QRIS</h2>
            <div class="p-2 bg-gray-50 rounded-lg">
                <canvas id="qrCanvas" class="mx-auto"></canvas>
            </div>
            <button onclick="downloadQRCode()" 
                class="mt-2 text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Download QR Code
            </button>
            
            <div class="bg-blue-50 p-3 rounded-lg text-left mt-3">
                <button onclick="togglePaymentSteps()" class="w-full flex items-center justify-between font-bold text-blue-800 mb-0">
                    <span>Langkah-Langkah Pembayaran</span>
                    <svg id="paymentStepsArrow" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div id="paymentStepsContent" class="hidden mt-2">
                    <ol class="list-decimal list-inside text-sm text-gray-700 space-y-1">
                        <li>Unduh atau screenshot kode QRIS di atas.</li>
                        <li>Buka aplikasi dompet digital atau mobile banking yang Anda gunakan.</li>
                        <li>Pindai atau unggah kode QRIS melalui aplikasi tersebut.</li>
                        <li>Lakukan pembayaran sesuai dengan nominal yang tertera.</li>
                        <li>Periksa status pembayaran Anda pada halaman ini setelah transaksi selesai.</li>
                    </ol>
                </div>
            </div>
            
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
    document.body.appendChild(modal);

    // Generate QR code
    generateQRCode('qrCanvas', data);
    
    // Ensure modal is scrollable on mobile
    document.body.style.overflow = 'hidden';
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });
}

// Fungsi untuk toggle langkah-langkah pembayaran
function togglePaymentSteps() {
    const content = document.getElementById('paymentStepsContent');
    const arrow = document.getElementById('paymentStepsArrow');
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        arrow.classList.add('rotate-180');
    } else {
        content.classList.add('hidden');
        arrow.classList.remove('rotate-180');
    }
}

async function checkPaymentStatus() {
    try {
        if (!qrData) {
            showNotification('Data QR tidak valid', 'error');
            return;
        }

        const result = await AwaitFetchApi('check-status', 'POST', { qr_data: qrData }, true);

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