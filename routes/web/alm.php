<?php

use App\Http\Controllers\Almacen\DonacionController;
use App\Http\Controllers\Almacen\RecepcionController;
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
});
