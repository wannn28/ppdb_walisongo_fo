@extends('layouts.app')

@section('content')
    <div class="text-2xl flex w-full justify-center font-bold">Form Pendaftaran Siswa</div>
    <form id="form-pendaftaran" class="space-y-4">
        <div>Data Siswa</div>
        <div class="text-xs">
            NISN
            <input id="nisn" type="text"
                class="w-full h-8 pl-3 pr-4 border rounded-lg focus:outline-none font-extralight" placeholder="Masukkan NISN">
        </div>
        <div class="text-xs grid grid-cols-2 gap-4">
            <div>
                Tempat Lahir
                <input id="tempat_lahir" type="text"
                    class="w-full h-8 pl-3 pr-4 border rounded-lg focus:outline-none font-extralight"
                    placeholder="Tempat Lahir">
            </div>
            <div>
                Tanggal Lahir
                <input id="tanggal_lahir" type="date"
                    class="w-full h-8 pl-3 pr-4 border rounded-lg focus:outline-none font-extralight">
            </div>
        </div>
        <div class="text-xs grid grid-cols-2 gap-4">
            <div>
                Kelas 1
                <select id="jurusan1" class="w-full h-8 pl-3 pr-4 border rounded-lg focus:outline-none font-extralight">
                    <option value="">Pilih Kelas</option>
                </select>
            </div>
            {{-- <div>
                Kelas 2
                <select id="jurusan2" class="w-full h-8 pl-3 pr-4 border rounded-lg focus:outline-none font-extralight">
                    <option value="">Pilih Jurusan</option>
                </select>
            </div> --}}
        </div>
        <div class="text-xs">
            Alamat
            <textarea id="alamat" cols="30" rows="5"
                class="w-full py-2 pl-3 pr-4 border rounded-lg focus:outline-none font-extralight"
                placeholder="Masukkan Alamat Lengkap"></textarea>
        </div>

        <div>Data Orang Tua</div>
        <div class="text-xs">
            Nama Ayah
            <input id="nama_ayah" type="text"
                class="w-full h-8 pl-3 pr-4 border rounded-lg focus:outline-none font-extralight"
                placeholder="Masukkan Nama Ayah">
        </div>
        <div class="text-xs">
            Nama Ibu
            <input id="nama_ibu" type="text"
                class="w-full h-8 pl-3 pr-4 border rounded-lg focus:outline-none font-extralight"
                placeholder="Masukkan Nama Ibu">
        </div>
        <div class="text-xs">
            No Telp
            <input id="no_telp" type="text"
                class="w-full h-8 pl-3 pr-4 border rounded-lg focus:outline-none font-extralight"
                placeholder="Masukkan No Telp Orang Tua">
        </div>
        <div class="text-xs">
            Pekerjaan Ayah
            <select id="pekerjaan_ayah_id" class="w-full h-8 pl-3 pr-4 border rounded-lg focus:outline-none font-extralight">
                <option value="">Pilih Pekerjaan Ayah</option>
            </select>
        </div>
        <div class="text-xs">
            Pekerjaan Ibu
            <select id="pekerjaan_ibu_id" class="w-full h-8 pl-3 pr-4 border rounded-lg focus:outline-none font-extralight">
                <option value="">Pilih Pekerjaan Ibu</option>
            </select>
        </div>
        <div class="text-xs">
            Penghasilan Ortu (ID)
            <input id="penghasilan_ortu_id" type="number"
                class="w-full h-8 pl-3 pr-4 border rounded-lg focus:outline-none font-extralight"
                placeholder="ID Penghasilan Orang Tua">
        </div>


        <button type="submit" class="bg-[#51C2FF] text-white p-2 rounded-lg">Submit</button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            const form = document.getElementById('form-pendaftaran');
            
            // Fetch jurusan data
            try {
                const jurusanRes = await AwaitFetchApi('jurusan', 'GET', null);
                if (jurusanRes.meta?.code === 200) {
                    const jurusan1Select = document.getElementById('jurusan1');
                    const jurusan2Select = document.getElementById('jurusan2');
                    
                    // Clear existing options except the first one
                    jurusan1Select.innerHTML = '<option value="">Pilih Jurusan</option>';
                    jurusan2Select.innerHTML = '<option value="">Pilih Jurusan</option>';
                    
                    // Handle both possible response structures
                    const jurusanData = Array.isArray(jurusanRes.data?.data) ? jurusanRes.data.data : 
                                        Array.isArray(jurusanRes.data) ? jurusanRes.data : [];
                    
                    // Add options from API
                    jurusanData.forEach(item => {
                        const option1 = document.createElement('option');
                        option1.value = item.id;
                        option1.textContent = `${item.jurusan} (${item.jenjang_sekolah})`;
                        jurusan1Select.appendChild(option1);
                        
                        const option2 = document.createElement('option');
                        option2.value = item.id;
                        option2.textContent = `${item.jurusan} (${item.jenjang_sekolah})`;
                        jurusan2Select.appendChild(option2);
                    });
                }
            } catch (error) {
                print.error('Error fetching jurusan:', error);
            }
            
            // Fetch pekerjaan ortu data
            try {
                const pekerjaanRes = await AwaitFetchApi('pekerjaan-ortu', 'GET', null);
                if (pekerjaanRes.meta?.code === 200 && Array.isArray(pekerjaanRes.data?.data)) {
                    const pekerjaanAyahSelect = document.getElementById('pekerjaan_ayah_id');
                    const pekerjaanIbuSelect = document.getElementById('pekerjaan_ibu_id');
                    
                    // Clear existing options except the first one
                    pekerjaanAyahSelect.innerHTML = '<option value="">Pilih Pekerjaan Ayah</option>';
                    pekerjaanIbuSelect.innerHTML = '<option value="">Pilih Pekerjaan Ibu</option>';
                    
                    // Add options from API
                    pekerjaanRes.data.data.forEach(item => {
                        const optionAyah = document.createElement('option');
                        optionAyah.value = item.id;
                        optionAyah.textContent = item.nama_pekerjaan;
                        pekerjaanAyahSelect.appendChild(optionAyah);
                        
                        const optionIbu = document.createElement('option');
                        optionIbu.value = item.id;
                        optionIbu.textContent = item.nama_pekerjaan;
                        pekerjaanIbuSelect.appendChild(optionIbu);
                    });
                }
            } catch (error) {
                print.error('Error fetching pekerjaan ortu:', error);
            }

            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const submitBtn = document.querySelector('button[type="submit"]');

                const payload = {
                    nisn: document.getElementById('nisn').value,
                    tempat_lahir: document.getElementById('tempat_lahir').value,
                    tanggal_lahir: document.getElementById('tanggal_lahir').value,
                    alamat: document.getElementById('alamat').value,
                    jurusan1_id: parseInt(document.getElementById('jurusan1').value),
                    jurusan2_id: parseInt(document.getElementById('jurusan2').value)
                };

                const payloadOrtu = {
                    nama_ayah: document.getElementById('nama_ayah').value,
                    nama_ibu: document.getElementById('nama_ibu').value,
                    no_telp: document.getElementById('no_telp').value,
                    pekerjaan_ayah_id: parseInt(document.getElementById('pekerjaan_ayah_id').value),
                    pekerjaan_ibu_id: parseInt(document.getElementById('pekerjaan_ibu_id').value),
                    penghasilan_ortu_id: parseInt(document.getElementById('penghasilan_ortu_id')
                        .value)
                };

                try {
                    const responseForm = await buttonAPI('user/peserta/form-peserta', 'PUT',
                        payload, false, submitBtn, 'Menyimpan data siswa...');
                    if (responseForm.errors) {
                     //   showNotification("Gagal menyimpan data siswa", "error");
                        return;
                    }

                    const responseOrtu = await buttonAPI('user/biodata-ortu', 'POST', payloadOrtu, false, submitBtn, 'Menyimpan data orang tua...');
                    if (responseOrtu.errors) {
                    //    showNotification("Gagal menyimpan data orang tua", "error");
                        return;
                    }

                    showNotification("Formulir berhasil dikirim!", "success");
                    window.location.href = '/data-siswa';
                } catch (error) {
                    print.error('Error:', error);
                    showNotification(
                        "Terjadi kesalahan saat mengirim data. Periksa koneksi atau hubungi admin.",
                        "error");
                }
            });

        });
    </script>
@endsection
