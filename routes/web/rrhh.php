<?php

use App\Http\Controllers\RRHH\BeneficiarioController;
use App\Http\Controllers\RRHH\EmpleadoController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::group(['middleware' => ['auth', 'access']], function () {
    Route::get(
        '/rrhh/empleados',
        function () {
            return Inertia::render('RRHH/Empleados', [
                'menu' => session()->get('menu')
            ]);
        }
    )->name('rrhh.empleados');

    Route::post('employees', [EmpleadoController::class, 'getEmployees'])->name('empleado.getEmployees');
    Route::get('search-person-by-dui', [EmpleadoController::class, 'searchPersonByDUI'])->name('empleado.seachPersonByDUI');
    Route::get('get-selects-options-employee', [EmpleadoController::class, 'getSelectOptionsEmployee'])->name('empleado.getSelectOptionsEmployee');

    //
    Route::get(
        '/rrhh/beneficiarios',
        function () {
            return Inertia::render('RRHH/Beneficiarios', [
                'menu' => session()->get('menu')
            ]);
        }
    )->name('rrhh.beneficiarios');

    Route::get('search-people-by-name/{name}', [BeneficiarioController::class, 'searchPeopleByName'])->name('empleado.seachPersonByName');




});