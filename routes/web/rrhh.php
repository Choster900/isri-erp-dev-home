<?php

use App\Http\Controllers\RRHH\DetallePlazaController;
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
    Route::post('store-employee', [EmpleadoController::class, 'storeEmployee'])->name('empleado.storeEmployee');
    Route::post('update-employee', [EmpleadoController::class, 'updateEmployee'])->name('empleado.updateEmployee');

    Route::get(
        '/rrhh/det-plazas',
        function () {
            return Inertia::render('RRHH/DetallePlazas', [
                'menu' => session()->get('menu')
            ]);
        }
    )->name('rrhh.detPlazas');
    Route::post('det-job-positions', [DetallePlazaController::class, 'getDetJobPositions'])->name('detallePlaza.getDetJobPositions');
    Route::get('get-selects-job-position-det', [DetallePlazaController::class, 'getSelectsJobPositionDet'])->name('detallePlaza.getSelectsJobPositionDet');
    Route::post('store-job-position-det', [DetallePlazaController::class, 'storeJobPositionDet'])->name('detallePlaza.storeJobPositionDet');
    Route::post('update-job-position-det', [DetallePlazaController::class, 'updateJobPositionDet'])->name('detallePlaza.updateJobPositionDet');

    Route::get(
        '/rrhh/beneficiarios',
        function () {
            return Inertia::render('RRHH/Beneficiarios', [
                'menu' => session()->get('menu')
            ]);
        }
    )->name('rrhh.beneficiarios');



    Route::get(
        '/rrhh/crearBeneficiario',
        function () {
            return Inertia::render('RRHH/Beneficiarios', [
                'menu' => session()->get('menu')
            ]);
        }
    )->name('rrhh.crearBeneficiario');

    Route::post('beneficiarios', [BeneficiarioController::class, 'getDataNemefocoarops'])->name('beneficiarios.getEmployees');
    Route::post('search-people', [BeneficiarioController::class, 'searchPeopleByNameOrId'])->name('empleado.seachPersonByName');
    Route::post('add-relatives', [BeneficiarioController::class, 'addRelatives'])->name('add.relatives');
    Route::post('update-relatives', [BeneficiarioController::class, 'updateRelatives'])->name('update.relatives');
});


/* Route::get(
    '/rrhh/crearBeneficiario',
    [BeneficiarioController::class, 'indexBefe']
)->name('rrhh.crearBeneficiario'); */

/* Route::get(
    '/rrhh/crearBeneficiario',
    function () {
        return Inertia::render('RRHH/Beneficiarios', [
            'menu' => session()->get('menu')
        ]);
    }
)->name('rrhh.beneficiarios'); */
