<?php

use App\Http\Controllers\RRHH\EmpleadoController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::group(['middleware' => ['auth','access']], function () {
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
    //Route::get('get-work-areas', [EmpleadoController::class, 'getWorkAreas'])->name('empleado.getWorkAreas');
    //Route::get('get-institutional-activities', [EmpleadoController::class, 'getInstitutionalActivities'])->name('empleado.getInstitutionalActivities');
    //Route::get('get-job-positions', [EmpleadoController::class, 'getJobPositions'])->name('empleado.getJobPositions');
    Route::post('store-employee', [EmpleadoController::class, 'storeEmployee'])->name('empleado.storeEmployee');
});