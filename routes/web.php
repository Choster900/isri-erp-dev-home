<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\File;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get(
        'dashboard',
        function () {
            return Inertia::render('Dashboard');
        }
    )->name('dashboard');

    //Manage Login Information
    Route::get('dashboard/{id}', [IndexController::class, 'getMenus'])->name('index.getMenus');
    Route::get('password/create', [IndexController::class, 'createCambiarContraseña'])->name('index.createCambiarContraseña');
    Route::put('password/reset', [IndexController::class, 'cambiarContraseña'])->name('index.cambiarContraseña');

    Route::get('/resources/{filename}', function ($filename) {
        $path = resource_path('img/' . $filename);
    
        if (File::exists($path)) {
            return response()->file($path);
        }
    
        return response()->json(['message' => 'Imagen no encontrada'], 404);
    })->where('filename', '.*');

});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/web/auth.php';
require __DIR__ . '/web/public.php';
require __DIR__ . '/web/admin.php';
require __DIR__ . '/web/rrhh.php';
require __DIR__ . '/web/af.php';
require __DIR__ . '/web/pr.php';
require __DIR__ . '/web/ts.php';
require __DIR__ . '/web/jrd.php';
require __DIR__ . '/web/ucp.php';