<?php
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Tesoreria\ProveedorController;
use App\Http\Controllers\Tesoreria\QuedanController;

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
    Route::post('add-detalle-quedan', [QuedanController::class, 'addDetalleQuedan'])->name('add-detalle-quedan');
    Route::get('get-quedan', [QuedanController::class, 'getQuedan'])->name('get-quedan');
    Route::post('update-detalle-quedan', [QuedanController::class, 'updateDetalleQuedan'])->name('update-detalle-quedan');

});