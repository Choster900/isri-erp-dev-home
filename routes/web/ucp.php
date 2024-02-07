<?php

use App\Http\Controllers\UCP\ProductoController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::group(['middleware' => ['auth', 'access']], function () {
    Route::get(
        '/ucp/productos',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/ucp/productos', 'UCP/Productos');
        }
    )->name('ucp.productos');
    Route::post('productos', [ProductoController::class, 'getProductos'])->name('producto.getProductos');
    Route::get('get-info-modal-prod/{id}', [ProductoController::class, 'getInfoModalProd'])->name('producto.getInfoModalProd');
    Route::post('search-unspsc', [ProductoController::class, 'searchUnspsc'])->name('producto.searchUnspsc');
});
