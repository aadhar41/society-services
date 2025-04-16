<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

// Route Group For Society Module.
Route::prefix('society')->group(function () {
    // Society Module
    Route::get('/create', [App\Http\Controllers\SocietyController::class, 'create'])->name('admin.society.create');
    Route::post('/store', [App\Http\Controllers\SocietyController::class, 'store'])->name('admin.society.store');
    Route::get('/{society}/edit', [App\Http\Controllers\SocietyController::class, 'edit'])->name('admin.society.edit');
    Route::put('/update/{society}', [App\Http\Controllers\SocietyController::class, 'update'])->name('admin.society.update');
    Route::get('/list', [App\Http\Controllers\SocietyController::class, 'index'])->name('admin.society.list');

    Route::get('/enable/{id}', [App\Http\Controllers\SocietyController::class, 'enable'])->name('admin.society.enable');
    Route::get('/disable/{id}', [App\Http\Controllers\SocietyController::class, 'disable'])->name('admin.society.disable');

    Route::get(
        '/datatable',
        [App\Http\Controllers\SocietyController::class, 'datatable']
    )->name('society.datatables');

    Route::get(
        '/delete/{id}',
        [App\Http\Controllers\SocietyController::class, 'destroy']
    )->name('society.delete');
});


// Route Group For Block Module.
Route::prefix('block')->group(function () {
    // Block Module
    Route::get('/create', [App\Http\Controllers\BlockController::class, 'create'])->name('admin.block.create');
    Route::post('/store', [App\Http\Controllers\BlockController::class, 'store'])->name('admin.block.store');
    Route::get('/{block}/edit', [App\Http\Controllers\BlockController::class, 'edit'])->name('admin.block.edit');
    Route::put('/update/{block}', [App\Http\Controllers\BlockController::class, 'update'])->name('admin.block.update');
    Route::get('/list', [App\Http\Controllers\BlockController::class, 'index'])->name('admin.block.list');

    Route::get('/enable/{id}', [App\Http\Controllers\BlockController::class, 'enable'])->name('admin.block.enable');
    Route::get('/disable/{id}', [App\Http\Controllers\BlockController::class, 'disable'])->name('admin.block.disable');

    Route::get(
        '/datatable',
        [App\Http\Controllers\BlockController::class, 'datatable']
    )->name('block.datatables');

    Route::get(
        '/delete/{id}',
        [App\Http\Controllers\BlockController::class, 'destroy']
    )->name('block.delete');
});


// Route Group For Plot Module.
Route::prefix('plot')->group(function () {
    // Plot Module
    Route::get('/create', [App\Http\Controllers\PlotController::class, 'create'])->name('admin.plot.create');
    Route::post('/store', [App\Http\Controllers\PlotController::class, 'store'])->name('admin.plot.store');
    Route::get('/{plot}/edit', [App\Http\Controllers\PlotController::class, 'edit'])->name('admin.plot.edit');
    Route::put('/update/{plot}', [App\Http\Controllers\PlotController::class, 'update'])->name('admin.plot.update');
    Route::get('/list', [App\Http\Controllers\PlotController::class, 'index'])->name('admin.plot.list');

    Route::get('/enable/{id}', [App\Http\Controllers\PlotController::class, 'enable'])->name('admin.plot.enable');
    Route::get('/disable/{id}', [App\Http\Controllers\PlotController::class, 'disable'])->name('admin.plot.disable');

    Route::get(
        '/datatable',
        [App\Http\Controllers\PlotController::class, 'datatable']
    )->name('plot.datatables');

    Route::get(
        '/delete/{id}',
        [App\Http\Controllers\PlotController::class, 'destroy']
    )->name('plot.delete');
});


// Route Group For Flat Module.
Route::prefix('flat')->group(function () {
    // Flat Module
    Route::get('/create', [App\Http\Controllers\FlatController::class, 'create'])->name('admin.flat.create');
    Route::post('/store', [App\Http\Controllers\FlatController::class, 'store'])->name('admin.flat.store');
    Route::get('/{flat}/edit', [App\Http\Controllers\FlatController::class, 'edit'])->name('admin.flat.edit');
    Route::put('/update/{flat}', [App\Http\Controllers\FlatController::class, 'update'])->name('admin.flat.update');
    Route::get('/list', [App\Http\Controllers\FlatController::class, 'index'])->name('admin.flat.list');

    Route::get('/enable/{id}', [App\Http\Controllers\FlatController::class, 'enable'])->name('admin.flat.enable');
    Route::get('/disable/{id}', [App\Http\Controllers\FlatController::class, 'disable'])->name('admin.flat.disable');

    Route::get(
        '/datatable',
        [App\Http\Controllers\FlatController::class, 'datatable']
    )->name('flat.datatables');

    Route::get(
        '/delete/{id}',
        [App\Http\Controllers\FlatController::class, 'destroy']
    )->name('flat.delete');
});

// Route Group For Maintenance Module.
Route::prefix('maintenance')->group(function () {
    // Maintenance Module
    Route::get('/create', [App\Http\Controllers\MaintenanceController::class, 'create'])->name('admin.maintenance.create');
    Route::post('/store', [App\Http\Controllers\MaintenanceController::class, 'store'])->name('admin.maintenance.store');
    Route::get('/{payment}/edit', [App\Http\Controllers\MaintenanceController::class, 'edit'])->name('admin.maintenance.edit');
    Route::put('/update/{payment}', [App\Http\Controllers\MaintenanceController::class, 'update'])->name('admin.maintenance.update');
    Route::get('/list', [App\Http\Controllers\MaintenanceController::class, 'index'])->name('admin.maintenance.list');
    Route::get('/show/{id}', [App\Http\Controllers\MaintenanceController::class, 'show'])->name('admin.maintenance.show');

    Route::get('/enable/{id}', [App\Http\Controllers\MaintenanceController::class, 'enable'])->name('admin.maintenance.enable');
    Route::get('/disable/{id}', [App\Http\Controllers\MaintenanceController::class, 'disable'])->name('admin.maintenance.disable');

    Route::get(
        '/datatable',
        [App\Http\Controllers\MaintenanceController::class, 'datatable']
    )->name('maintenance.datatables');

    Route::get(
        '/delete/{id}',
        [App\Http\Controllers\MaintenanceController::class, 'destroy']
    )->name('maintenance.delete');
});



// Route Group For Payment Module.
// Route::prefix('payment')->group(function () {
//     // Payment Module
//     Route::get('/create', [App\Http\Controllers\PaymentController::class, 'create'])->name('admin.payment.create');
//     Route::post('/store', [App\Http\Controllers\PaymentController::class, 'store'])->name('admin.payment.store');
//     Route::get('/{payment}/edit', [App\Http\Controllers\PaymentController::class, 'edit'])->name('admin.payment.edit');
//     Route::put('/update/{payment}', [App\Http\Controllers\PaymentController::class, 'update'])->name('admin.payment.update');
//     Route::get('/list', [App\Http\Controllers\PaymentController::class, 'index'])->name('admin.payment.list');

//     Route::get('/enable/{id}', [App\Http\Controllers\PaymentController::class, 'enable'])->name('admin.payment.enable');
//     Route::get('/disable/{id}', [App\Http\Controllers\PaymentController::class, 'disable'])->name('admin.payment.disable');

//     Route::get(
//         '/datatable',
//         [App\Http\Controllers\PaymentController::class, 'datatable']
//     )->name('payment.datatables');

//     Route::get(
//         '/delete/{id}',
//         [App\Http\Controllers\PaymentController::class, 'destroy']
//     )->name('payment.delete');
// });




// Ajax Route
Route::post('getSocietyBlocks', [App\Http\Controllers\FlatController::class, 'getSocietyBlocks'])->name('getSocietyBlocks');
Route::post('getBlockPlots', [App\Http\Controllers\FlatController::class, 'getBlockPlots'])->name('getBlockPlots');
Route::post('getPlotsFlats', [App\Http\Controllers\FlatController::class, 'getPlotsFlats'])->name('getPlotsFlats');