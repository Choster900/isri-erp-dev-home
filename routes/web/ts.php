<?php
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Tesoreria\ProveedorController;
use App\Http\Controllers\Tesoreria\ConceptoIngresoController;

Route::group(['middleware' => ['auth', 'access']], function () {

    //Manage supplier
    //This route obtains supplier based on the parameters sent from the page.
    Route::get(
        '/ts/proveedores',
        function () {
            return Inertia::render('Tesoreria/Proveedores', [
                'menu' => session()->get('menu')
            ]);
        }
    )->name('ts.proveedores');
    Route::get('proveedores', [ProveedorController::class, 'getProveedores'])->name('get.proveedores');
    Route::get('list-option-select-suppliers', [ProveedorController::class, 'getInformationToSelect'])->name('list-option-select-suppliers');
    Route::get('update-suplier', [ProveedorController::class, 'getProveedores'])->name('get.proveedores');
    Route::get('get-supplier', [ProveedorController::class, 'getDataSupplier'])->name('get.information-supplier');
    Route::post('update-supplier', [ProveedorController::class, 'updateDataSupplier'])->name('update-supplier');
    Route::post('add-supplier', [ProveedorController::class, 'addDataSupplier'])->name('add-supplier');
    Route::post('change-values-retencion/{id_sujeto_retencion}', [ProveedorController::class, 'changeValueRetencion'])->name('change-values-retencion');
    Route::post('update-state-supplier', [ProveedorController::class, 'changeStateSupplier'])->name('change-values-retencion');

    //Routes to manage income concept
    Route::get(
        '/ts/concepto-ingresos',
        function () {
            return Inertia::render('Tesoreria/ConceptoIngresos', [
                'menu' => session()->get('menu')
            ]);
        }
    )->name('ts.conceptoIngresos');
    Route::get('ingresos', [ConceptoIngresoController::class, 'getConceptoIngresos'])->name('ingreso.getConceptoIngresos');
    Route::post('change-state-income-concept', [ConceptoIngresoController::class, 'changeStateIncomeConcept'])->name('ingreso.changeStateIncomeConcept');
    Route::get('get-selects-income-concept', [ConceptoIngresoController::class, 'getSelectsIncomeConcept'])->name('ingreso.getSelectsIncomeConcept');
    Route::post('save-income-concept', [ConceptoIngresoController::class, 'saveIncomeConcept'])->name('ingreso.saveIncomeConcept');
    Route::post('update-income-concept', [ConceptoIngresoController::class, 'updateIncomeConcept'])->name('ingreso.updateIncomeConcept');

    //Routes to manage income
    Route::get(
        '/ts/ingresos',
        function () {
            return Inertia::render('Tesoreria/Ingresos', [
                'menu' => session()->get('menu')
            ]);
        }
    )->name('ts.ingresos');
});