<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use Inertia\Inertia;

Route::group(['middleware' => ['auth','access']], function () {
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
});