function showLoading() {
    const loading = document.getElementById('global-loading');
    if (loading) loading.classList.remove('hidden');
}

function hideLoading() {
    const loading = document.getElementById('global-loading');
    if (loading) loading.classList.add('hidden');
}

/**
 * Disables a button, shows loading state, and returns a function to restore it
 * @param {HTMLButtonElement} button - The button element to disable
 * @param {string} loadingText - Optional text to show while loading
 * @returns {Function} Function to restore the button to its original state
 */
function disableButton(button, loadingText = 'Loading...') {
    if (!button || !(button instanceof HTMLElement)) return () => { };

    // Store original state
    const originalText = button.textContent || button.innerText;
    const originalDisabled = button.disabled;
    const originalCursor = button.style.cursor;
    const originalOpacity = button.style.opacity;

    // Disable button and show loading state
    button.disabled = true;
    button.style.cursor = 'wait';
    button.style.opacity = '0.7';

    // Add loading text if requested
    if (loadingText) {
        button.textContent = loadingText;
    }

    // Return function to restore the button
    return function enableButton() {
        button.disabled = originalDisabled;
        button.style.cursor = originalCursor || '';
        button.style.opacity = originalOpacity || '';

        if (loadingText) {
            button.textContent = originalText;
        }
    };
}

/**
 * Makes an API call with automatic button disabling/enabling
 * @param {string} url - API endpoint to call
 * @param {string} method - HTTP method (GET, POST, PUT, DELETE)
 * @param {Object|FormData} data - Data to send
 * @param {boolean} skipAuth - Whether to skip auth token
 * @param {HTMLButtonElement} button - Button to disable during the call
 * @param {string} loadingText - Optional text to show on button while loading
 * @returns {Promise<Object>} API response
 */
async function buttonAPI(url, method, data, skipAuth = false, button = null, loadingText = 'Loading...') {
    // Disable button if provided
    const restoreButton = button ? disableButton(button, loadingText) : null;
    
    try {
        // Make the API call
        const response = await AwaitFetchApi(url, method, data, skipAuth);
        
        // Return the response
        return response;
    } catch (error) {
        // Rethrow the error
        throw error;
    } finally {
        // Always restore the button
        if (restoreButton) {
            restoreButton();
        }
    }
}

/**
 * Core API fetch function - now without button handling which is done by buttonAPI
 */
async function AwaitFetchApi(url, method, data, skipAuth = false) {
    const token = localStorage.getItem('token');
    const BASE_URL = window.API_BASE_URL 
    
    if (!skipAuth && !token) {
        print.warn("Token tidak ditemukan di localStorage.");
        return Promise.resolve({ message: 'Token tidak ditemukan' });
    }

    const isFormData = data instanceof FormData;

    const headers = {
        'Accept': 'application/json',
        ...(isFormData ? {} : { 'Content-Type': 'application/json' })
    };

    if (!skipAuth) {
        headers['Authorization'] = `Bearer ${token}`;
    }

    const options = {
        method: method,
        headers: headers
    };

    if (method !== 'GET' && method !== 'HEAD') {
        options.body = isFormData ? data : JSON.stringify(data);
    }

    showLoading();

    let timeoutReached = false;
    const timeout = setTimeout(() => {
        timeoutReached = true;
        hideLoading();
        showNotification("Permintaan melebihi waktu tunggu. Silakan coba lagi.", "error");
    }, 30000);

    try {
        const response = await fetch(BASE_URL + url, options);
        clearTimeout(timeout);
        if (timeoutReached) {
            return { message: 'Timeout' };
        }
        hideLoading();

        const result = await response.json();
        print.log("Fetch result:", result);
        
        // Tambahkan kondisi pengecekan method
        if (['POST', 'PUT'].includes(method) && result.meta?.message) {
            if (response.ok) {
                
                showNotification(result.meta.message, 'info' );
            }
        }

        if (!response.ok) {
            if (response.status === 422 && result.errors) {
                const allErrors = Object.values(result.errors)
                    .flat()
                    .join('<br>');
                
                // Tampilkan error hanya untuk POST/PUT
                if (['POST', 'PUT'].includes(method)) {
                    showNotification(allErrors, 'error');
                }
            } else if (response.status === 401 && !skipAuth) {
                print.error('Unauthenticated. Redirecting to login...');
                if (['POST', 'PUT'].includes(method)) {
                    showNotification("Sesi Anda telah berakhir. Silakan login kembali.", "error");
                }
                window.location.href = '/login';
            } else if (result.meta?.message && ['POST', 'PUT'].includes(method)) {
                showNotification(result.meta.message, 'error');
            }
        }

        return result;
    } catch (error) {
        clearTimeout(timeout);
        hideLoading();
        
        if (!timeoutReached && ['POST', 'PUT'].includes(method)) {
            showNotification("Terjadi kesalahan jaringan", "error");
        }
        print.error("Fetch error:", error);
        return { message: 'Fetch failed', error };
    }
}

function showNotification(message, type = 'success') {
    Swal.fire({
        icon: type,
        title: message,
        toast: true,
        position: 'top',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        customClass: {
            popup: 'custom-swal',
            title: 'text-sm'
        },
        width: '300px',
        padding: '0.5rem',
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });
}

// Hapus event listener DOMContentLoaded untuk notifikasi
document.addEventListener('DOMContentLoaded', () => {
    const pendingNotification = localStorage.getItem('pendingNotification');
    if (pendingNotification) {
        const { message, type } = JSON.parse(pendingNotification);
        showNotification(message, type);
    }
});

