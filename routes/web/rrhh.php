<?php

use App\Http\Controllers\RRHH\AcuerdoController;
use App\Http\Controllers\RRHH\DetallePlazaController;
use App\Http\Controllers\RRHH\BeneficiarioController;
use App\Http\Controllers\RRHH\DirectorCentroController;
use App\Http\Controllers\RRHH\EmpleadoController;
use App\Http\Controllers\RRHH\EvaluacionController;
use App\Http\Controllers\RRHH\ExpedienteController;
use App\Http\Controllers\RRHH\GerenteGeneralController;
use App\Http\Controllers\RRHH\HojaServicioController;
use App\Http\Controllers\RRHH\JefeInmediatoController;
use App\Http\Controllers\RRHH\PermisoController;
use App\Http\Controllers\RRHH\SolicitudPermisoController;
use App\Http\Controllers\RRHH\SubDirectorMedicoController;
use App\Models\EvaluacionRendimiento;
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
    Route::post('get-permission-info-by-id', [PermisoController::class, 'getPermissionInfoById'])->name('permiso.getPermissionInfoById');
    Route::post('delete-permission', [PermisoController::class, 'deletePermission'])->name('permiso.deletePermission');
    Route::post('send-permission', [PermisoController::class, 'sendPermission'])->name('solicPermiso.sendPermission');


    Route::get(
        '/rrhh/evaluaciones',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/rrhh/evaluaciones', 'RRHH/Evaluaciones');
        }
    )->name('rrhh.evaluaciones');
    Route::post('evaluaciones', [EvaluacionController::class, 'getEvaluaciones'])->name('evaluaciones.getEvaluaciones');
    Route::post('search-employees-for-evaluations', [EvaluacionController::class, 'searchEmployeesForNewEvaluationRequest'])->name('evaluaciones.search-employees');
    Route::post('create-new-evaluacion', [EvaluacionController::class, 'createNewEvaluation'])->name('evaluaciones.new-evaluacion');
    Route::post('get-evaluacion', [EvaluacionController::class, 'getPersonalPerformanceEvaluationVersion'])->name('evaluaciones.get-evaluacion');
    Route::post('save-response', [EvaluacionController::class, 'saveResponseInEvaluation'])->name('evaluaciones.save-response');
    Route::post('get-by-id', [EvaluacionController::class, 'getEvaluationById'])->name('evaluaciones.get-by-id');

    Route::get(
        '/rrhh/jefe-inmediato',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/rrhh/jefe-inmediato', 'RRHH/JefeInmediato');
        }
    )->name('rrhh.jefeInmediato');
    Route::post('get-jefe-inmediato', [JefeInmediatoController::class, 'getReqJefeInmediato'])->name('jefInmediato.getReqJefeInmediato');
    Route::post('supervisor-approval', [JefeInmediatoController::class, 'setSupervisorApproval'])->name('jefInmediato.setSupervisorApproval');
    Route::post('supervisor-denial', [JefeInmediatoController::class, 'setSupervisorDenial'])->name('jefInmediato.setSupervisorDenial');

    Route::get(
        '/rrhh/director-centro',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/rrhh/director-centro', 'RRHH/DirectorCentro');
        }
    )->name('rrhh.directorCentro');
    Route::post('get-requests-director-centro', [DirectorCentroController::class, 'getReqDirectorCentro'])->name('dirCentro.getReqDirectorCentro');
    Route::post('director-approval', [DirectorCentroController::class, 'setDirectorApproval'])->name('dirCentro.setDirectorApproval');
    Route::post('director-denial', [DirectorCentroController::class, 'setDirectorDenial'])->name('dirCentro.setDirectorDenial');

    Route::get(
        '/rrhh/sub-direccion-medica',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/rrhh/sub-direccion-medica', 'RRHH/SubDirectorMedico');
        }
    )->name('rrhh.subDireccionMedica');
    Route::post('get-requests-sub-director-medico', [SubDirectorMedicoController::class, 'getReqSubDirMedico'])->name('subDirMedico.getReqSubDirMedico');
    Route::post('medical-management-approval', [SubDirectorMedicoController::class, 'setMedicalManagementApproval'])->name('subDirMedico.setMedicalManagementApproval');
    Route::post('medical-management-denial', [SubDirectorMedicoController::class, 'setMedicalManagementDenial'])->name('subDirMedico.setMedicalManagementDenial');


    Route::get(
        '/rrhh/gerente-general',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/rrhh/gerente-general', 'RRHH/GerenteGeneral');
        }
    )->name('rrhh.gerenteGeneral');
    Route::post('get-requests-gerente-general', [GerenteGeneralController::class, 'getReqGerenteGeneral'])->name('gerGeneral.getReqGerenteGeneral');
    Route::post('general-management-approval', [GerenteGeneralController::class, 'setGeneralManagementApproval'])->name('gerGeneral.setGeneralManagementApproval');
    Route::post('general-management-denial', [GerenteGeneralController::class, 'setGeneralManagementDenial'])->name('gerGeneral.setGeneralManagementDenial');

    Route::get(
        '/rrhh/expedientes',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/rrhh/expedientes', 'RRHH/Expedientes');
        }
    )->name('rrhh.expedientes');
    Route::post('expedientes', [ExpedienteController::class, 'getEmployeeExpediente'])->name('expediente.getEmployeeExpediente');

});
