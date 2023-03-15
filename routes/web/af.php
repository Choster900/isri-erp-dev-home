<?php
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ActivoFijo\MarcaController;

Route::group(['middleware' => ['auth','access']], function () {
    Route::get('af/marcas', function () {
        return Inertia::render('ActivoFijo/Marcas', [
            'menu' => session()->get('menu')
        ]);
    })->name('af.marcas');
    
    Route::get('af/mayores', function () {
        return Inertia::render('ActivoFijo/BienesMayores', [
            'menu' => session()->get('menu')
        ]);
    })->name('mayores');

    Route::get('af/catalogo1', function () {
        return Inertia::render('ActivoFijo/Catalogo1', [
            'menu' => session()->get('menu')
        ]);
    })->name('catalogo1');

    Route::get('af/catalogo2', function () {
        return Inertia::render('ActivoFijo/Catalogo2', [
            'menu' => session()->get('menu')
        ]);
    })->name('catalogo2');

    Route::get('af/catalogo3', function () {
        return Inertia::render('ActivoFijo/Catalogo3', [
            'menu' => session()->get('menu')
        ]);
    })->name('catalogo3');

    Route::get('padre/hijo1', function () {
        return Inertia::render('ActivoFijo/Hijo1AF', [
            'menu' => session()->get('menu')
        ]);
    })->name('hijo1af');

    //Manage Marcas
    //This route obtains marcas based on the parameters sent from the page.
    Route::get('marcas', [MarcaController::class, 'getMarcas'])->name('marca.getMarcas');
    Route::post('change-state-brand', [MarcaController::class, 'changeStateBrand'])->name('marca.changeStateBrand');
    Route::post('save-brand', [MarcaController::class, 'saveBrand'])->name('user.saveBrand');
    Route::post('update-brand', [MarcaController::class, 'updateBrand'])->name('marca.updateBrand');
});