@extends('layouts.app')

@section('content')
    <div>
        <div class="text-xl flex w-full justify-center font-bold">Daftar Peserta</div>
        <div class="w-full justify-center font-medium flex text-[12px] text-[#757575]">Berikut adalah daftar peserta penerimaan siswa</div>
    </div>
    <div>
        <table class="w-full border-collapse border border-gray-400 text-xs font-normal text-center">
            <thead class="h-8">
              <tr>
                <th class="border border-gray-300 text-xs font-normal">No</th>
                <th class="border border-gray-300 text-xs font-normal">Nama Siswa</th>
                {{-- <th class="border border-gray-300 text-xs font-normal">Uang Wakaf</th>
                <th class="border border-gray-300 text-xs font-normal">Waktu Pembayaran</th> --}}
              </tr>
            </thead>
            <tbody id="peserta-table-body">
              <!-- Data will be populated here -->
            </tbody>
          </table>
    </div>
    {{-- <td class="border border-gray-300 text-xs font-normal">${item.peserta?.wakaf ? item.peserta.wakaf.toLocaleString() : '-'}</td>
    <td class="border border-gray-300 text-xs font-normal">${item.created_at ? new Date(item.created_at).toLocaleString('id-ID') : '-'}</td> --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetchDaftarPeserta();
        });

        async function fetchDaftarPeserta() {
            try {
                const response = await AwaitFetchApi('user/peringkat', 'GET', {});
                if (response && response.data) {
                    const tableBody = document.getElementById('peserta-table-body');
                    tableBody.innerHTML = '';
                    
                    response.data.forEach((item, index) => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="border border-gray-300 text-xs font-normal">${index + 1}</td>
                            <td class="border border-gray-300 text-xs font-normal">${item.peserta?.nama || '-'}</td>
                           
                        `;
                        tableBody.appendChild(row);
                    });
                } else {
                    console.error('Failed to fetch daftar peserta data');
                }
            } catch (error) {
                console.error('Error fetching daftar peserta data:', error);
            }
        }
    </script>
@endsection 