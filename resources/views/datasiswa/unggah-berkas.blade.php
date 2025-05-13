@extends('layouts.app')

@section('content')
    <div class="text-2xl flex w-full justify-center font-bold mb-4">Unggah Berkas</div>
    
    <!-- Informasi format file -->
    {{-- <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-3 mb-4 text-xs rounded">
        <p class="font-bold">Informasi Format File:</p>
        <ul class="list-disc ml-4 mt-1">
            <li>Format file yang diizinkan: PNG, JPG, JPEG, PDF</li>
            <li>Ukuran maksimal file: 2MB</li>
        </ul>
    </div> --}}
    
    <form id="uploadForm" class="space-y-6">
        <div id="uploadFields" class="space-y-6 text-xs">
            <!-- Field upload akan di-generate di sini -->
        </div>

        <!-- Tombol Kirim -->
        <button type="submit"
            class="w-full bg-[#51C2FF] text-white py-2 rounded-lg font-semibold cursor-pointer transition-colors">
            Kirim
        </button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            const container = document.getElementById('uploadFields');
            const res = await AwaitFetchApi('user/berkas', 'GET', null);
            


            if (res.meta?.code === 200 && Array.isArray(res.data)) {
                res.data.forEach(item => {
                    const fieldId = `file_${item.nama}`;
                    const isRequired = item.is_required === 1;
                    
                    const fieldHTML = `
                <div class="space-y-2">
                    <label class="block capitalize">
                        ${item.nama.replace(/_/g, ' ')} 
                        ${isRequired ? '<span class="text-red-500">*</span>' : '<span class="text-gray-400 text-[10px]">(Opsional)</span>'}
                    </label>
                    <div class="text-gray-500 text-[10px] mb-1">Format: PNG, JPG, JPEG, PDF. Maks: 2MB</div>
                    <div class="flex">
                        <input type="file" id="${fieldId}" class="hidden" data-ketentuan-id="${item.id}" ${isRequired ? 'required' : ''} accept=".png,.jpg,.jpeg,.pdf">
                        <label for="${fieldId}" class="w-full h-8 px-3 border rounded-lg flex items-center cursor-pointer bg-white">
                            <span class="text-white font-medium bg-[#51C2FF] p-1 rounded-sm shadow-xs">Pilih file</span>
                            <span class="text-xs text-gray-400 block pl-2 file-name">Tidak ada file yang dipilih</span>
                        </label>
                    </div>
                </div>
                `;
                    container.insertAdjacentHTML('beforeend', fieldHTML);
                });

                // Binding ulang ke semua input setelah elemen dimuat
                document.querySelectorAll('input[type="file"]').forEach(input => {
                    input.addEventListener('change', function(e) {
                        const fileName = e.target.files[0]?.name ||
                            'Tidak ada file yang dipilih';
                        const fileNameSpan = this.closest('.flex').querySelector('.file-name');
                        fileNameSpan.textContent = fileName;
                        
                        // Validasi ukuran file (2MB = 2 * 1024 * 1024 bytes)
                        const fileSize = e.target.files[0]?.size || 0;
                        const maxSize = 2 * 1024 * 1024; // 2MB
                        
                        if (fileSize > maxSize) {
                            showNotification("Ukuran file melebihi 2MB. Silakan pilih file yang lebih kecil.", "error");
                            // Reset input
                            this.value = '';
                            fileNameSpan.textContent = 'Tidak ada file yang dipilih';
                        }
                    });
                });
            } else {
                container.innerHTML = `<p class="text-sm text-red-500">Gagal memuat ketentuan berkas.</p>`;
            }
        });
        document.getElementById('uploadForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const submitBtn = document.querySelector('button[type="submit"]');
            const formData = new FormData();
            const fileInputs = document.querySelectorAll('input[type="file"]');
            let hasFile = false;
            let missingRequired = false;
            
            fileInputs.forEach((input, index) => {
                if (input.files.length > 0) {
                    hasFile = true;
                    formData.append(`files[${index}]`, input.files[0]);
                    formData.append(`ketentuan_berkas_ids[${index}]`, input.dataset.ketentuanId);
                } else if (input.hasAttribute('required')) {
                    missingRequired = true;
                }
            });

            if (!hasFile) {
                showNotification("Silakan pilih minimal satu file untuk diunggah.", "error");
                return;
            }
            
            if (missingRequired) {
                showNotification("Mohon lengkapi berkas yang wajib diisi (bertanda *)", "error");
                return;
            }

            const response = await buttonAPI('user/berkas/upload', 'POST', formData, false, submitBtn, 'Menyimpan data siswa...');

            if (response.meta?.code === 200) {
                showNotification("Berkas berhasil diunggah!", "success");
                window.location.href = '/home';
            } 
        });
    </script>
@endsection
