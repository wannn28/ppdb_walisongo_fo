<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('informasi.homepage');
})->name('homepage');

Route::get('/home', function () {
    return view('datasiswa.home');
})->name('home');

Route::get('/akun', function () {
    return view('datasiswa.akun');
})->name('akun');

Route::get('/data-siswa', function () {
    return view('datasiswa.data-siswa');
})->name('data-siswa');

Route::get('/jadwal', function () {
    return view('informasi.jadwal');
})->name('jadwal');

Route::get('/berita', function () {
    return view('informasi.berita');
})->name('berita');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/form-pendaftaran', function () {
    return view('datasiswa.form-pendaftaran');
})->name('form-pendaftaran');

Route::get('/unggah-berkas', function () {
    return view('datasiswa.unggah-berkas');
})->name('unggah-berkas');

Route::get('/peringkat', function () {
    return view('datasiswa.peringkat');
})->name('peringkat');


Route::get('/pesan', function () {
    return view('komunikasi.pesan');
})->name('pesan');

Route::get('/riwayat', [App\Http\Controllers\RiwayatController::class, 'index'])->name('riwayat');

Route::get('/pengajuan-biaya', function () {
    return view('transaksi.pengajuan-biaya');
})->name('pengajuan-biaya');