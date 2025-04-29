function generateQRCode(elementId, data, options = {}) {
    // Default options
    const defaultOptions = {
        size: 300,
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
        align-items: center;
        z-index: 1000;
    `;

    // Konten modal
    modal.innerHTML = `
    <div class="bg-white p-8 rounded-xl shadow-2xl max-w-md w-full mx-4">
        <div class="text-center space-y-6">
            <h2 class="text-2xl font-bold text-gray-800">Pembayaran QRIS</h2>
            <div class="p-4 bg-gray-50 rounded-lg">
                <canvas id="qrCanvas" class="mx-auto"></canvas>
            </div>
            <button onclick="downloadQRCode()" 
                class="mt-2 text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Download QR Code
            </button>
            
            <div class="bg-blue-50 p-4 rounded-lg text-left mt-4">
                <h3 class="font-bold text-blue-800 mb-2">Langkah-Langkah Pembayaran:</h3>
                <ol class="list-decimal list-inside text-sm text-gray-700 space-y-1.5">
                    <li>Unduh atau screenshot kode QRIS di atas.</li>
                    <li>Buka aplikasi dompet digital atau mobile banking yang Anda gunakan.</li>
                    <li>Pindai atau unggah kode QRIS melalui aplikasi tersebut.</li>
                    <li>Lakukan pembayaran sesuai dengan nominal yang tertera.</li>
                    <li>Periksa status pembayaran Anda pada halaman ini setelah transaksi selesai.</li>
                </ol>
            </div>
            
            <div class="flex flex-col space-y-3 mt-4">
                <button onclick="checkPaymentStatus()" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                    Cek Status Pembayaran
                </button>
                <button onclick="closeModal()" 
                    class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-lg transition duration-200">
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