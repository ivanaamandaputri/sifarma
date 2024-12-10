<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InstansiController;
use App\Http\Controllers\JenisObatController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReturController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth/login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Rute untuk dashboard, admin, dan operator
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/operator', [DashboardController::class, 'operator'])->name('dashboard.operator'); // Rute untuk operator
});

// Rute untuk admin
Route::middleware(['auth', 'check.level:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::resource('user', UserController::class);
    Route::resource('obat', ObatController::class);
    Route::resource('jenis_obat', JenisObatController::class);
    Route::resource('instansi', InstansiController::class);
    Route::get('/jenis-obat/{id}/edit', [JenisObatController::class, 'edit'])->name('jenis_obat.edit');
    Route::resource('transaksi', TransaksiController::class);
    Route::post('/transaksi/approve/{id}', [PengajuanController::class, 'approve'])->name('transaksi.approve');
    Route::post('/transaksi/reject/{id}', [PengajuanController::class, 'reject'])->name('transaksi.reject');
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
    Route::get('/laporan/obatMasuk', [LaporanController::class, 'obatMasuk'])->name('laporan.obatMasuk');
    Route::get('/laporan/obatKeluar', [LaporanController::class, 'obatKeluar'])->name('laporan.obatKeluar');
    Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan.index');
    Route::post('/obat/{id}/tambah-stok', [ObatController::class, 'tambahStok'])->name('obat.tambahStok');
    Route::get('/dashboard/notification', [PengajuanController::class, 'getNotification'])->name('dashboard.notification');
    Route::get('/notification/baca/{id}', [PengajuanController::class, 'bacaNotification'])->name('notification.baca');
});

// Rute untuk operator
Route::middleware(['auth', 'check.level:operator'])->group(function () {
    Route::get('/operator/dataobat', [ObatController::class, 'operatorIndex'])->name('operator.dataobat');
    Route::get('/operator/showobat/{id}', [ObatController::class, 'operatorShowobat'])->name('operator.showobat');
    Route::resource('transaksi', TransaksiController::class);
    Route::get('/transaksi/{id}/print', [TransaksiController::class, 'print'])->name('transaksi.print');
    Route::patch('/transaksi/{id}/selesai', [TransaksiController::class, 'selesai'])->name('transaksi.selesai');
    Route::post('/transaksi/finish/{id}', [TransaksiController::class, 'finish'])->name('transaksi.finish');
    Route::get('/transaksi/detail/{id}', [TransaksiController::class, 'detail'])->name('transaksi.detail');
    Route::post('/transaksi/selesai/{id}', [TransaksiController::class, 'selesai'])->name('transaksi.selesai');

    Route::post('/transaksi/selesai/{transaksi}', [TransaksiController::class, 'selesai'])->name('transaksi.selesai');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit/{user}', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route untuk memproses pembaruan profil
    Route::put('/profile/edit/{user}', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::post('/transaksi/retur', [TransaksiController::class, 'retur'])->name('transaksi.retur');
    Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::delete('transaksi/{transaksi}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');
});
