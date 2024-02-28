<?php

use App\Http\Controllers\UCP\BienesServiciosController;
use App\Http\Controllers\UCP\ProductoController;
use App\Models\CentroAtencion;
use App\Models\DetDocumentoAdquisicion;
use App\Models\LineaTrabajo;
use App\Models\Producto;
use App\Models\UnidadMedida;
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
    Route::post('save-product', [ProductoController::class, 'saveProduct'])->name('producto.saveProduct');
    Route::post('update-product', [ProductoController::class, 'updateProduct'])->name('producto.updateProduct');
    Route::post('change-status-product', [ProductoController::class, 'changeStatusProduct'])->name('producto.changeStatusProduct');

    Route::get(
        '/ucp/bienes-servicvios',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/ucp/bienes-servicvios', 'UCP/BienesServicios');
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/ucp/bienes-servicvios', 'UCP/BienesServicios');
        }
    )->name('ucp.bienes-servicios');
    Route::post('producto-adquisiciono', [BienesServiciosController::class, 'getProductoAdquisicionByDocumentoAdquisicion'])->name('bieneservicios.productioAdquisicion');
    Route::post('get-all-linea-trabajo', [BienesServiciosController::class, 'getAllLineaTrabajo'])->name('bieneservicios.getAllLineaTrabajo');
    Route::post('get-array-objects-for-multiselect', [BienesServiciosController::class, 'getArrayObjectoForMultiSelect'])->name('bieneservicios.getAllLineaTrabajo');
    Route::post('get-product-by-codigo-producto', function (Request $request) {
        $product = Producto::with(["unidad_medida"])->where('codigo_producto', 'like', '%' . $request->codigoProducto . '%')->get();
        // Formatear resultados para respuesta JSON
        $formattedResults = $product->map(function ($item) {
            return [
                'value'           => $item->id_producto,
                'label'           => $item->codigo_producto,
                'allDataProducto' => $item,
            ];
        });
        return response()->json($formattedResults);
    })->name('bieneservicios.getProductByCodigoProducto');
    Route::post('save-prod-adquicicion', [BienesServiciosController::class, 'saveProductoAdquisicion'])->name('bieneservicios.saveProdAdquisicion');
    Route::post('update-prod-adquicicion', [BienesServiciosController::class, 'updateProductoAdquisicion'])->name('bieneservicios.updateProdAdquisicion');

});
