<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Tesoreria\ProveedorController;
use App\Http\Controllers\Tesoreria\QuedanController;
use App\Http\Controllers\Tesoreria\RequerimientoController;
use App\Http\Controllers\Tesoreria\ConceptoIngresoController;
use App\Http\Controllers\Tesoreria\ReciboIngresoController;
use App\Http\Controllers\Tesoreria\LiquidacionQuedanController;

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


    //Manage quenda
    //This route obtains Quedan based on the parameters sent from the page.
    Route::get(
        '/ts/quedan',
        function () {
            return Inertia::render('Tesoreria/Quedan', [
                'menu' => session()->get('menu')
            ]);
        }
    )->name('ts.quedan');

    Route::get('quedan', [QuedanController::class, 'getDataQuedan'])->name('get.quedan');
    Route::get('get-list-select', [QuedanController::class, 'getListForSelect'])->name('get-list-select');
    Route::post('add-quedan', [QuedanController::class, 'addQuedan'])->name('add-quedan');
    Route::post('update-detalle-quedan', [QuedanController::class, 'updateDetalleQuedan'])->name('update-detalle-quedan');
    Route::get('getAllSuppliers', [QuedanController::class, 'getSuppliers'])->name('get-all-suppliers');


    //Manage requerimiento pago
    //This route obtains Requerimiento pago based on the parameters sent from the page.
    Route::get(
        '/ts/requerimientos',
        function () {
            return Inertia::render('Tesoreria/Requerimiento', [
                'menu' => session()->get('menu')
            ]);
        }
    )->name('ts.requerimientos');

    Route::get('requerimientos', [RequerimientoController::class, 'getRequerimientos'])->name('get.requerimientos');
    Route::post('add-requerimiento', [RequerimientoController::class, 'addRequerimientoNumber'])->name('add-requerimiento');
    Route::post('update-requerimiento', [RequerimientoController::class, 'updateRequerimientoNumber'])->name('update-requerimiento');
    Route::get('filter-quedan', [RequerimientoController::class, 'filterQuedan'])->name('filter-quedan');
    Route::post('add-numberRequest-quedan', [RequerimientoController::class, 'addANumerRequestToQuedan'])->name('add-numberRequest-quedan');
    Route::post('take-of-number-request', [RequerimientoController::class, 'takeOfNumberRequest'])->name('take-of-number-request');


    //Manage Asignacion de requerimiento
    //This route obtains Quedan de requerimiento based on the parameters sent from the page.
    Route::get(
        '/ts/asignacion',
        function () {
            return Inertia::render('Tesoreria/AsignacionRequerimiento', [
                'menu' => session()->get('menu')
            ]);
        }
    )->name('ts.asignacion');

    //Routes to manage income concept
    Route::get(
        '/ts/conceptos-ingreso',
        function () {
            return Inertia::render('Tesoreria/ConceptosIngreso', [
                'menu' => session()->get('menu')
            ]);
        }
    )->name('ts.conceptosIngreso');
    Route::get('ingresos', [ConceptoIngresoController::class, 'getConceptoIngresos'])->name('conceptoIngreso.getConceptoIngresos');
    Route::post('change-state-income-concept', [ConceptoIngresoController::class, 'changeStateIncomeConcept'])->name('conceptoIngreso.changeStateIncomeConcept');
    Route::get('get-selects-income-concept', [ConceptoIngresoController::class, 'getSelectsIncomeConcept'])->name('conceptoIngreso.getSelectsIncomeConcept');
    Route::post('save-income-concept', [ConceptoIngresoController::class, 'saveIncomeConcept'])->name('conceptoIngreso.saveIncomeConcept');
    Route::post('update-income-concept', [ConceptoIngresoController::class, 'updateIncomeConcept'])->name('conceptoIngreso.updateIncomeConcept');

    //Routes to manage income
    Route::get(
        '/ts/recibos-ingreso',
        function () {
            return Inertia::render('Tesoreria/RecibosIngreso', [
                'menu' => session()->get('menu')
            ]);
        }
    )->name('ts.recibosIngreso');
    Route::get('recibos-ingreso', [ReciboIngresoController::class, 'getRecibosIngreso'])->name('reciboIngreso.getRecibosIngreso');
    Route::post('change-state-income-receipt', [ReciboIngresoController::class, 'changeStateIncomeReceipt'])->name('reciboIngreso.changeStateIncomeReceipt');
    Route::get('get-modal-receipt-selects', [ReciboIngresoController::class, 'getModalReceiptSelects'])->name('reciboIngreso.getModalReceiptSelects');
    Route::get('get-income-concept', [ReciboIngresoController::class, 'getIncomeConcept'])->name('reciboIngreso.getIncomeConcept');
    Route::post('save-income-receipt', [ReciboIngresoController::class, 'saveIncomeReceipt'])->name('reciboIngreso.saveIncomeReceipt');
    Route::post('update-income-receipt', [ReciboIngresoController::class, 'updateIncomeReceipt'])->name('conceptoIngreso.updateIncomeReceipt');


    Route::get(
        '/ts/liquidacion',
        function () {
            return Inertia::render('Tesoreria/LiquidacionRequerimiento', [
                'menu' => session()->get('menu')
            ]);
        }
    )->name('ts.liquidacion');
    Route::post('liquidar-quedan', [LiquidacionQuedanController::class, 'processLiquidacionQuedan'])->name('liquidar-quedan');


});