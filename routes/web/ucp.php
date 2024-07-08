<?php

use App\Http\Controllers\UCP\BienesServiciosController;
use App\Http\Controllers\UCP\MarcaUcpController;
use App\Http\Controllers\UCP\ProductoController;
use App\Models\CentroAtencion;
use App\Models\DetDocumentoAdquisicion;
use App\Models\LineaTrabajo;
use App\Models\Producto;
use App\Models\UnidadMedida;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Luecano\NumeroALetras\NumeroALetras;

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
        '/ucp/orden-compra',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/ucp/orden-compra', 'UCP/OrdenCompra');
        }
    )->name('ucp.orden-compra');
    Route::get(
        '/ucp/contrato',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/ucp/contrato', 'UCP/Contratos');
        }
    )->name('ucp.contrato');
    Route::post('producto-adquisiciono', [BienesServiciosController::class, 'getProductoAdquisicionByDocumentoAdquisicion'])->name('bieneservicios.productioAdquisicion');
    Route::post('get-all-linea-trabajo', [BienesServiciosController::class, 'getAllLineaTrabajo'])->name('bieneservicios.getAllLineaTrabajo');
    Route::post('get-array-objects-for-multiselect', [BienesServiciosController::class, 'getArrayObjectoForMultiSelect'])->name('bieneservicios.getAllLineaTrabajo');
    Route::post('get-product-by-proceso', function (Request $request) {
        $product = Producto::with(["unidad_medida"])
            ->where('id_proceso_compra', $request->procesoId)
            ->get();
        // Formatear resultados para respuesta JSON
        $formattedResults = $product->map(function ($item) {
            return [
                'value'           => $item->id_producto,
                'label'           => $item->codigo_producto . ' - ' . $item->nombre_completo_producto . ' - ' . $item->unidad_medida->nombre_unidad_medida,
                'allDataProducto' => $item,
            ];
        });
        return response()->json($formattedResults);
    })->name('bieneservicios.getProductByCodigoProducto');
    Route::post('save-prod-adquicicion', [BienesServiciosController::class, 'saveProductoAdquisicion'])->name('bieneservicios.saveProdAdquisicion');
    Route::post('update-prod-adquicicion', [BienesServiciosController::class, 'updateProductoAdquisicion'])->name('bieneservicios.updateProdAdquisicion');
    Route::post('change-state-detalle-doc-adquisicion', [BienesServiciosController::class, 'changeStateProductoAdquisicion'])->name('bieneservicios.changeStateProductoAdquisicion');
    Route::post('export-reporte-bienes-servicios-to-excel', [BienesServiciosController::class, 'exportDocumentToExcel'])->name('bieneservicios.changeStateProductoAdquisicion');
    Route::post(
        'convert-numbers-to-string',
        function (Request $request) {
            $numbersToLetters = new NumeroALetras();
            $numero = $request->number;
            $amountLetter = $numbersToLetters->toInvoice($numero, 2, 'DÓLARES');
            return $amountLetter;
        }
    )->name('bieneservicios.getNumbersToLetters');

    Route::get(
        '/ucp/documento-adquisicion',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/ucp/documento-adquisicion', 'Tesoreria/DocAdquisicion');
        }
    )->name('ucp.documento-adquisicion-ucp');

    Route::get(
        '/ucp/marcas',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/ucp/marcas', 'UCP/MarcasUcp');
        }
    )->name('ucp.marcas');
    Route::post('marcas-ucp', [MarcaUcpController::class, 'getMarcasUcp'])->name('marcas.getMarcasUcp');
});
