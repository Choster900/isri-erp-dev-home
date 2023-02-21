<?php
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::group(['middleware' => ['auth','access']], function () {
    Route::get('adm/usuarios', function () {
        return Inertia::render('Administracion/Usuarios', [
            'menu' => session()->get('menu')
        ]);
    })->name('adm.usuarios');
    Route::get('adm/roles', function () {
        return Inertia::render('Administracion/Roles', [
            'menu' => session()->get('menu')
        ]);
    })->name('adm.roles');
});