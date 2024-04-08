<?php

use App\Http\Controllers\Almacen\AjusteEntradaController;
use App\Http\Controllers\Almacen\DonacionController;
use App\Http\Controllers\Almacen\RecepcionController;
use App\Http\Controllers\Almacen\RequerimientoAlmacenController;
use App\Models\DetalleRequerimiento;
use App\Models\ExistenciaAlmacen;
use App\Models\PlazaAsignada;
use App\Models\Requerimiento;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::group(['middleware' => ['auth', 'access']], function () {
    //Normal receptions
    Route::get(
        '/alm/recepciones',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/alm/recepciones', 'Almacen/Recepciones');
        }
    )->name('alm.recepciones');
    Route::post('recepciones', [RecepcionController::class, 'getRecepciones'])->name('recepcion.getRecepciones');
    Route::post('get-info-modal-recep', [RecepcionController::class, 'getInfoModalRecep'])->name('recepcion.getInfoModalRecep');
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
            return Requerimiento::latest("fecha_reg_requerimiento")->where('id_estado_req','!=', 4)->first();
        }
    )->name('donacion.getObjectForRequerimientoAlmacen');
    Route::post('insert-requerimiento-almacen', [RequerimientoAlmacenController::class, 'addRequerimiento'])->name('donacion.insertRequerimientoAlmacen');
    Route::post('update-requerimiento-almacen', [RequerimientoAlmacenController::class, 'updateRequerimientoAlmacen'])->name('donacion.updateRequerimientoAlmacen');
    Route::post('get-product-searched-almacen', [RequerimientoAlmacenController::class, 'getProductByNameOrCode'])->name('donacion.productSearchedAlmacen');
    Route::post(
        'get-centro-by-user',
        function (Request $request) {
            $plazasAsignadas = PlazaAsignada::where("id_empleado", $request->user()->id_persona)->with("centro_atencion")->get();
            // Crear una colección para almacenar los centros de atención únicos
            $centrosUnicos = collect();
            // Iterar sobre las plazas asignadas y agregar los centros de atención únicos a la colección
            foreach ($plazasAsignadas as $plazaAsignada) {
                $centroAtencion = $plazaAsignada->centro_atencion;
                $centrosUnicos->push($centroAtencion);
            }
            // Filtrar la colección para obtener solo centros de atención únicos
            $centrosUnicos = $centrosUnicos->unique('id_centro_atencion');
            return $centrosUnicos;
        }
    )->name('bieneservicios.get-centro-by-user');
    Route::post(
        'get-product-by-proy-financiado',
        function (Request $request) {
            return ExistenciaAlmacen::with(['detalle_existencia_almacen', 'productos'])
                ->whereHas('detalle_existencia_almacen', function ($query) use ($request) {
                    $query->where('id_centro_atencion', $request->idCentroAtencion);
                    $query->where('id_lt', $request->idLt);
                })
                ->where('id_proy_financiado', $request->idProyFinanciado)
                ->get();
        }
    )->name('bieneservicios.get-product-by-proy-financiad');
    Route::post('update-state-requerimiento', [RequerimientoAlmacenController::class, 'updateStateRequerimiento'])->name('bieneservicios.get-product-by-proy-financiad');

    //Financial report
    Route::get(
        '/alm/reporte-financiero',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/alm/reporte-financiero', 'Almacen/ReporteFinanciero');
        }
    )->name('alm.reporteFinanciero');

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
});
