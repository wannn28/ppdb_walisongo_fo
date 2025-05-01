@extends('layouts.app')

@section('content')
    <div>
        <div class="text-xl flex w-full justify-center font-bold">Peringkat Penerimaan</div>
        <div class="w-full justify-center font-medium flex text-[12px] text-[#757575]">Berikut adalah tabel peringkat penerimaan siswa</div>
    </div>
    <div>
        <table class="w-full border-collapse border border-gray-400 text-xs font-normal text-center">
            <thead class="h-8">
              <tr>
                <th class="border border-gray-300 text-xs font-normal">No</th>
                <th class="border border-gray-300 text-xs font-normal">Nama Siswa</th>
                <th class="border border-gray-300 text-xs font-normal">Uang Wakaf</th>
              </tr>
            </thead>
            <tbody id="peringkat-table-body">
              <!-- Data will be populated here -->
            </tbody>
          </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetchPeringkat();
        });

        async function fetchPeringkat() {
            try {
                const response = await AwaitFetchApi('user/peringkat', 'GET', {});
                if (response && response.data) {
                    const tableBody = document.getElementById('peringkat-table-body');
                    tableBody.innerHTML = '';
                    
                    response.data.forEach((item, index) => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="border border-gray-300 text-xs font-normal">${item.id || '-'}</td>
                            <td class="border border-gray-300 text-xs font-normal">${item.peserta?.nama || '-'}</td>
                            <td class="border border-gray-300 text-xs font-normal">${item.peserta?.wakaf ? item.peserta.wakaf.toLocaleString() : '-'}</td>
                        `;
                        tableBody.appendChild(row);
                    });
                } else {
                    console.error('Failed to fetch peringkat data');
                }
            } catch (error) {
                console.error('Error fetching peringkat data:', error);
            }
        }
    </script>
@endsection
