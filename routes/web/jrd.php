<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::group(['middleware' => ['auth', 'access']], function () {
    Route::get(
        '/jrd/finiquitos',
        function (Request $request) {
            return checkModuleAccessAndRedirect($request->user()->id_usuario, '/jrd/finiquitos', 'Juridico/Finiquitos');
        }
    )->name('jrd.finiquitos');
});