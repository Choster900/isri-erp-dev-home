<?php
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::group(['middleware' => ['auth','access']], function () {
    Route::get('rrhh/consultar', function () {
        return Inertia::render('RRHH/ConsultaEmpleados', [
            'menu' => session()->get('menu')
        ]);
    })->name('consulta.empleados');

    Route::get('rrhh/empleados', function () {
        return Inertia::render('RRHH/GestionEmpleados', [
            'menu' => session()->get('menu')
        ]);
    })->name('gestion.empleados');
});