<nav class="container-bottom-navigation fixed bottom-0 w-full max-w-sm bg-white">
    <div class="grid grid-cols-4 gap-4 py-4">
        <!-- Home -->
        <a href="{{ route('home') }}"
            class="text-center @if (request()->routeIs('home')) text-ppdb-green @else text-gray-500 @endif">
            <div class="nav-home flex flex-col items-center">
                @if (request()->routeIs('home'))
                    <img src="{{ asset('assets/svg/nav-home-actived.svg') }}" alt="Home">
                @else
                    <img src="{{ asset('assets/svg/nav-home.svg') }}" alt="Home">
                @endif
            </div>
        </a>

        <!-- Jadwal -->
        <a href="{{ route('jadwal') }}"
            class="text-center @if (request()->routeIs('jadwal')) text-ppdb-green @else text-gray-500 @endif">
            <div class="flex flex-col items-center">
                @if (request()->routeIs('jadwal'))
                    <img src="{{ asset('assets/svg/nav-jadwal-actived.svg') }}" alt="Jadwal">
                @else
                    <img src="{{ asset('assets/svg/nav-jadwal.svg') }}" alt="Jadwal">
                @endif
            </div>
        </a>

        <!-- Berita -->
        <a href="{{ route('berita') }}"
            class="text-center @if (request()->routeIs('berita')) text-ppdb-green @else text-gray-500 @endif">
            <div class="flex flex-col items-center">
                @if (request()->routeIs('berita'))
                    <img src="{{ asset('assets/svg/nav-berita-actived.svg') }}" alt="Berita">
                @else
                    <img src="{{ asset('assets/svg/nav-berita.svg') }}" alt="Berita">
                @endif
            </div>
        </a>


        <a href="javascript:void(0)" 
            id="logout-button"
            class="text-center text-gray-500">
            <div class="flex flex-col items-center">
                <img src="{{ asset('assets/svg/nav-logout.svg') }}" alt="Logout">
                {{-- <span class="text-xs mt-1">Logout</span> --}}
            </div>
        </a>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const logoutButton = document.getElementById('logout-button');
        
        if (logoutButton) {
            logoutButton.addEventListener('click', function() {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah Anda yakin ingin keluar?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Keluar',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Clear localStorage
                        localStorage.removeItem('token');
                        localStorage.removeItem('cachedBerita');
                        localStorage.removeItem('cachedBeritaTimestamp');
                        localStorage.removeItem('cachedJadwal');
                        localStorage.removeItem('cachedJadwalTimestamp');
                        // Show success notification
                        showNotification('Berhasil logout', 'success');
                        
                        // Redirect to login page after a short delay
                        setTimeout(function() {
                            window.location.href = '/login';
                        }, 1000);
                    }
                });
            });
        }
    });
</script>