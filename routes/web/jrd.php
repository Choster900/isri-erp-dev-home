<?php

use App\Http\Controllers\Juridico\FiniquitoController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::group(['middleware' => ['auth', 'access']], function () {
    Route::get(
        '/jrd/finiquitos',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/jrd/finiquitos', 'Juridico/Finiquitos');
        }
    )->name('jrd.finiquitos');
    Route::post('finiquitos', [FiniquitoController::class, 'getFiniquitos'])->name('finiquito.getFiniquitos');
    Route::get('get-info-modal-finiquitos/{id}', [FiniquitoController::class, 'getInfoModalFiniquitos'])->name('finiquito.getInfoModalFiniquitos');
    Route::post('search-person-jrd', [FiniquitoController::class, 'searchPersonJrd'])->name('finiquito.searchPersonJrd');
});
