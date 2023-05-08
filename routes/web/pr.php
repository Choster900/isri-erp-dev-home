<?php
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::group(['middleware' => ['auth','access']], function () {
    Route::get('/pr/paac', function () {
        return Inertia::render('Presupuesto/GestionPAAC', [
            'menu' => session()->get('menu')
        ]);
    })->name('gestion.paac');
});