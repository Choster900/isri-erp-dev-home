<?php
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\ActivoFijo\MarcaController;
use App\Http\Controllers\ActivoFijo\ModeloController;
use App\Http\Controllers\ActivoFijo\BienEspecificoController;
use App\Http\Controllers\ActivoFijo\ActivoFijoController;

Route::group(['middleware' => ['auth','access']], function () {
    
    Route::get('af/mayores', function () {
        return Inertia::render('ActivoFijo/BienesMayores', [
            'menu' => session()->get('menu')
        ]);
    })->name('mayores');

    //Manage Marcas
    Route::get('af/marcas', function (Request $request) {
        return checkModuleAccessAndRedirect($request->user()->id_usuario,'/af/marcas','ActivoFijo/Marcas');
    })->name('af.marcas');
    Route::post('marcas', [MarcaController::class, 'getMarcas'])->name('marca.getMarcas');
    Route::post('change-state-brand', [MarcaController::class, 'changeStateBrand'])->name('marca.changeStateBrand');
    Route::post('save-brand', [MarcaController::class, 'saveBrand'])->name('marca.saveBrand');
    Route::post('update-brand', [MarcaController::class, 'updateBrand'])->name('marca.updateBrand');

    //Manage Modelos
    Route::get('af/modelos', function (Request $request) {
        return checkModuleAccessAndRedirect($request->user()->id_usuario,'/af/modelos','ActivoFijo/Modelos');
    })->name('af.modelos');
    Route::post('modelos', [ModeloController::class, 'getModelos'])->name('modelo.getModelos');
    Route::post('change-state-model', [ModeloController::class, 'changeStateModel'])->name('modelo.changeStateModel');
    Route::post('save-model', [ModeloController::class, 'saveModel'])->name('modelo.saveModel');
    Route::get('get-brands', [ModeloController::class, 'getBrands'])->name('modelo.getBrands');
    Route::post('update-model', [ModeloController::class, 'updateModel'])->name('modelo.updateModelo');

    //Manage Bien Espefico
    Route::get('af/bien-especifico', function (Request $request) {
        return checkModuleAccessAndRedirect($request->user()->id_usuario,'/af/bien-especifico','ActivoFijo/BienEspecifico');
    })->name('af.bienEspecifico');
    Route::post('bien-especifico', [BienEspecificoController::class, 'getBienEspecifico'])->name('bienEspecifico.getBienEspecifico');
    Route::post('change-state-specific-asset', [BienEspecificoController::class, 'changeStateSpecificAsset'])->name('bienEspecifico.changeStateSpecificAsset');
    Route::get('get-select-specific-asset', [BienEspecificoController::class, 'getSelectSpecificAsset'])->name('bienEspecifico.getSelectSpecificAsset');
    Route::post('save-specific-asset', [BienEspecificoController::class, 'saveSpecificAsset'])->name('bienEspecifico.saveSpecificAsset');
    Route::post('update-specific-asset', [BienEspecificoController::class, 'updateSpecificAsset'])->name('bienEspecifico.updateSpecificAsset');

    //Manage bienes muebles y vehiculos
    Route::get('af/muebles-vehiculos', function (Request $request) {
        return checkModuleAccessAndRedirect($request->user()->id_usuario,'/af/muebles-vehiculos','ActivoFijo/BienesMueblesYVehiculos');

    })->name('af.bienesMyV');
    Route::post('get-activos', [ActivoFijoController::class, 'getActivos'])->name('activoFijo.getActivos');
    Route::get('get-select-mv-asset', [ActivoFijoController::class, 'getSelectMVAsset'])->name('activoFijo.getSelectMVAsset');
    Route::post('save-mv-asset', [ActivoFijoController::class, 'saveMVAsset'])->name('activoFijo.saveMVAsset');
    Route::get('get-models', [ActivoFijoController::class, 'getModels'])->name('activoFijo.getModels');
});