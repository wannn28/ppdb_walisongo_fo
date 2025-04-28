@extends('layouts.app')

@section('content')
    <div class="text-xl flex w-full justify-center font-bold mb-4">Informasi Peserta</div>

    <!-- Tampilan Informasi -->
    <div id="info-display" class="space-y-4">
        <div class="font-medium">Data Siswa</div>
        <!-- NISN -->
        <div class="text-sm font-medium flex flex-col pl-2 pr-2 pb-2 border-b border-gray-400">
            <span>NISN</span>
            <span class="font-light text-xs" id="nisn"></span>
        </div>
        <div class="text-sm font-medium flex flex-col pl-2 pr-2 pb-2 border-b border-gray-400">
            <span>Nama</span>
            <span class="font-light text-xs" id="nama"></span>
        </div>
        <!-- Tempat, Tanggal Lahir -->
        <div class="text-sm font-medium flex flex-col pl-2 pr-2 pb-2 border-b border-gray-400">
            <span>Tempat, Tanggal Lahir</span>
            <span class="font-light text-xs" id="tempat-tanggal-lahir"></span>
        </div>
        <!-- No Telepon -->
        <div class="text-sm font-medium flex flex-col pl-2 pr-2 pb-2 border-b border-gray-400">
            <span>No Telepon</span>
            <span class="font-light text-xs" id="no-telp"></span>
        </div>
        <!-- Jenis Kelamin -->
        <div class="text-sm font-medium flex flex-col pl-2 pr-2 pb-2 border-b border-gray-400">
            <span>Jenis Kelamin</span>
            <span class="font-light text-xs" id="jenis_kelamin"></span>
        </div>
        <!-- Jenjang -->
        <div class="text-sm font-medium flex flex-col pl-2 pr-2 pb-2 border-b border-gray-400">
            <span>Jenjang</span>
            <span class="font-light text-xs" id="jenjang"></span>
        </div>
        <!-- Alamat -->
        <div class="text-sm font-medium flex flex-col pl-2 pr-2 pb-2 border-b border-gray-400">
            <span>Alamat</span>
            <span class="font-light text-xs" id="alamat"></span>
        </div>
        <div class="font-medium">Data Orang Tua</div>
        <!-- Nama Ayah -->
        <div class="text-sm font-medium flex flex-col pl-2 pr-2 pb-2 border-b border-gray-400">
            <span>Nama Ayah</span>
            <span class="font-light text-xs" id="nama-ayah"></span>
        </div>
        <!-- Nama Ibu -->
        <div class="text-sm font-medium flex flex-col pl-2 pr-2 pb-2 border-b border-gray-400">
            <span>Nama Ibu</span>
            <span class="font-light text-xs" id="nama-ibu"></span>
        </div>
        <!-- Penghasilan Orang Tua -->
        <div class="text-sm font-medium flex flex-col pl-2 pr-2 pb-2 border-b border-gray-400">
            <span>Penghasilan Orang Tua</span>
            <span class="font-light text-xs" id="penghasilan-ayah"></span>
        </div>
        <!-- Pekerjaan Ayah -->
        <div class="text-sm font-medium flex flex-col pl-2 pr-2 pb-2 border-b border-gray-400">
            <span>Pekerjaan Ayah</span>
            <span class="font-light text-xs" id="pekerjaan-ayah"></span>
        </div>

        <!-- Pekerjaan Ibu -->
        <div class="text-sm font-medium flex flex-col pl-2 pr-2 pb-2 border-b border-gray-400">
            <span>Pekerjaan Ibu</span>
            <span class="font-light text-xs" id="pekerjaan-ibu"></span>
        </div>
    </div>

    <!-- Form Edit -->
    <div id="edit-form" class="hidden space-y-4 bg-white rounded-lg p-6 shadow-md">
        <div class="font-medium text-xl text-[#0267B2] mb-4">Edit Data Siswa</div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex flex-col">
                <label for="edit-nisn" class="text-sm font-medium text-gray-700 mb-1">NISN</label>
                <input id="edit-nisn" type="text" class="border rounded p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
            </div>
            <div class="flex flex-col">
                <label for="edit-nama" class="text-sm font-medium text-gray-700 mb-1">Nama</label>
                <input id="edit-nama" type="text" class="border rounded p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
            </div>
            <div class="flex flex-col">
                <label for="edit-tempat-lahir" class="text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                <input id="edit-tempat-lahir" type="text" class="border rounded p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
            </div>
            <div class="flex flex-col">
                <label for="edit-tanggal-lahir" class="text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                <input id="edit-tanggal-lahir" type="date" class="border rounded p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
            </div>
            <div class="flex flex-col">
                <label for="edit-no-telp" class="text-sm font-medium text-gray-700 mb-1">No Telepon</label>
                <input id="edit-no-telp" type="text" class="border rounded p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
            </div>
            <div class="flex flex-col">
                <label for="edit-jenis_kelamin" class="text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                <select id="edit-jenis_kelamin" class="border rounded p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
            </div>
            <div class="flex flex-col">
                <label for="edit-jenjang" class="text-sm font-medium text-gray-700 mb-1">Jenjang</label>
                <select id="edit-jenjang" class="border rounded p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                    <option value="SD">SD</option>
                    <option value="SMP">SMP</option>
                    <option value="SMA">SMA</option>
                    <option value="SMK">SMK</option>
                </select>
            </div>
        </div>

        <div class="font-medium text-lg text-[#0267B2] mt-4 mb-2">Data Orang Tua</div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex flex-col">
                <label for="edit-nama-ayah" class="text-sm font-medium text-gray-700 mb-1">Nama Ayah</label>
                <input id="edit-nama-ayah" type="text" class="border rounded p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
            </div>
            <div class="flex flex-col">
                <label for="edit-nama-ibu" class="text-sm font-medium text-gray-700 mb-1">Nama Ibu</label>
                <input id="edit-nama-ibu" type="text" class="border rounded p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
            </div>
            <div class="flex flex-col">
                <label for="edit-pekerjaan-ayah" class="text-sm font-medium text-gray-700 mb-1">Pekerjaan Ayah</label>
                <select id="edit-pekerjaan-ayah" class="border rounded p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                    <option value="">Pilih Pekerjaan Ayah</option>
                </select>
            </div>
            <div class="flex flex-col">
                <label for="edit-pekerjaan-ibu" class="text-sm font-medium text-gray-700 mb-1">Pekerjaan Ibu</label>
                <select id="edit-pekerjaan-ibu" class="border rounded p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                    <option value="">Pilih Pekerjaan Ibu</option>
                </select>
            </div>
            <div class="flex flex-col col-span-1 md:col-span-2">
                <label for="edit-penghasilan-ortu" class="text-sm font-medium text-gray-700 mb-1">Penghasilan Orang Tua</label>
                <select id="edit-penghasilan-ortu" class="border rounded p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                    <option value="">Pilih Penghasilan Orang Tua</option>
                </select>
            </div>
        </div>

        <div class="flex flex-col">
            <label for="edit-alamat" class="text-sm font-medium text-gray-700 mb-1">Alamat</label>
            <textarea id="edit-alamat" class="border rounded p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300" rows="3"></textarea>
        </div>

        <div class="flex gap-2 mt-4">
            <button id="simpan-btn" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded transition-colors">Simpan</button>
            <button id="batal-btn" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded transition-colors">Batal</button>
        </div>
    </div>

    <!-- Tombol Edit -->
    <button id="edit-btn"
        class="fixed bottom-20 right-4 w-24 flex justify-center text-sm bg-[#51C2FF] text-white p-2 rounded-lg shadow-lg z-50">
        Edit
    </button>

    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const pesertaRes = await AwaitFetchApi('user/peserta', 'GET', null);
            const data = pesertaRes.data;

            const assignText = (id, value) => document.getElementById(id).textContent = value ?? '';

            assignText('nisn', data.nisn);
            assignText('nama', data.nama);
            assignText('tempat-tanggal-lahir', `${data.tempat_lahir ?? ''}, ${data.tanggal_lahir ?? ''}`);
            assignText('no-telp', data.no_telp);
            assignText('jenis_kelamin', data.jenis_kelamin);
            assignText('jenjang', data.jenjang_sekolah);
            assignText('alamat', data.alamat);

            assignText('nama-ayah', data.biodata_ortu?.nama_ayah);
            assignText('nama-ibu', data.biodata_ortu?.nama_ibu);
            assignText('penghasilan-ayah', data.biodata_ortu?.penghasilan_ortu?.penghasilan);
            assignText('pekerjaan-ayah', data.biodata_ortu?.pekerjaan_ayah?.pekerjaan);
            assignText('pekerjaan-ibu', data.biodata_ortu?.pekerjaan_ibu?.pekerjaan);

            const container = document.getElementById('info-display');
            const header = document.createElement('div');
            header.classList.add('font-medium', 'mt-4');
            header.innerText = 'Berkas Siswa';
            container.appendChild(header);

            (data.berkas ?? []).forEach(berkas => {
                const wrapper = document.createElement('div');
                wrapper.className =
                    'text-sm font-medium pl-2 pr-2 pb-3 pt-2 border-b border-gray-400 flex flex-col';
                const label = berkas.nama_file.replace(/_/g, ' ');
                
                const extension = berkas.url_file.split('.').pop() || 'png';
                const fileIcon = extension === 'pdf' ? 
                    '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1 text-red-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" /></svg>' :
                    '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1 text-blue-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" /></svg>';
                
                wrapper.innerHTML = `
                    <div class="flex justify-between items-center">
                        <span class="capitalize">${label}</span>
                        <button class="edit-btn text-gray-600 hover:text-blue-500 transition-colors" data-id="${berkas.id}" data-ketentuan-id="${berkas.ketentuan_berkas_id ?? ''}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                        </button>
                    </div>
                    <div class="font-light text-xs mt-1 flex items-center">
                        ${fileIcon}
                        <a href="${berkas.url_file}" target="_blank" class="hover:text-blue-500 transition-colors flex-1 truncate">${label}.${extension}</a>
                    </div>
                    <div class="file-upload-container mt-2 hidden"></div>
                `;
                container.appendChild(wrapper);
            });

            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const wrapper = this.closest('div.text-sm');
                    const fileUploadContainer = wrapper.querySelector('.file-upload-container');
                    
                    // Toggle visibility
                    if (fileUploadContainer.classList.contains('hidden')) {
                        fileUploadContainer.classList.remove('hidden');
                        fileUploadContainer.innerHTML = '';
                        
                        const ketentuanId = this.dataset.ketentuanId;
                        
                        const inputContainer = document.createElement('div');
                        inputContainer.className = 'flex items-center gap-2 w-full';

                        const input = document.createElement('input');
                        input.type = 'file';
                        input.accept = 'image/*,application/pdf';
                        input.className = 'text-xs flex-1';
                        input.style.maxWidth = '180px';
                        input.dataset.ketentuanId = ketentuanId;
                        
                        const saveBtn = document.createElement('button');
                        saveBtn.innerHTML = `
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        `;
                        saveBtn.className = 'bg-green-500 text-white p-1 rounded hover:bg-green-600 transition-colors';
                        saveBtn.title = 'Simpan';
                        
                        const cancelBtn = document.createElement('button');
                        cancelBtn.innerHTML = `
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        `;
                        cancelBtn.className = 'bg-gray-500 text-white p-1 rounded hover:bg-gray-600 transition-colors';
                        cancelBtn.title = 'Batal';
                        
                        cancelBtn.addEventListener('click', () => {
                            fileUploadContainer.classList.add('hidden');
                        });
                        
                        saveBtn.addEventListener('click', async () => {
                            const file = input.files[0];
                            if (!file) return showNotification(
                                'Pilih file terlebih dahulu.', 'error');
                            const formData = new FormData();
                            formData.append('file', file);
                            formData.append('ketentuan_berkas_id', input.dataset
                                .ketentuanId);
                            
                            // Show loading indicator
                            saveBtn.innerHTML = `<svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>`;
                            saveBtn.disabled = true;
                            
                            const res = await AwaitFetchApi(`user/berkas/${id}`, 'POST',
                                formData);
                            res.meta?.code === 200 ? location.reload() :
                                showNotification('Gagal memperbarui berkas.', 'error');
                        });

                        inputContainer.appendChild(input);
                        inputContainer.appendChild(saveBtn);
                        inputContainer.appendChild(cancelBtn);
                        fileUploadContainer.appendChild(inputContainer);
                    } else {
                        fileUploadContainer.classList.add('hidden');
                    }
                });
            });
        });

        document.getElementById('edit-btn').addEventListener('click', async () => {
            document.getElementById('info-display').classList.add('hidden');
            document.getElementById('edit-form').classList.remove('hidden');
            
            const getText = id => document.getElementById(id).textContent;
            
            // Populate basic form fields
            document.getElementById('edit-nisn').value = getText('nisn');
            document.getElementById('edit-nama').value = getText('nama');
            const [tempat, tanggal] = getText('tempat-tanggal-lahir').split(', ');
            document.getElementById('edit-tempat-lahir').value = tempat || '';
            document.getElementById('edit-tanggal-lahir').value = tanggal || '';
            document.getElementById('edit-no-telp').value = getText('no-telp');
            document.getElementById('edit-jenis_kelamin').value = getText('jenis_kelamin');
            document.getElementById('edit-jenjang').value = getText('jenjang');
            document.getElementById('edit-alamat').value = getText('alamat');
            document.getElementById('edit-nama-ayah').value = getText('nama-ayah');
            document.getElementById('edit-nama-ibu').value = getText('nama-ibu');
            
            // Fetch pekerjaan ortu data for dropdowns
            try {
                const pekerjaanRes = await AwaitFetchApi('pekerjaan-ortu', 'GET', null);
                
                if (pekerjaanRes.meta?.code === 200) {
                    const pekerjaanAyahSelect = document.getElementById('edit-pekerjaan-ayah');
                    const pekerjaanIbuSelect = document.getElementById('edit-pekerjaan-ibu');
                    
                    // Clear existing options except the first one
                    pekerjaanAyahSelect.innerHTML = '<option value="">Pilih Pekerjaan Ayah</option>';
                    pekerjaanIbuSelect.innerHTML = '<option value="">Pilih Pekerjaan Ibu</option>';
                    
                    // Get the data array from the correct location
                    let pekerjaanData = [];
                    if (Array.isArray(pekerjaanRes.data)) {
                        pekerjaanData = pekerjaanRes.data;
                    } else if (Array.isArray(pekerjaanRes.data?.data)) {
                        pekerjaanData = pekerjaanRes.data.data;
                    }
                    
                    // Add options from API and set selected value
                    const pekerjaanAyahText = getText('pekerjaan-ayah');
                    const pekerjaanIbuText = getText('pekerjaan-ibu');
                    
                    pekerjaanData.forEach(item => {
                        const optionAyah = document.createElement('option');
                        optionAyah.value = item.id;
                        optionAyah.textContent = item.nama_pekerjaan;
                        if (item.nama_pekerjaan === pekerjaanAyahText) {
                            optionAyah.selected = true;
                        }
                        pekerjaanAyahSelect.appendChild(optionAyah);
                        
                        const optionIbu = document.createElement('option');
                        optionIbu.value = item.id;
                        optionIbu.textContent = item.nama_pekerjaan;
                        if (item.nama_pekerjaan === pekerjaanIbuText) {
                            optionIbu.selected = true;
                        }
                        pekerjaanIbuSelect.appendChild(optionIbu);
                    });
                }
            } catch (error) {
                console.error('Error fetching pekerjaan ortu:', error);
            }
            
            // Fetch penghasilan ortu data for dropdown
            try {
                const penghasilanRes = await AwaitFetchApi('user/penghasilan-ortu', 'GET', null);
                
                if (penghasilanRes.meta?.code === 200) {
                    const penghasilanSelect = document.getElementById('edit-penghasilan-ortu');
                    
                    // Clear existing options
                    penghasilanSelect.innerHTML = '<option value="">Pilih Penghasilan Orang Tua</option>';
                    
                    // Get the data array
                    let penghasilanData = [];
                    if (Array.isArray(penghasilanRes.data)) {
                        penghasilanData = penghasilanRes.data;
                    } else if (Array.isArray(penghasilanRes.data?.data)) {
                        penghasilanData = penghasilanRes.data.data;
                    }
                    
                    // Get current penghasilan text
                    const penghasilanText = getText('penghasilan-ayah');
                    
                    // Add options from API
                    penghasilanData.forEach((item, index) => {
                        const option = document.createElement('option');
                        option.value = index + 1; // Use index+1 as value
                        option.textContent = item.penghasilan_ortu;
                        
                        // Select the matching option
                        if (item.penghasilan_ortu === penghasilanText) {
                            option.selected = true;
                        }
                        
                        penghasilanSelect.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('Error fetching penghasilan ortu:', error);
            }
        });

        // Add a cancel button handler
        document.getElementById('batal-btn').addEventListener('click', () => {
            document.getElementById('edit-form').classList.add('hidden');
            document.getElementById('info-display').classList.remove('hidden');
        });

        document.getElementById('simpan-btn').addEventListener('click', async () => {
            const siswaData = {
                nisn: document.getElementById('edit-nisn').value,
                nama: document.getElementById('edit-nama').value,
                tempat_lahir: document.getElementById('edit-tempat-lahir').value,
                tanggal_lahir: document.getElementById('edit-tanggal-lahir').value,
                no_telp: document.getElementById('edit-no-telp').value,
                jenis_kelamin: document.getElementById('edit-jenis_kelamin').value,
                jenjang_sekolah: document.getElementById('edit-jenjang').value,
                alamat: document.getElementById('edit-alamat').value,
            };

            const ortuData = {
                nama_ayah: document.getElementById('edit-nama-ayah').value,
                nama_ibu: document.getElementById('edit-nama-ibu').value,
                no_telp: document.getElementById('edit-no-telp').value,
                pekerjaan_ayah_id: document.getElementById('edit-pekerjaan-ayah').value || null,
                pekerjaan_ibu_id: document.getElementById('edit-pekerjaan-ibu').value || null,
                penghasilan_ortu_id: document.getElementById('edit-penghasilan-ortu').value || null
            };

            try {
                // Simpan data siswa terlebih dahulu
                const responseForm = await AwaitFetchApi('user/peserta', 'PUT', siswaData);
                
                if (responseForm.meta?.code !== 200) {
                    showNotification("Gagal memperbarui data siswa. Silakan coba lagi.", "error");
                    return;
                }
                
                // Cek apakah sudah ada data ortu
                const pesertaRes = await AwaitFetchApi('user/peserta', 'GET', null);
                const method = pesertaRes.data?.biodata_ortu ? 'PUT' : 'POST';
                
                // Gunakan POST jika belum ada data ortu, PUT jika sudah ada
                const responseOrtu = await AwaitFetchApi('user/biodata-ortu', method, ortuData);
                
                if (responseOrtu.meta?.code >= 200 && responseOrtu.meta?.code < 300) {
                    showNotification("Data berhasil diperbarui", "success");
                    location.reload();
                } else {
                    showNotification("Gagal memperbarui data orang tua. Silakan coba lagi.", "error");
                }
            } catch (error) {
                console.error('Error updating data:', error);
                showNotification("Terjadi kesalahan saat memperbarui data.", "error");
            }
        });
    </script>
@endsection
