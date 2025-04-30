@extends('layouts.app')

@section('content')
    <div>
        <div class="text-xl flex w-full justify-center font-bold ">Pengajuan Biaya</div>
        <div class="w-full justify-center font-medium flex text-[12px] text-[#757575]">Detail biaya pendaftaran</div>
    </div>
    <div id="regulerSection" class="hidden">
        <div class="w-full justify-center font-thin flex text-[12px] text-[#757575]">Berikut adalah biaya tambahan untuk
            kategori reguler</div>
        <div class="border-2 border-black p-4 rounded-lg">
            <div class="flex w-full justify-between border-b border-gray-400 font-normal text-xs pb-2 py-2">
                <span>Uang Pendaftaran : </span>
                <span>Rp500.000</span>
            </div>
            <div class="flex w-full justify-between border-b border-gray-400 font-normal text-xs pb-2 py-2">
                <span>Uang Seragam : </span>
                <span>Rp750.000</span>
            </div>
            <div class="flex w-full justify-between border-b border-gray-400 font-normal text-xs pb-2 py-2">
                <span>Uang Buku atau Modul : </span>
                <span>Rp1.000.000</span>
            </div>
            <div class="flex w-full justify-between border-b border-gray-400 font-normal text-xs pb-2 py-2">
                <span>SPP Bulanan : </span>
                <span>Rp300.000</span>
            </div>
            <div class="flex w-full justify-between border-b border-gray-400 font-normal text-xs pb-2 py-2">
                <span>Biaya Kegiatan : </span>
                <span>Rp250.000</span>
            </div>
            <div class="flex w-full justify-between font-bold text-xs pb-2 py-2">
                <span>Total : </span>
                <span>Rp4.300.000</span>
            </div>
        </div>
        <button id="regulerPaymentBtn" class="mt-4 w-full h-10 bg-[#51C2FF] rounded-lg text-white cursor-pointer text-sm font-normal shadow-xl">
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

    <!-- Section Unggulan -->
    <div id="unggulanSection" class="hidden">
        <div class="w-full flex justify-between gap-4 bg-[#51C2FF40] h-16 rounded-lg items-center p-2">
            <span>Booking Vee Rp1.000.000</span>
            <button id="bookVeeBtn"
                class="w-24 flex justify-center text-xs bg-[#51C2FF] text-white p-2 rounded-lg shadow-lg">Booking</button>
        </div>
        <div class="mt-4 flex flex-col">
            Wakaf Perorangan
            <input type="number" id="wakafInput"
                class="w-full h-12 pl-3 pr-4 border rounded-lg font-normal focus:outline-none placeholder:text-sm placeholder:font-normal"
                placeholder="Masukkan Nominal">
            <div class="font-thin text-justify text-[#757575] flex flex-col gap-2 text-xs py-2 pb-2">
                <div>Wakaf perorang merupakan wakaf periodik, jika nominal yang dimasukkan kurang dari nominal yang
                    dianjurkan
                    maka
                    akan dinyatakan wakaf permanent melalui uang.</div>

                <div><span class="font-bold">Wakaf Periodik</span> adalah wakaf yang akan dikembalikan 100% setelah
                    kelulusan/3
                    tahun.</div>
                <div><span class="font-bold">Wakaf Permanent</span> adalah wakaf yang tidak bisa dikembalikan</div>
            </div>
            Bulanan (SPP/Infaq)
            <input type="number" id="sppInput"
                class="w-full h-12 pl-3 pr-4 border rounded-lg font-normal focus:outline-none placeholder:text-sm placeholder:font-normal"
                placeholder="Masukkan Nominal">
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
            async function fetchPengajuanBiaya() {
                try {
                    const response = await AwaitFetchApi('user/pengajuan-biaya', 'GET');
                    console.log('API Response - pengajuan biaya:', response);
                    
                    if (response && response.data && response.data.jurusan) {
                        const jurusan = response.data.jurusan.toLowerCase();
                        
                        if (jurusan === 'reguler') {
                            document.getElementById('regulerSection').classList.remove('hidden');
                            document.getElementById('unggulanSection').classList.add('hidden');
                        } else if (jurusan === 'unggulan') {
                            document.getElementById('regulerSection').classList.add('hidden');
                            document.getElementById('unggulanSection').classList.remove('hidden');
                        }
                    }
                } catch (error) {
                    console.error('Error fetching pengajuan biaya data:', error);
                }
            }
            
            // Set up event listeners for payment buttons
            document.getElementById('regulerPaymentBtn').addEventListener('click', async function() {
                try {
                    const response = await AwaitFetchApi('user/pengajuan-biaya/reguler', 'PUT');
                    if (response && response.meta && response.meta.code === 200) {
                        showNotification('Pembayaran reguler berhasil diproses');
                        window.location.reload();
                    } else {
                        showNotification(response?.meta?.message || 'Terjadi kesalahan saat memproses pembayaran');
                    }
                } catch (error) {
                    console.error('Error processing reguler payment:', error);
                    showNotification('Terjadi kesalahan saat memproses pembayaran');
                }
            });
            
            document.getElementById('unggulanPaymentBtn').addEventListener('click', async function() {
                try {
                    const wakafNominal = document.getElementById('wakafInput').value;
                    const sppNominal = document.getElementById('sppInput').value;
                    
                    if (!wakafNominal) {
                        showNotification('Mohon isi nominal wakaf');
                        return;
                    }
                    
                    if (!sppNominal) {
                        showNotification('Mohon isi nominal SPP/Infaq');
                        return;
                    }
                    
                    // Process wakaf payment
                    const wakafResponse = await AwaitFetchApi('user/pengajuan-biaya/wakaf', 'PUT', {
                        wakaf: parseInt(wakafNominal)
                    });
                    
                    if (!wakafResponse || wakafResponse.meta.code !== 200) {
                        showNotification(wakafResponse?.meta?.message || 'Terjadi kesalahan saat memproses wakaf');
                        return;
                    }
                    
                    // Process SPP payment
                    const sppResponse = await AwaitFetchApi('user/pengajuan-biaya/spp', 'PUT', {
                        spp: parseInt(sppNominal)
                    });
                    
                    if (sppResponse && sppResponse.meta.code === 200) {
                        showNotification('Pembayaran unggulan berhasil diproses');
                        window.location.reload();
                    } else {
                        showNotification(sppResponse?.meta?.message || 'Terjadi kesalahan saat memproses SPP/Infaq');
                    }
                } catch (error) {
                    console.error('Error processing unggulan payment:', error);
                    showNotification('Terjadi kesalahan saat memproses pembayaran');
                }
            });
            
            document.getElementById('bookVeeBtn').addEventListener('click', async function() {
                try {
                    const response = await AwaitFetchApi('user/pengajuan-biaya/book-vee', 'PUT');
                    
                    if (response && response.meta && response.meta.code === 200) {
                        showNotification('Booking Vee berhasil diproses');
                    } else {
                        showNotification(response?.meta?.message || 'Terjadi kesalahan saat memproses Booking Vee');
                    }
                } catch (error) {
                    console.error('Error processing book vee:', error);
                    showNotification('Terjadi kesalahan saat memproses Booking Vee');
                }
            });
            
            fetchPengajuanBiaya();
        });
    </script>
@endsection
