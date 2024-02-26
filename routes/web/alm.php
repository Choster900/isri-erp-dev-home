<?php

use App\Http\Controllers\Almacen\RecepcionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::group(['middleware' => ['auth', 'access']], function () {
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

});