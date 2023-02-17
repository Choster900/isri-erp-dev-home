<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\Administracion\UserController;
use App\Http\Controllers\Administracion\RolController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    //This route obtains users based on the parameters sent from the page.
    Route::get('users', [UserController::class, 'getUsers'])->name('getusers');

    Route::get('roles', [RolController::class, 'getRoles'])->name('getroles');

    Route::get('sistemas', [UserController::class, 'getSistemas'])->name('getsistemas');
    Route::get('rolesxsistemas', [UserController::class, 'getRoles'])->name('getroles');
    Route::post('save/rol', [UserController::class, 'saveRol'])->name('saverol');
    Route::post('desactive/rol', [UserController::class, 'desactiveRol'])->name('desactiverol');
    Route::post('edit/rol', [UserController::class, 'editRol'])->name('editrol');
    Route::get('rolesxsistema', [UserController::class, 'getRolesxSistema'])->name('getrolesxsistema');

    Route::get('dashboard/{id}', [IndexController::class, 'getMenus'])->name('mainpage');
    Route::get('password/create', [IndexController::class, 'createCambiarContraseña'])->name('crear.contraseña');
    Route::put('password/reset', [IndexController::class, 'cambiarContraseña'])->name('cambiar.contraseña');
});


Route::middleware('auth')->group(function () {  
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/web/auth.php';
require __DIR__.'/web/public.php';
require __DIR__.'/web/admin.php';
require __DIR__.'/web/rrhh.php';
require __DIR__.'/web/af.php';
require __DIR__.'/web/pr.php';