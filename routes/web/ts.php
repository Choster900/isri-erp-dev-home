<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Tesoreria\ProveedorController;
use App\Http\Controllers\Tesoreria\QuedanController;
use App\Http\Controllers\Tesoreria\RequerimientoController;
use App\Http\Controllers\Tesoreria\ConceptoIngresoController;
use App\Http\Controllers\Tesoreria\ReciboIngresoController;
use App\Http\Controllers\Tesoreria\LiquidacionQuedanController;
use App\Http\Controllers\Tesoreria\ReporteTesoreriaController;
use App\Http\Controllers\Tesoreria\DocumentoAdquisicionController;

Route::group(['middleware' => ['auth']], function () {

    //Manage supplier
    //This route obtains supplier based on the parameters sent from the page.
    Route::get(
        '/ts/proveedores',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario,'/ts/proveedores','Tesoreria/Proveedores');
        }
    )->name('ts.proveedores');
    Route::post('proveedores', [ProveedorController::class, 'getProveedores'])->name('proveedor.getProveedor');
    Route::get('list-option-select-suppliers', [ProveedorController::class, 'getInformationToSelect'])->name('proveedor.getInformationToSelect');
    Route::post('update-supplier', [ProveedorController::class, 'updateDataSupplier'])->name('proveedor.updateDataSupplier');
    Route::post('add-supplier', [ProveedorController::class, 'addDataSupplier'])->name('proveedor.addDataSupplier');
    Route::post('change-values-retencion/{id_sujeto_retencion}', [ProveedorController::class, 'changeValueRetencion'])->name('proveedor.changeValueRetencion');
    Route::post('update-state-supplier', [ProveedorController::class, 'changeStateSupplier'])->name('proveedor.changeStateSupplier');


    //Manage quenda
    //This route obtains Quedan based on the parameters sent from the page.
    Route::get(
        '/ts/quedan',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario,'/ts/quedan','Tesoreria/Quedan');
        }
    )->name('ts.quedan');

    Route::post('quedan', [QuedanController::class, 'getDataQuedan'])->name('get.quedan');
    Route::get('get-list-select', [QuedanController::class, 'getListForSelect'])->name('get-list-select');
    Route::post('add-quedan', [QuedanController::class, 'addQuedan'])->name('add-quedan');
    Route::post('update-detalle-quedan', [QuedanController::class, 'updateDetalleQuedan'])->name('update-detalle-quedan');
    Route::post('updateFechaRetencionIva', [QuedanController::class, 'updateFechaRetencionIva'])->name('update-fecha-liquidacion');
    Route::post('getAmountByDocumentDetail', [QuedanController::class, 'getAmountByDet'])->name('get-amount-by-supplier');


    //Manage requerimiento pago
    //This route obtains Requerimiento pago based on the parameters sent from the page.
    Route::get(
        '/ts/requerimientos',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario,'/ts/requerimientos','Tesoreria/Requerimiento');
        }
    )->name('ts.requerimientos');

    Route::post('requerimientos', [RequerimientoController::class, 'getRequerimientos'])->name('get.requerimientos');
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
            return Inertia::render('Tesoreria/AsignacionLiquidacionRequerimiento', [
                'menu' => session()->get('menu')
            ]);
        }
    )->name('ts.asignacion');

    //Routes to manage income concept
    Route::get(
        '/ts/conceptos-ingreso',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario,'/ts/conceptos-ingreso','Tesoreria/ConceptosIngreso');
        }
    )->name('ts.conceptosIngreso');
    Route::post('ingresos', [ConceptoIngresoController::class, 'getConceptoIngresos'])->name('conceptoIngreso.getConceptoIngresos');
    Route::post('change-state-income-concept', [ConceptoIngresoController::class, 'changeStateIncomeConcept'])->name('conceptoIngreso.changeStateIncomeConcept');
    Route::post('save-income-concept', [ConceptoIngresoController::class, 'saveIncomeConcept'])->name('conceptoIngreso.saveIncomeConcept');
    Route::post('update-income-concept', [ConceptoIngresoController::class, 'updateIncomeConcept'])->name('conceptoIngreso.updateIncomeConcept');
    //New route for Composition API
    Route::get('get-info-modal-concepto-ingreso/{id}', [ConceptoIngresoController::class, 'getInfoModalConceptoIngreso'])->name('conceptoIngreso.getInfoModalConceptoIngreso');

    //Routes to manage income
    Route::get(
        '/ts/recibos-ingreso',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario,'/ts/recibos-ingreso','Tesoreria/RecibosIngreso');
        }
    )->name('ts.recibosIngreso');
    Route::post('recibos-ingreso', [ReciboIngresoController::class, 'getRecibosIngreso'])->name('reciboIngreso.getRecibosIngreso');
    Route::post('change-state-income-receipt', [ReciboIngresoController::class, 'changeStateIncomeReceipt'])->name('reciboIngreso.changeStateIncomeReceipt');
    Route::get('get-income-concept', [ReciboIngresoController::class, 'getIncomeConcept'])->name('reciboIngreso.getIncomeConcept');
    Route::post('save-income-receipt', [ReciboIngresoController::class, 'saveIncomeReceipt'])->name('reciboIngreso.saveIncomeReceipt');
    Route::post('update-income-receipt', [ReciboIngresoController::class, 'updateIncomeReceipt'])->name('conceptoIngreso.updateIncomeReceipt');
    Route::get('get-select-financing-source', [ReciboIngresoController::class, 'getSelectFinancingSource'])->name('reciboIngreso.getSelectFinancingSource');
    Route::get('get-select-income-concept', [ReciboIngresoController::class, 'getSelectIncomeConcept'])->name('reciboIngreso.getSelectIncomeConcept');
    //New route for Composition API
    Route::get('get-info-modal-recibo-ingreso/{id}', [ReciboIngresoController::class, 'getInfoModalReciboIngreso'])->name('reciboIngreso.getInfoModalReciboIngreso');
    Route::get('get-info-modal-recibo-format/{id}', [ReciboIngresoController::class, 'getInfoModalReciboFormat'])->name('reciboIngreso.getInfoModalReciboFormat');

    //Routes to manage acquisition doc
    Route::get(
        '/ts/doc-adquisicion',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario,'/ts/doc-adquisicion','Tesoreria/DocAdquisicion');
        }
    )->name('ts.docAdquisicion');
    Route::post('doc-adquisicion', [DocumentoAdquisicionController::class, 'getDocAdquisicion'])->name('documentoAdquisicion.getDocAdquisicion');
    Route::post('change-state-acq-doc', [DocumentoAdquisicionController::class, 'changeStateAcqdoc'])->name('documentoAdquisicion.changeStateAcqdoc');
    Route::post('save-acq-doc', [DocumentoAdquisicionController::class, 'saveAcqDoc'])->name('documentoAdquisicion.saveAcqDoc');
    Route::post('update-acq-doc', [DocumentoAdquisicionController::class, 'updateAcqDoc'])->name('documentoAdquisicion.updateAcqDoc');
    //New route for Composition API
    Route::get('get-info-modal-doc-adquisicion/{id}', [DocumentoAdquisicionController::class, 'getInfoModalDocAdquisicion'])->name('documentoAdquisicion.getInfoModalDocAdquisicion');


    Route::get(
        '/ts/liquidacion',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario,'/ts/liquidacion','Tesoreria/AsignacionLiquidacionRequerimiento');
        }
    )->name('ts.liquidacion');
    Route::post('asignados', [LiquidacionQuedanController::class, 'getRequerimientosAsiganos'])->name('asignados');
    Route::post('liquidar-quedan', [LiquidacionQuedanController::class, 'processLiquidacionQuedan'])->name('liquidar-quedan');
    Route::post('total-liquidacion-by-quedan', [LiquidacionQuedanController::class, 'totalLiquidacionesByQuedan'])->name('total-liquidacion-by-quedan');
    Route::post('delete-quedan-from-request', [LiquidacionQuedanController::class, 'deleteQuedanFromRequest'])->name('delete-quedan-from-request');

    Route::get(
        '/ts/reporte-quedan',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario,'/ts/reporte-quedan','Tesoreria/ReporteQuedan');
        }
    )->name('ts.reporteQuedan');
    Route::post('create-quedan-report', [ReporteTesoreriaController::class, 'createQuedanReport'])->name('reporteTesoreria.createQuedanReport');
    Route::get('get-selects-report', [ReporteTesoreriaController::class, 'getSelectsReport'])->name('reporteTesoreria.getSelectsReport');
    Route::get(
        '/ts/reporte-facturas',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario,'/ts/reporte-facturas','Tesoreria/ReporteFactura');
        }
    )->name('ts.reporteFactura');
    Route::get('get-selects-invoice-reporting', [ReporteTesoreriaController::class, 'getSelectsInvoiceReporting'])->name('reporteTesoreria.getSelectsInvoiceReporting');
    Route::post('create-invoice-report', [ReporteTesoreriaController::class, 'createInvoiceReport'])->name('reporteTesoreria.createInvoiceReport');
    Route::get(
        '/ts/reporte-retencion-isr',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario,'/ts/reporte-retencion-isr','Tesoreria/ReporteRetencionISR');
        }
    )->name('ts.reporteRetencionISR');
    Route::get('get-selects-withholding-tax-report', [ReporteTesoreriaController::class, 'getSelectsWithholdingTaxReport'])->name('reporteTesoreria.getSelectsWithholdingTaxReport');
    Route::post('create-income-tax-report', [ReporteTesoreriaController::class, 'createIncomeTaxReport'])->name('reporteTesoreria.createIncomeTaxReport');
    Route::get(
        '/ts/reporte-retencion-iva',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario,'/ts/reporte-retencion-iva','Tesoreria/ReporteRetencionIVA');
        }
    )->name('ts.reporteRetencionIVA');
    Route::post('create-withholding-iva-report', [ReporteTesoreriaController::class, 'createWithholdingIVAReport'])->name('reporteTesoreria.createWithholdingIVAReport');
    Route::get(
        '/ts/reporte-ingresos',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario,'/ts/reporte-ingresos','Tesoreria/ReporteIngresos');
        }
    )->name('ts.reporteIngresos');
    Route::get('get-selects-income-report', [ReporteTesoreriaController::class, 'getSelectsIncomeReport'])->name('reporteTesoreria.getSelectsIncomeReport');
    Route::post('create-income-report', [ReporteTesoreriaController::class, 'createIncomeReport'])->name('reporteTesoreria.createIncomeReport');
    Route::get(
        '/ts/ingresos-diarios',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario,'/ts/ingresos-diarios','Tesoreria/IngresosDiarios');
        }
    )->name('ts.ingresosDiarios');
    Route::post('get-daily-income-report', [ReporteTesoreriaController::class, 'getDailyIncomeReport'])->name('reporteTesoreria.getDailyIncomeReport');
});
