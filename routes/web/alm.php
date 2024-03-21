<?php

use App\Http\Controllers\Almacen\DonacionController;
use App\Http\Controllers\Almacen\RecepcionController;
use App\Http\Controllers\Almacen\RequerimientoAlmacenController;
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


    //Requerimientos
    Route::get(
        '/alm/requerimientos',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/alm/requerimientos', 'Almacen/RequerimientoAlmacen');
        }
    )->name('alm.requerimientos');
    Route::post('get-requerimiento-almacen', [RequerimientoAlmacenController::class, 'getRequerimientoAlmacen'])->name('donacion.get-object-for-requerimiento-almacen');
    Route::post('get-object-for-requerimiento-almacen', [RequerimientoAlmacenController::class, 'getObject'])->name('donacion.get-object-for-requerimiento-almacen');
    Route::post('insert-requerimiento-almacen', [RequerimientoAlmacenController::class, 'addRequerimiento'])->name('donacion.insert-requerimiento-almacen');


});
