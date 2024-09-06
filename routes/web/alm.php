<?php

use App\Http\Controllers\Almacen\AjusteEntradaController;
use App\Http\Controllers\Almacen\AjusteSalidaController;
use App\Http\Controllers\Almacen\DonacionController;
use App\Http\Controllers\Almacen\ProductoAlmacenController;
use App\Http\Controllers\Almacen\RecepcionController;
use App\Http\Controllers\Almacen\ReporteAlmacenController;
use App\Http\Controllers\Almacen\RequerimientoAlmacenController;
use App\Http\Controllers\Almacen\SubAlmacenController;
use App\Http\Controllers\Almacen\TransferenciaController;
use App\Http\Controllers\UCP\ProcesoCompraController;
use App\Models\ProyectoFinanciado;
use App\Models\Requerimiento;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::group(['middleware' => ['auth', 'access']], function () {

    Route::get(
        '/alm/sub-almacen',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/alm/sub-almacen', 'Almacen/SubAlmacen');
        }
    )->name('alm.sub-almacen');
    Route::post('sub-almacen', [SubAlmacenController::class, 'getSubAlmacenes'])->name('mantenimiento.subAlmacen');
    Route::post('find-employee-sub-almacen', [SubAlmacenController::class, 'findEmployeeByName'])->name('mantenimiento.findEmployeeByName');
    Route::post('save-sub-almacen', [SubAlmacenController::class, 'saveSubAlmacen'])->name('mantenimiento.saveSubAlmacen');
    Route::post('update-sub-almacen', [SubAlmacenController::class, 'updateSubAlmacen'])->name('mantenimiento.saveSubAlmacen');
    Route::post('delete-sub-almacen', [SubAlmacenController::class, 'deleteSubAlmacen'])->name('mantenimiento.saveSubAlmacen');


    //Normal receptions
    Route::get(
        '/alm/recepciones',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/alm/recepciones', 'Almacen/Recepciones');
        }
    )->name('alm.recepciones');
    Route::post('recepciones', [RecepcionController::class, 'getRecepciones'])->name('recepcion.getRecepciones');
    Route::post('get-info-modal-recep', [RecepcionController::class, 'getInfoModalRecep'])->name('recepcion.getInfoModalRecep');
    Route::post('check-available-months', [RecepcionController::class, 'checkAvailableMonths'])->name('recepcion.checkAvailableMonths');
    Route::get('get-initial-doc-info', [RecepcionController::class, 'getInitialInfoDoc'])->name('recepcion.getInitialInfoDoc');
    Route::post('save-goods-reception', [RecepcionController::class, 'storeReception'])->name('recepcion.storeReception');
    Route::post('update-goods-reception', [RecepcionController::class, 'updateReception'])->name('recepcion.updateReception');
    Route::post('change-status-reception', [RecepcionController::class, 'changeStatusReception'])->name('recepcion.changeStatusReception');
    //Functionality to send information to Kardex
    Route::get('get-info-modal-send-kardex/{id}', [RecepcionController::class, 'getInfoModalSendKardex'])->name('recepcion.getInfoModalSendKardex');
    Route::post('send-goods-reception', [RecepcionController::class, 'sendGoodsReception'])->name('recepcion.sendGoodsReception');
    //Print reception
    Route::get('print-reception/{id}', [RecepcionController::class, 'printReception'])->name('recepcion.printReception');
    //Donations
    Route::get(
        '/alm/donaciones',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/alm/donaciones', 'Almacen/Donaciones');
        }
    )->name('alm.donaciones');
    Route::post('donaciones', [DonacionController::class, 'getDonaciones'])->name('donacion.getDonaciones');
    Route::post('get-info-modal-donation', [DonacionController::class, 'getInfoModalDonation'])->name('donacion.getInfoModalDonation');
    Route::post('search-donation-product', [DonacionController::class, 'searchProduct'])->name('donacion.searchProduct');
    Route::post('save-donation-info', [DonacionController::class, 'storeDonationInfo'])->name('donacion.storeDonationInfo');
    Route::post('update-donation-info', [DonacionController::class, 'updateDonationInfo'])->name('donacion.updateDonationInfo');
    Route::post('change-status-donation', [DonacionController::class, 'changeStatusDonation'])->name('donacion.changeStatusDonation');
    //Functionality to send information to Kardex
    Route::get('get-info-modal-send-donation/{id}', [DonacionController::class, 'getInfoModalSendDonation'])->name('donacion.getInfoModalSendDonation');
    Route::post('send-goods-donation', [DonacionController::class, 'sendGoodsDonation'])->name('donacion.sendGoodsDonation');
    //Print donation document
    Route::get('print-donation/{id}', [DonacionController::class, 'printDonation'])->name('donacion.printDonation');
    //Requerimientos
    Route::get(
        '/alm/requerimientos',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/alm/requerimientos', 'Almacen/RequerimientoAlmacen');
        }
    )->name('alm.requerimientos');
    Route::post('get-requerimiento-almacen', [RequerimientoAlmacenController::class, 'getRequerimientoAlmacen'])->name('donacion.getObjectForRequerimientoAlmacen');
    Route::post('get-object-for-requerimiento-almacen', [RequerimientoAlmacenController::class, 'getObject'])->name('donacion.getObjectForRequerimientoAlmacen');
    Route::post(
        'get-number-requerimiento',
        function (Request $request) {
            return Requerimiento::latest("fecha_reg_requerimiento")->where('id_tipo_req', 1)->first();
        }
    )->name('donacion.getObjectForRequerimientoAlmacen');
    Route::post('get-centro-produccion-by-users-centro', [RequerimientoAlmacenController::class, 'getCentroProduccionByUsersCentro'])->name('donacion.get-centro-produccion-by-centro');
    Route::post('get-centro-produccion-by-centro', [RequerimientoAlmacenController::class, 'getProductionCenterByCenter'])->name('donacion.get-centro-produccion-by-centro');
    Route::post('insert-requerimiento-almacen', [RequerimientoAlmacenController::class, 'addRequerimiento'])->name('donacion.insertRequerimientoAlmacen');
    Route::post('update-requerimiento-almacen', [RequerimientoAlmacenController::class, 'updateRequerimientoAlmacen'])->name('donacion.updateRequerimientoAlmacen');
    Route::post('get-product-searched-almacen', [RequerimientoAlmacenController::class, 'getProductByNameOrCode'])->name('donacion.productSearchedAlmacen');
    Route::post('get-centro-by-user', [RequerimientoAlmacenController::class, 'getAttentionCentersByUser'])->name('bieneservicios.get-centro-by-user');
    Route::post('get-product-by-proy-financiado', [RequerimientoAlmacenController::class, 'getProductByFundedProjectCenterAndWorkLine'])->name('bieneservicios.get-product-by-proy-financiad');
    Route::post('update-state-requerimiento', [RequerimientoAlmacenController::class, 'updateStateRequerimiento'])->name('bieneservicios.get-product-by-proy-financiad');
    //Financial report
    Route::get(
        '/alm/reporte-financiero',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/alm/reporte-financiero', 'Almacen/ReporteFinanciero');
        }
    )->name('alm.reporteFinanciero');
    Route::post('get-proyecto-financiado', function (Request $request) {
        return ProyectoFinanciado::all();
    })->name('reporte.get-proyectos');
    Route::post('get-reporte-financiero-almacen-bienes-existencia', [ReporteAlmacenController::class, 'getReporteFinanciero'])->name('bieneservicios.get-reporte-financiero-almacen');
    Route::post('get-excel-document-reporte-financiero', [ReporteAlmacenController::class, 'createExcelReport'])->name('bieneservicios.get-proyectos');
    Route::post('get-reporte-consumo', [ReporteAlmacenController::class, 'getReporteConsumo'])->name('bieneservicios.get-reporte-consumo');
    Route::post('get-excel-document-reporte-consumo', [ReporteAlmacenController::class, 'getExcelDocumentConsumo'])->name('bieneservicios.get-excel-document-reporte-consumo');
    /*  Route::post('get-cuenta-by-number', function (Request $request) {
        $cuentas = CatalogoCtaPresupuestal::where("id_ccta_presupuestal", "like", '%' . $request->numeroCuenta . '%')->get();
        // Formatear resultados para respuesta JSON
        return $cuentas->map(function ($item) {
            return [
                'value'           => $item->id_ccta_presupuestal,
                'label'           => $item->id_ccta_presupuestal . '-' . $item->nombre_ccta_presupuestal,
                'allDataPersonas' => $item,
            ];
        });
    })->name('reporte.get-cuenta'); */

    //Surplus adjustment
    Route::get(
        '/alm/ajuste-entrada',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/alm/ajuste-entrada', 'Almacen/AjusteEntrada');
        }
    )->name('alm.ajusteEntrada');
    Route::post('ajuste-entrada', [AjusteEntradaController::class, 'getAjusteEntrada'])->name('ajusteEntrada.getAjusteEntrada');
    Route::post('get-info-modal-shortage-adjustment', [AjusteEntradaController::class, 'getInfoShortageAdjustment'])->name('ajusteEntrada.getInfoShortageAdjustment');
    Route::post('save-shortage-adjustment-info', [AjusteEntradaController::class, 'storeShortageAdjustment'])->name('ajusteEntrada.storeShortageAdjustment');
    Route::post('update-shortage-adjustment-info', [AjusteEntradaController::class, 'updateShortageAdjustment'])->name('ajusteEntrada.updateShortageAdjustment');
    Route::post('change-status-shortage-adjustment', [AjusteEntradaController::class, 'changeStatusShortageAdjustment'])->name('ajusteEntrada.changeStatusShortageAdjustment');
    Route::post('send-shortage-adjustment', [AjusteEntradaController::class, 'sendShortageAdjustment'])->name('ajusteEntrada.sendShortageAdjustment');
    Route::get(
        '/alm/reporte-consumo',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/alm/reporte-consumo', 'Almacen/ReporteConsumo');
        }
    )->name('alm.reporteConsumo');
    Route::post('get-cuenta-by-number', [ReporteAlmacenController::class, 'getBudgetaryAccountsByAccountNumber'])->name('reporte.get-cuenta');
    //Outgoing adjustment
    Route::get(
        '/alm/ajuste-salida',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/alm/ajuste-salida', 'Almacen/AjusteSalida');
        }
    )->name('alm.ajusteSalida');
    Route::post('ajuste-salida', [AjusteSalidaController::class, 'getAjusteSalida'])->name('ajusteSalida.getAjusteSalida');
    Route::post('get-info-modal-outgoing-adjustment', [AjusteSalidaController::class, 'getInfoOutgoingAdjustment'])->name('ajusteSalida.getInfoOutgoingAdjustment');
    Route::post('search-products-outgoing-adjustment', [AjusteSalidaController::class, 'searchProductsOutgoingAdjustment'])->name('ajusteSalida.searchProductsOutgoingAdjustment');
    Route::post('save-outgoing-adjustment-info', [AjusteSalidaController::class, 'storeOutgoingAdjustment'])->name('ajusteSalida.storeOutgoingAdjustment');
    Route::post('update-outgoing-adjustment-info', [AjusteSalidaController::class, 'updateOutgoingAdjustment'])->name('ajusteSalida.updateOutgoingAdjustment');
    Route::post('change-status-outgoing-adjustment', [AjusteSalidaController::class, 'changeStatusOutgoingAdjustment'])->name('ajusteSalida.changeStatusOutgoingAdjustment');
    Route::post('send-outgoing-adjustment', [AjusteSalidaController::class, 'sendOutgoingAdjustment'])->name('ajusteSalida.sendOutgoingAdjustment');
    Route::get(
        '/alm/reporte-rotacion',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/alm/reporte-rotacion', 'Almacen/ReporteRotacion');
        }
    )->name('alm.reporteRotacion');
    Route::post('get-reporte-rotacion', [ReporteAlmacenController::class, 'getReporteDonacion'])->name('ajusteSalida.getAjusteSalida');
    Route::post('get-excel-reporte-rotacion', [ReporteAlmacenController::class, 'getExcelReporteRotacion'])->name('ajusteSalida.getAjusteSalida');
    //Transfers
    Route::get(
        '/alm/transferencias',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/alm/transferencias', 'Almacen/Transferencias');
        }
    )->name('alm.transferencias');
    Route::post('transferencias-almacen', [TransferenciaController::class, 'getTransferencias'])->name('transferencia.getTransferencias');
    Route::post('get-info-modal-warehouse-transfer', [TransferenciaController::class, 'getInfoWarehouseTransfer'])->name('transferencia.getInfoWarehouseTransfer');
    Route::post('save-warehouse-transfer-info', [TransferenciaController::class, 'storeWarehouseTransfer'])->name('transferencia.storeWarehouseTransfer');
    Route::post('update-warehouse-transfer-info', [TransferenciaController::class, 'updateWarehouseTransfer'])->name('transferencia.updateWarehouseTransfer');
    Route::post('change-status-warehouse-transfer', [TransferenciaController::class, 'changeStatusWarehouseTransfer'])->name('transferencia.changeStatusWarehouseTransfer');
    Route::post('send-warehouse-transfer', [TransferenciaController::class, 'sendWarehouseTransfer'])->name('transferencia.sendWarehouseTransfer');
    Route::get(
        '/alm/reporte-existencia',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/alm/reporte-rotacion', 'Almacen/ReporteExistencia');
        }
    )->name('alm.reporteExistencia');
    Route::post('get-reporte-existencia-almacen', [ReporteAlmacenController::class, 'getReporteExistencia'])->name('reporte.AlmacenExistencia');
    Route::post('get-reporte-existencia-almacen-excel', [ReporteAlmacenController::class, 'getExcelReporteExistencia'])->name('reporte.getReporteAlmacenExistenciaExcel');
    Route::get(
        '/alm/reporte-requisicion',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/alm/reporte-requisicion', 'Almacen/ReporteRequisicion');
        }
    )->name('alm.reporteRequisicion');
    Route::post('get-reporte-requisicion-almacen', [ReporteAlmacenController::class, 'getReporteRequisicionAlmacen'])->name('reporte.AlmacenExistencia');
    Route::post('get-reporte-excel-requisicion', [ReporteAlmacenController::class, 'getExcelRequisicion'])->name('reporte.AlmacenExistencia');
    Route::get(
        '/alm/reporte-recepcion',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/alm/reporte-recepcion', 'Almacen/ReporteRecepcion');
        }
    )->name('alm.reporteRecepcion');
    Route::post('get-reporte-recepcion-almacen', [ReporteAlmacenController::class, 'getReporteRecepcion'])->name('reporte.AlmacenRecepcion');
    Route::post('get-reporte-recepcion-excel', [ReporteAlmacenController::class, 'getExcelRecepcion'])->name('reporte.AlmacenRecepcion');

    //Contract tracking report
    Route::get(
        '/alm/reporte-seguimiento',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/alm/reporte-seguimiento', 'Almacen/ReporteSeguimientoContrato');
        }
    )->name('alm.reporteSeguimiento');
    Route::get('get-contracts-info', [ReporteAlmacenController::class, 'getContractsInfo'])->name('reporteAlm.getContractsInfo');
    Route::post('get-contract-tracking-report', [ReporteAlmacenController::class, 'getContractTrackingReport'])->name('reporteAlm.getContractTrackingReport');
    Route::post('create-excel-tracking-contract', [ReporteAlmacenController::class, 'createExcelTrackingContract'])->name('reporteAlm.createExcelTrackingContract');

    Route::get(
        '/alm/reporte-kardex',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/alm/reporte-kardex', 'Almacen/ReporteKardex');
        }
    )->name('alm.reporteKardex');
    Route::post('get-producto-for-reporte', [ReporteAlmacenController::class, 'getProductos'])->name('reporte.get-producto-for-reporte');
    Route::post('get-reporte-kardex', [ReporteAlmacenController::class, 'getReporteKardex'])->name('reporte.get-reporte-kardex');
    Route::post('get-reporte-excel-kardex', [ReporteAlmacenController::class, 'getKardexExcelReport'])->name('reporte.get-reporte-kardex');

    Route::get(
        '/alm/reporte-perc',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/alm/reporte-perc', 'Almacen/ReportePERC');
        }
    )->name('alm.reportePerc');
    Route::post('get-reporte-perc-report', [ReporteAlmacenController::class, 'getReportePerc'])->name('reporte.get-perc-report');
    Route::post('get-report-excel-perc', [ReporteAlmacenController::class, 'getPercExcelReport'])->name('reporte.get-perc-report');

    //Products catalog for almacen
    Route::get(
        '/alm/productos',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/alm/productos', 'Almacen/ProductosAlmacen');
        }
    )->name('alm.productos');
    Route::post('productos-almacen', [ProductoAlmacenController::class, 'getProductosAlmacen'])->name('productoAlmacen.getProductosAlmacen');
    Route::get('get-info-modal-prod-almacen/{id}', [ProductoAlmacenController::class, 'getInfoModalProdAlmacen'])->name('productoAlmacen.getInfoModalProdAlmacen');
    Route::post('update-product-almacen', [ProductoAlmacenController::class, 'updateProductAlmacen'])->name('productoAlmacen.updateProductAlmacen');


    Route::get(
        '/alm/proceso-compra',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/alm/proceso-compra', 'Almacen/ProcesoCompra');
        }
    )->name('alm.proceso-compras');
    Route::post('get-proceso-compra-for-almacen', [ProcesoCompraController::class, 'getProcesosComprasForAlmacen'])->name('procesoCompra.getProcesosComprasForAlmacen');
    Route::post('find-employee-by-name-for-warehouse', [ProcesoCompraController::class, 'findEmployeeByName'])->name('procesoCompra.findEmployeeByName');
    Route::post('update-employee-in-proceso-compra', [ProcesoCompraController::class, 'updateProcesoCompraEmployee'])->name('procesoCompra.updateProcesoCompraEmployee');
    Route::get('getEmployeeByDependencia', [ProcesoCompraController::class, 'getEmployeeByDependencia'])->name('procesoCompra.getEmployeeByDependencia');
});//COMENTARIO
