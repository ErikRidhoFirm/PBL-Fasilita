<?php

use App\Models\Penugasan;
use App\Models\SkoringKriteria;
use App\Models\PenilaianPengguna;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PeranController;
use App\Http\Controllers\GedungController;
use App\Http\Controllers\LantaiController;
use App\Http\Controllers\TopsisController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\PenugasanController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\SkorTopsisController;
use App\Http\Controllers\VerifikasiController;
use App\Http\Controllers\RiwayatPelaporController;
use App\Http\Controllers\SkoringKriteriaController;
use App\Http\Controllers\LaporanFasilitasController;
use App\Http\Controllers\PelaporDashboardController;
use App\Http\Controllers\RiwayatPerbaikanController;
use App\Http\Controllers\KategoriFasilitasController;
use App\Http\Controllers\KategoriKerusakanController;
use App\Http\Controllers\PenilaianPenggunaController;
use App\Http\Controllers\PelaporLaporanFasilitasController;
use App\Http\Controllers\RiwayatLaporanFasilitasController;
use App\Http\Controllers\RiwayatPerbaikanTeknisiController;

// use App\Models\PenilaianPengguna;
// use App\Models\Penugasan;
use App\Models\RiwayatLaporanFasilitas;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/welcome', [\App\Http\Controllers\LandingPageController::class, 'index'])->name('landing.index');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');
Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',   [AuthController::class, 'login'])->name('login.attempt');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/update-photo', [ProfileController::class, 'update_photo'])->name('profile.update_photo');
        Route::post('/update-info', [ProfileController::class, 'update_info'])->name('profile.update_info');
        Route::post('/update-password', [ProfileController::class, 'update_password'])->name('profile.update_password');
    });

    Route::middleware(['role:ADM'])->group(function () {
        Route::prefix('peran')->group(function () {
            Route::get('/', [PeranController::class, 'index'])->name('peran.index');
            Route::get('/list', [PeranController::class, 'list'])->name('peran.list');
            Route::get('/show/{id}', [PeranController::class, 'show'])->name('peran.show');
            Route::get('/create', [PeranController::class, 'create'])->name('peran.create');
            Route::post('/store', [PeranController::class, 'store'])->name('peran.store');
            Route::get('/edit/{id}', [PeranController::class, 'edit'])->name('peran.edit');
            Route::put('/update/{id}', [PeranController::class, 'update'])->name('peran.update');
            Route::get('/delete/{id}', [PeranController::class, 'delete'])->name('peran.delete');
            Route::delete('/destroy/{id}', [PeranController::class, 'destroy'])->name('peran.destroy');
            Route::get('/export_pdf', [PeranController::class, 'exportPdf'])->name('peran.export_pdf');
            Route::get('/import', [PeranController::class, 'import'])->name('peran.import');
            Route::post('/import_ajax', [PeranController::class, 'importAjax'])->name('peran.import_ajax');
        });

        Route::prefix('kategori_kerusakan')->group(function () {
            Route::get('/', [KategoriKerusakanController::class, 'index'])->name('kategori_kerusakan.index');
            Route::get('/list', [KategoriKerusakanController::class, 'list'])->name('kategori_kerusakan.list');
            Route::get('/show/{id}', [KategoriKerusakanController::class, 'show'])->name('kategori_kerusakan.show');
            Route::get('/create', [KategoriKerusakanController::class, 'create'])->name('kategori_kerusakan.create');
            Route::post('/store', [KategoriKerusakanController::class, 'store'])->name('kategori_kerusakan.store');
            Route::get('/edit/{id}', [KategoriKerusakanController::class, 'edit'])->name('kategori_kerusakan.edit');
            Route::put('/update/{id}', [KategoriKerusakanController::class, 'update'])->name('kategori_kerusakan.update');
            Route::get('/delete/{id}', [KategoriKerusakanController::class, 'delete'])->name('kategori_kerusakan.delete');
            Route::delete('/destroy/{id}', [KategoriKerusakanController::class, 'destroy'])->name('kategori_kerusakan.destroy');
            Route::get('/export_pdf', [KategoriKerusakanController::class, 'exportPdf'])->name('kategori_kerusakan.export_pdf');
            Route::get('/import', [KategoriKerusakanController::class, 'import'])->name('kategori_kerusakan.import');
            Route::post('/import_ajax', [KategoriKerusakanController::class, 'importAjax'])->name('kategori_kerusakan.import_ajax');
        });

        // Pengguna
        Route::prefix('pengguna')->group(function () {
            Route::get('/', [PenggunaController::class, 'index'])->name('pengguna.index');
            Route::get('/list', [PenggunaController::class, 'list'])->name('pengguna.list');
            Route::get('/create', [PenggunaController::class, 'create'])->name('pengguna.create');
            Route::get('/show/{id}', [PenggunaController::class, 'show'])->name('pengguna.show');
            Route::post('/store', [PenggunaController::class, 'store'])->name('pengguna.store');
            Route::get('/edit/{id}', [PenggunaController::class, 'edit'])->name('pengguna.edit');
            Route::put('/update/{id}', [PenggunaController::class, 'update'])->name('pengguna.update');
            Route::get('/delete/{id}', [PenggunaController::class, 'confirm'])->name('pengguna.delete');
            Route::delete('/destroy/{id}', [PenggunaController::class, 'destroy'])->name('pengguna.destroy');
            Route::get('/import',      [PenggunaController::class, 'import'])->name('pengguna.import');
            Route::post('/import_ajax', [PenggunaController::class, 'importAjax'])->name('pengguna.import_ajax');
            Route::get('/export_excel', [PenggunaController::class, 'exportExcel'])->name('pengguna.export_excel');
            Route::get('/export_pdf',   [PenggunaController::class, 'exportPdf'])->name('pengguna.export_pdf');
        });

        // Master Data Fisik: Gedung, Lantai, Ruangan
        /* ----------  GEDUNG  ---------- */

        Route::prefix('gedung')->name('gedung.')->group(function () {

            Route::get('/',                [GedungController::class, 'index'])->name('index');
            Route::get('/list',            [GedungController::class, 'list'])->name('list');
            Route::get('/create',          [GedungController::class, 'create'])->name('create');
            Route::post('/store',          [GedungController::class, 'store'])->name('store');

            /* modal edit & hapus */
            Route::get('/{gedung}/edit',   [GedungController::class, 'edit'])->name('edit');
            Route::get('/{gedung}/delete', [GedungController::class, 'delete'])->name('delete');

            /* simpan edit & eksekusi hapus */
            Route::put('/{gedung}',        [GedungController::class, 'update'])->name('update');   // ← ganti {id} → {gedung}
            Route::delete('/{gedung}',     [GedungController::class, 'destroy'])->name('destroy'); // ← ganti {id} → {gedung}
            Route::get('/export_pdf', [GedungController::class, 'exportPdf'])->name('export_pdf');
            Route::get('/import', [GedungController::class, 'import'])->name('import');
            Route::post('/import_ajax', [GedungController::class, 'importAjax'])->name('import_ajax');
        });

        // 1️⃣ Daftar & Tambah Lantai untuk satu Gedung
        Route::prefix('gedung/{gedung}/lantai')
            ->name('gedung.lantai.')
            ->group(
                function () {
                    Route::get('/',       [LantaiController::class, 'index'])->name('index');
                    Route::get('list',    [LantaiController::class, 'list'])->name('list');
                    Route::get('create',  [LantaiController::class, 'create'])->name('create');
                    Route::post('store',  [LantaiController::class, 'store'])->name('store');
                    Route::get('export_pdf', [LantaiController::class, 'exportPdf'])->name('export_pdf');
                    Route::get('import', [LantaiController::class, 'import'])->name('import');
                    Route::post('import_ajax', [LantaiController::class, 'importAjax'])->name('import_ajax');
                }
            );

        // 2️⃣ Akses langsung satu Lantai (Edit / Update / Delete / Show)
        Route::prefix('lantai')->name('lantai.')->group(function () {
            // modal edit (GET /lantai/{id}/edit)
            Route::get('{lantai}/edit', [LantaiController::class, 'edit'])->name('edit');

            // ★ ganti jadi PUT /lantai/update/{id} ★
            Route::put('update/{lantai}', [LantaiController::class, 'update'])->name('update');

            // modal delete (GET /lantai/{id}/delete)
            Route::get('{lantai}/delete', [LantaiController::class, 'delete'])->name('delete');

            // ★ ganti jadi DELETE /lantai/delete/{id} ★
            Route::delete('delete/{lantai}', [LantaiController::class, 'destroy'])->name('destroy');
        });
        // … pastikan import di atas …

        /*
        |--------------------------------------------------------------------------
        | Nested: RUANGAN di dalam LANTAI
        |--------------------------------------------------------------------------
        */

        Route::prefix('lantai/{lantai}/ruangan')
            ->name('lantai.ruangan.')
            ->group(
                function () {
                    Route::get('/',      [RuanganController::class, 'index'])->name('index');
                    Route::get('list',   [RuanganController::class, 'list'])->name('list');
                    Route::get('create', [RuanganController::class, 'create'])->name('create');
                    Route::post('store', [RuanganController::class, 'store'])->name('store');
                    Route::get('export_pdf', [RuanganController::class, 'exportPdf'])->name('export_pdf');
                    Route::get('import', [RuanganController::class, 'import'])->name('import');
                    Route::post('import', [RuanganController::class, 'importAjax'])->name('import_ajax');
                }
            );

        /*
        |--------------------------------------------------------------------------
        | Direct CRUD: RUANGAN
        |--------------------------------------------------------------------------
        */
        Route::prefix('ruangan')
            ->name('ruangan.')
            ->group(
                function () {
                    // Modal edit (GET /ruangan/13/edit)
                    Route::get('{ruangan}/edit',    [RuanganController::class, 'edit'])->name('edit');

                    // Update (PUT /ruangan/update/13)
                    Route::put('update/{ruangan}',  [RuanganController::class, 'update'])->name('update');

                    // Modal delete (GET /ruangan/13/delete)
                    Route::get('{ruangan}/delete',   [RuanganController::class, 'delete'])->name('delete');

                    // Destroy (DELETE /ruangan/delete/13)
                    Route::delete('delete/{ruangan}', [RuanganController::class, 'destroy'])->name('destroy');

                    // Show (GET /ruangan/13)
                    Route::get('{ruangan}',          [RuanganController::class, 'show'])->name('show');
                }
            );

        // Nested: Fasilitas di dalam Ruangan
        Route::prefix('ruangan/{ruangan}/fasilitas')
            ->name('ruangan.fasilitas.')
            ->group(
                function () {
                    Route::get('/',       [FasilitasController::class, 'index'])->name('index');
                    Route::get('list',    [FasilitasController::class, 'list'])->name('list');
                    Route::get('create',  [FasilitasController::class, 'create'])->name('create');
                    Route::post('store',  [FasilitasController::class, 'store'])->name('store');
                    Route::get('export-pdf', [FasilitasController::class, 'exportPdf'])->name('export_pdf');
                    Route::get('import',     [FasilitasController::class, 'import'])->name('import');
                    Route::post('import-ajax', [FasilitasController::class, 'importAjax'])->name('import_ajax');
                }
            );

        // CRUD langsung Fasilitas
        Route::prefix('fasilitas')
            ->name('fasilitas.')
            ->group(
                function () {
                    // form edit
                    Route::get('{fasilitas}/edit',   [FasilitasController::class, 'edit'])->name('edit');
                    // update = PUT /fasilitas/update/{id}
                    Route::put('update/{fasilitas}', [FasilitasController::class, 'update'])->name('update');
                    // form delete
                    Route::get('{fasilitas}/delete', [FasilitasController::class, 'delete'])->name('delete');
                    // destroy = DELETE /fasilitas/delete/{id}
                    Route::delete('delete/{fasilitas}', [FasilitasController::class, 'destroy'])->name('destroy');
                    // detail show
                    Route::get('{fasilitas}',        [FasilitasController::class, 'show'])->name('show');
                }
            );

        /* -----------------------------------------------------------------
         |  FASILITAS (akses langsung: edit / update / delete)
         |------------------------------------------------------------------*/
        Route::prefix('fasilitas')->name('fasilitas.')->group(function () {
            Route::get('/{fasilitas}/edit', [FasilitasController::class, 'edit'])->name('edit');
            Route::put('/{fasilitas}',      [FasilitasController::class, 'update'])->name('update');
            Route::delete('/{fasilitas}',   [FasilitasController::class, 'destroy'])->name('destroy');
        });
        Route::prefix('lantai')->group(function () {
            Route::get('/', 'LantaiController@index')->name('lantai.index');
            Route::post('/store', 'LantaiController@store')->name('lantai.store');
            Route::post('/update/{id}', 'LantaiController@update')->name('lantai.update');
        });
        Route::prefix('ruangan')->group(function () {
            Route::get('/', 'RuanganController@index')->name('ruangan.index');
            Route::post('/store', 'RuanganController@store')->name('ruangan.store');
            Route::post('/update/{id}', 'RuanganController@update')->name('ruangan.update');
        });

        // Kategori Fasilitas & Kategori Kerusakan
        Route::prefix('kategori-fasilitas')->group(function () {
            Route::get('/', [KategoriFasilitasController::class, 'index'])->name('kategoriF.index');
            Route::get('/list', [KategoriFasilitasController::class, 'list'])->name('kategoriF.list');
            Route::get('/show/{id}', [KategoriFasilitasController::class, 'show'])->name('kategoriF.show');
            Route::get('/create', [KategoriFasilitasController::class, 'create'])->name('kategoriF.create');
            Route::post('/store', [KategoriFasilitasController::class, 'store'])->name('kategoriF.store');
            Route::get('/edit/{id}', [KategoriFasilitasController::class, 'edit'])->name('kategoriF.edit');
            Route::put('/update/{id}', [KategoriFasilitasController::class, 'update'])->name('kategoriF.update');
            Route::get('/delete/{id}', [KategoriFasilitasController::class, 'delete'])->name('kategoriF.delete');
            Route::delete('/destroy/{id}', [KategoriFasilitasController::class, 'destroy'])->name('kategoriF.destroy');
            Route::get('/export_pdf', [KategoriFasilitasController::class, 'exportPdf'])->name('kategoriF.export_pdf');
            Route::get('/kategori-fasilitas/import', [KategoriFasilitasController::class, 'import'])->name('kategoriF.import');
            Route::post('/kategori-fasilitas/import-ajax', [KategoriFasilitasController::class, 'importAjax'])->name('kategoriF.import_ajax');
        });

        // Fasilitas
        Route::prefix('fasilitas')->group(function () {
            Route::get('/', 'FasilitasController@index')->name('fasilitas.index');
            Route::post('/store', 'FasilitasController@store')->name('fasilitas.store');
            Route::post('/update/{id}', 'FasilitasController@update')->name('fasilitas.update');
        });


        // Status (master)
        Route::prefix('status')->group(function () {
            Route::get('/', 'StatusController@index')->name('status.index');
            Route::post('/store', 'StatusController@store')->name('status.store');
            Route::post('/update/{id}', 'StatusController@update')->name('status.update');
            Route::delete('/destroy/{id}', 'StatusController@destroy')->name('status.destroy');
        });
    });

    Route::middleware(['role:ADM,SPR'])->group(function () {

        // Kriteria & Skoring Kriteria
        Route::prefix('kriteria')->group(function () {
            Route::get('/', [KriteriaController::class, 'index'])->name('kriteria.index');
            Route::get('/list', [KriteriaController::class, 'list'])->name('kriteria.list');
            Route::get('/show/{id}', [KriteriaController::class, 'show'])->name('kriteria.show');
            Route::get('/create', [KriteriaController::class, 'create'])->name('kriteria.create');
            Route::post('/store', [KriteriaController::class, 'store'])->name('kriteria.store');
            Route::get('/edit/{id}', [KriteriaController::class, 'edit'])->name('kriteria.edit');
            Route::put('/update/{id}', [KriteriaController::class, 'update'])->name('kriteria.update');
            Route::get('/delete/{id}', [KriteriaController::class, 'confirm'])->name('kriteria.confirm');
            Route::delete('/destroy/{id}', [KriteriaController::class, 'destroy'])->name('kriteria.destroy');
        });

        Route::prefix('skoring-kriteria')->group(function () {
            Route::get('/', [SkoringKriteriaController::class, 'index'])->name('skoring.index');
            Route::get('/list/{id}', [SkoringKriteriaController::class, 'list'])->name('skoring.list');
            Route::get('/create/{id}', [SkoringKriteriaController::class, 'create'])->name('skoring.create');
            Route::post('/store/{id}', [SkoringKriteriaController::class, 'store'])->name('skoring.store');
            Route::get('/edit/{id}', [SkoringKriteriaController::class, 'edit'])->name('skoring.edit');
            Route::put('/update/{id}', [SkoringKriteriaController::class, 'update'])->name('skoring.update');
            Route::get('/delete/{id}', [SkoringKriteriaController::class, 'confirm'])->name('skoring.confirm');
            Route::delete('/destroy/{id}', [SkoringKriteriaController::class, 'destroy'])->name('skoring.destroy');
        });

        Route::prefix('riwayat-perbaikan')->name('riwayat-perbaikan.')->group(function () {
            Route::get('/', [RiwayatPerbaikanController::class, 'index'])->name('index');
            Route::get('/list', [RiwayatPerbaikanController::class, 'list'])->name('list');
            Route::get('/{perbaikan}', [RiwayatPerbaikanController::class, 'show'])->name('show');
            Route::get('/{perbaikan}/edit', [RiwayatPerbaikanController::class, 'edit'])->name('edit');
            Route::put('/{perbaikan}', [RiwayatPerbaikanController::class, 'update'])->name('update');
        });

        Route::prefix('laporan')->group(function () {
            Route::get('/', [LaporanController::class, 'index'])->name('laporan.index');
            Route::get('/list', [LaporanController::class, 'list'])->name('laporan.list');
            Route::get('/show/{id}', [LaporanController::class, 'show'])->name('laporan.show');
            Route::get('/create', [LaporanController::class, 'create'])->name('laporan.create');
            Route::post('/store', [LaporanController::class, 'store'])->name('laporan.store');
            // Route::get('/edit/{id}', [LaporanController::class, 'edit'])->name('laporan.edit');
            // Route::put('/update/{id}', [LaporanController::class, 'update'])->name('laporan.update');
            // Route::get('/delete/{id}', [LaporanController::class, 'delete'])->name('laporan.delete');
            // Route::delete('/destroy/{id}', [LaporanController::class, 'destroy'])->name('laporan.destroy');
            Route::get('/get-lantai/{idGedung}', [LaporanController::class, 'getLantai']);
            Route::get('/get-ruangan/{idLantai}', [LaporanController::class, 'getRuangan']);
            Route::get('/get-fasilitas/{idRuangan}', [LaporanController::class, 'getFasilitas']);
            Route::get('/{id}/verifikasi', [LaporanController::class, 'formByLaporan'])->name('laporan.verifikasi.form');
            Route::post('/verifikasi', [LaporanController::class, 'storeByLaporan'])->name('laporan.verifikasi.store');
        });

        Route::prefix('riwayat-laporan')->group(function(){
            Route::get('/', [RiwayatLaporanFasilitasController::class, 'index'])->name('riwayat.index');
            Route::get('/list', [RiwayatLaporanFasilitasController::class, 'list'])->name('riwayat.list');
            Route::get('/{id}/riwayat', [RiwayatLaporanFasilitasController::class, 'show'])->name('riwayat.show');
            Route::get('/detail-modal/{id}', [RiwayatLaporanFasilitasController::class,'detailModal'])->name('riwayat.detailModal');
            Route::delete('/{id}', [RiwayatLaporanFasilitasController::class, 'destroy'])->name('riwayat.destroy');
            Route::get('/exportRekap', [RiwayatLaporanFasilitasController::class, 'exportRekap'])->name('riwayat.exportRekap');
            Route::get('/chartTren', [RiwayatLaporanFasilitasController::class, 'chartTren'])->name('chart.mingguan');
        });


        Route::prefix('spk')->group(function(){
            Route::get('/', [TopsisController::class,'index'])->name('spk.index');
            Route::get('/alternatif/list', [TopsisController::class,'listAlternatif'])->name('spk.alternatif.list');
            Route::post('/hitung', [TopsisController::class, 'hitung'])->name('spk.hitung');
            Route::get('/{id}/edit', [TopsisController::class, 'edit'])->name('spk.edit');
            Route::put('/{id}', [TopsisController::class, 'update'])->name('spk.update');
        });

        Route::prefix('skor-topsis')->group(function() {
            Route::get('/', [SkorTopsisController::class, 'index'])->name('skorTopsis.index');
            Route::get('/list', [SkorTopsisController::class, 'list'])->name('skorTopsis.list');
            Route::get('/{id}/assign', [SkorTopsisController::class,'assignForm'])->name('skorTopsis.assignForm');
            Route::post('/{id}/assign', [SkorTopsisController::class,'assign'])->name('skorTopsis.assign');
        });

        Route::prefix('verifikasi-perbaikan')->middleware('role:ADM,SPR')->group(function(){
            Route::get('{id}/form', [PenugasanController::class,'verifyForm'])->name('verifikasi.perbaikan.form');
            Route::post('{id}/form', [PenugasanController::class,'verify'])->name('verifikasi.perbaikan.submit');
        });

    });


    Route::middleware(['role:ADM,TNS'])->group(function(){
        Route::prefix('penugasan')->group(function(){
            Route::get('/', [PenugasanController::class,'index'])->name('penugasan.index');
            Route::get('/list', [PenugasanController::class,'list'])->name('penugasan.list');
            Route::get('/{id}/show', [PenugasanController::class, 'perbaikanForm'])->name('penugasan.show');
        });

        Route::post('/{id}/perbaikan', [PenugasanController::class,'perbaikanSubmit'])->name('laporanFasilitas.perbaikan.submit');
        Route::get(
            '/riwayat-perbaikan-teknisi',
            [RiwayatPerbaikanTeknisiController::class, 'index']
        )->name('riwayat-perbaikan-teknisi.index');

        // JSON untuk DataTable (server-side)
        Route::get(
            '/riwayat-perbaikan-teknisi/list',
            [RiwayatPerbaikanTeknisiController::class, 'list']
        )->name('riwayat-perbaikan-teknisi.list');

        // Show/detail (modal) — memanggil partial show yang sudah dibuat
        Route::get(
            '/riwayat-perbaikan-teknisi/{perbaikan}',
            [RiwayatPerbaikanTeknisiController::class, 'show']
        )->name('riwayat-perbaikan-teknisi.show');
    });

    // Route::middleware(['role:ADM,SPR'])->prefix('riwayat')->group(function () {
    //     Route::get('/', [RiwayatVerifikasiController::class, 'index'])->name('riwayat.index');
    //     Route::get('/list', [RiwayatVerifikasiController::class, 'list'])->name('riwayat.list');
    //     Route::get('/{id}', [RiwayatVerifikasiController::class, 'show'])->name('riwayat.show');
    // });


    Route::middleware(['role:MHS,DSN,TDK'])->group(function(){
        Route::prefix('laporanPelapor')->group(function(){
            Route::get('/', [LaporanController::class, 'indexPelapor'])->name('laporanPelapor.index');
            Route::get('/list', [LaporanController::class, 'listPelapor'])->name('laporanPelapor.list');
            Route::get('/show/{id}', [LaporanController::class, 'show'])->name('laporanPelapor.show');
            Route::get('/create', [LaporanController::class, 'create'])->name('laporanPelapor.create');
            Route::post('/store', [LaporanController::class, 'store'])->name('laporanPelapor.store');
            Route::get('/get-lantai/{idGedung}', [LaporanController::class, 'getLantai']);
            Route::get('/get-ruangan/{idLantai}', [LaporanController::class, 'getRuangan']);
            Route::get('/get-fasilitas/{idRuangan}', [LaporanController::class, 'getFasilitas']);
        });

        Route::prefix('vote')->group(function(){
            Route::post('/{id}/vote', [PelaporLaporanFasilitasController::class, 'vote'])->name('vote.store');
            Route::delete('/{id}/unvote', [PelaporLaporanFasilitasController::class, 'unvote'])->name('vote.destroy');
        });

        Route::prefix('riwayatPelapor')->group(function(){
            Route::get('/',    [RiwayatPelaporController::class,'index'])->name('riwayatPelapor.index');
            Route::get('/{id}',[RiwayatPelaporController::class,'show'])->name('riwayatPelapor.show');
            Route::get('/{id}/edit',  [RiwayatPelaporController::class,'edit'])->name('riwayatPelapor.edit');
            Route::put('/{id}', [RiwayatPelaporController::class,'update'])->name('riwayatPelapor.update');
        });

        Route::prefix('feedback')->group(function(){
            Route::get('{id}/create', [PenilaianPenggunaController::class, 'create'])->name('feedback.create');
            Route::post('{id}/store', [PenilaianPenggunaController::class, 'store'])->name('feedback.store');
            Route::get('{id}/edit', [PenilaianPenggunaController::class, 'edit'])->name('feedback.edit');
            Route::put('{id}/update', [PenilaianPenggunaController::class, 'update'])->name('feedback.update');
            Route::delete('{id}/destroy', [PenilaianPenggunaController::class, 'destroy'])->name('feedback.delete');
        });

        Route::prefix('notifikasi')->group(function(){
            Route::get('/', [NotifikasiController::class, 'index'])->name('notifikasi.index');
            Route::post('/{id}/read', [NotifikasiController::class, 'markAsRead'])->name('notifikasi.markAsRead');
            Route::post('/read-all', [NotifikasiController::class, 'markAllAsRead'])->name('notifikasi.markAllAsRead');
            Route::get('/{id}', [NotifikasiController::class, 'show'])->name('notifikasi.show');
            Route::delete('/{id}', [NotifikasiController::class, 'destroy'])->name('notifikasi.destroy');

            // API untuk mendapatkan jumlah notifikasi belum dibaca (untuk badge)
            Route::get('/api/unread-count', [NotifikasiController::class, 'getUnreadCount'])->name('notifikasi.unread-count');
        });

        Route::get('/dashboard-pelapor', [PelaporDashboardController::class, 'index'])->name('dashboard-pelapor.index');
    });


    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/update-photo', [ProfileController::class, 'update_photo'])->name('profile.update_photo');
        Route::post('/update-info', [ProfileController::class, 'update_info'])->name('profile.update_info');
        Route::post('/update-password', [ProfileController::class, 'update_password'])->name('profile.update_password');
    });

});


Route::get('/icons', function () {
    return view('pages.icons.index');
});

Route::get('/forms', function () {
    return view('pages.forms.index');
});
Route::get('/charts', function () {
    return view('pages.chart.index');
});
