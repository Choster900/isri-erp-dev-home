<?php

use App\Http\Controllers\RRHH\AcuerdoController;
use App\Http\Controllers\RRHH\DetallePlazaController;
use App\Http\Controllers\RRHH\BeneficiarioController;
use App\Http\Controllers\RRHH\EmpleadoController;
use App\Http\Controllers\RRHH\EvaluacionController;
use App\Http\Controllers\RRHH\HojaServicioController;
use App\Http\Controllers\RRHH\PermisoController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Inertia\Inertia;

Route::group(['middleware' => ['auth', 'access']], function () {
    Route::get(
        '/rrhh/empleados',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/rrhh/empleados', 'RRHH/Empleados');
        }
    )->name('rrhh.empleados');
    Route::post('employees', [EmpleadoController::class, 'getEmployees'])->name('empleado.getEmployees');
    Route::get('search-person-by-dui', [EmpleadoController::class, 'searchPersonByDUI'])->name('empleado.seachPersonByDUI');
    Route::get('get-selects-options-employee', [EmpleadoController::class, 'getSelectOptionsEmployee'])->name('empleado.getSelectOptionsEmployee');
    Route::post('store-employee', [EmpleadoController::class, 'storeEmployee'])->name('empleado.storeEmployee');
    Route::post('update-employee', [EmpleadoController::class, 'updateEmployee'])->name('empleado.updateEmployee');

    Route::post('upload-employee-photo', [EmpleadoController::class, 'uploadEmployeePhoto'])->name('empleado.uploadEmployeePhoto');
    Route::get('get-job-positios-by-employee', [EmpleadoController::class, 'getJobPositionsByEmployee'])->name('empleado.getJobPositionsByEmployee');
    Route::post('store-job-position', [EmpleadoController::class, 'storeJobPosition'])->name('empleado.storeJobPosition');

    Route::get(
        '/rrhh/det-plazas',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/rrhh/det-plazas', 'RRHH/DetallePlazas');
        }
    )->name('rrhh.detPlazas');
    Route::post('det-job-positions', [DetallePlazaController::class, 'getDetJobPositions'])->name('detallePlaza.getDetJobPositions');
    Route::get('get-selects-job-position-det', [DetallePlazaController::class, 'getSelectsJobPositionDet'])->name('detallePlaza.getSelectsJobPositionDet');
    Route::post('store-job-position-det', [DetallePlazaController::class, 'storeJobPositionDet'])->name('detallePlaza.storeJobPositionDet');
    Route::post('update-job-position-det', [DetallePlazaController::class, 'updateJobPositionDet'])->name('detallePlaza.updateJobPositionDet');
    Route::post('change-status-job-position-det', [DetallePlazaController::class, 'changeStatusJobPositionDet'])->name('detallePlaza.changeStatusJobPositionDet');

    Route::get(
        '/rrhh/beneficiarios',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/rrhh/beneficiarios', 'RRHH/Beneficiarios');
        }
    )->name('rrhh.beneficiarios');
    Route::post('beneficiarios', [BeneficiarioController::class, 'getDataFromBeneficiarios'])->name('beneficiarios.getEmployees');
    Route::post('search-people', [BeneficiarioController::class, 'searchPeopleByNameOrId'])->name('empleado.seachPersonByName');
    Route::post('add-relatives', [BeneficiarioController::class, 'addRelatives'])->name('add.relatives');
    Route::post('update-relatives', [BeneficiarioController::class, 'updateRelatives'])->name('update.relatives');



    Route::get(
        '/rrhh/acuerdos',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/rrhh/acuerdos', 'RRHH/Acuerdos');
        }
    )->name('rrhh.acuerdos');
    Route::post('acuerdos', [AcuerdoController::class, 'getAcuerdos'])->name('acuerdos.getAcuerdos');
    Route::post('search-employe', [AcuerdoController::class, 'searchEmployeByNameOrId'])->name('empleado.seachEmployeByName');
    Route::post('add-deals', [AcuerdoController::class, 'addDeals'])->name('add.deals');
    Route::post('update-deals', [AcuerdoController::class, 'updateDeals'])->name('update.deals');


    Route::get(
        '/rrhh/hoja-servicios',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/rrhh/hoja-servicios', 'RRHH/HojaServicios');
        }
    )->name('rrhh.hoja-servicios');
    Route::post('search-employees', [HojaServicioController::class, 'getEmployees'])->name('empleado.SearchEmployees');

    Route::get(
        '/rrhh/permisos',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/rrhh/permisos', 'RRHH/Permisos');
        }
    )->name('rrhh.permisos');
    Route::post('job-permissions', [PermisoController::class, 'getJobPermissions'])->name('permiso.getJobPermissions');
    Route::get('get-data-permission-modal/{id_empleado}', [PermisoController::class, 'getDataPermissionModal'])->name('permiso.getDataPermissionModal');
    Route::get('get-permission-data/{id_empleado}', [PermisoController::class, 'getPermissionData'])->name('permiso.getPermissionData');
    Route::post('store-employee-permission', [PermisoController::class, 'storeEmployeePermission'])->name('permiso.storeEmployeePermission');
    Route::post('update-employee-permission', [PermisoController::class, 'updateEmployeePermission'])->name('permiso.updateEmployeePermission');

    Route::get(
        '/rrhh/evaluaciones',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/rrhh/evaluaciones', 'RRHH/Evaluaciones');
        }
    )->name('rrhh.evaluaciones');
    Route::get('get-evaluacion-rendimiento', [EvaluacionController::class, 'getEvaluacionRendimiento'])->name('evaluacion.rendimiento');

});
