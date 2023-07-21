<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Administracion\UserController;
use App\Http\Controllers\Administracion\RolController;
use App\Http\Controllers\Administracion\PersonaController;

Route::group(['middleware' => ['access','auth']], function () {
    //Manage Usuarios
    Route::get(
        'adm/usuarios',
        function () {
            return Inertia::render('Administracion/Usuarios', [
                'menu' => session()->get('menu')
            ]);
        }
    )->name('adm.usuarios');
    //This route obtains users based on the parameters sent from the page.
    Route::post('users', [UserController::class, 'getUsers'])->name('user.getUsers');
    //Urls to create and update Users
    Route::post('change-state-user', [UserController::class, 'changeStateUser'])->name('user.changeStateUser');
    Route::post('change-password-user', [UserController::class, 'changePasswordUser'])->name('user.changePasswordUser');
    Route::post('systems', [UserController::class, 'getSystems'])->name('user.getSystems');
    Route::post('roles-per-system', [UserController::class, 'getRolesPerSystem'])->name('user.getRolesPerSystem');
    Route::post('save-rol', [UserController::class, 'saveRol'])->name('user.saveRol');
    Route::post('desactive-rol', [UserController::class, 'desactiveRol'])->name('user.desactiveRol');
    Route::post('update-rol', [UserController::class, 'updateRol'])->name('user.updateRol');
    Route::get('get-dui', [UserController::class, 'getDui'])->name('user.getDui');
    Route::post('save-user', [UserController::class, 'saveUser'])->name('user.saveUser');
    Route::get('get-selects-create-user', [UserController::class, 'getSelectsCreateUser'])->name('user.getSelectsCreateUser');

    //Manage Roles
    Route::get(
        'adm/roles',
        function () {
            return Inertia::render('Administracion/Roles', [
                'menu' => session()->get('menu')
            ]);
        }
    )->name('adm.roles');
    //This route obtains roles based on the parameters sent from the page.
    Route::get('roles', [RolController::class, 'getRoles'])->name('rol.getRoles');
    //Urls to create and update Roles
    Route::post('change-state-role-all', [RolController::class, 'changeStateRolAll'])->name('rol.changeStateRolAll');
    Route::post('menus-edit-rol', [RolController::class, 'getMenusEditRol'])->name('rol.getMenusEditRol');
    Route::post('children-menus', [RolController::class, 'getChildrenMenus'])->name('rol.getChildrenMenus');
    Route::post('save-menu', [RolController::class, 'saveMenu'])->name('rol.saveMenu');
    Route::post('desactive-menu', [RolController::class, 'desactiveMenu'])->name('rol.desactiveMenu');
    Route::post('update-permits', [RolController::class, 'updatePermits'])->name('rol.updatePermits');
    Route::get('systems-all', [RolController::class, 'getSystemsAll'])->name('rol.getSystemsAll');
    Route::post('parents-menu-all', [RolController::class, 'getParentsMenuAll'])->name('rol.getParetntsMenuAll');
    Route::post('create-rol', [RolController::class, 'createRol'])->name('rol.createRol');

    //Manage Personas
    Route::get(
        'adm/personas',
        function () {
            return Inertia::render('Administracion/Personas', [
                'menu' => session()->get('menu')
            ]);
        }
    )->name('adm.personas');
    //Manage Personas
    Route::get('personas', [PersonaController::class, 'getPersona'])->name('get.personas');
    Route::get('/list-option-select', [PersonaController::class, 'getInformationToSelect'])->name('list-option-select');
    Route::get('get-persona', [PersonaController::class, 'getInformationPersona'])->name('get.persona');
    Route::post('post-persona', [PersonaController::class, 'AgregarPersona'])->name('post-persona');
    Route::post('update-persona', [PersonaController::class, 'EditarPersona'])->name('update-persona');
    Route::post('update-state-person', [PersonaController::class, 'changeStatePerson'])->name('update-estate-person');
    Route::get('list-departament/{id_country}', [PersonaController::class, 'getDepartamentByCountry'])->name('list-departament');
    Route::get('list-municipio/{id_departament}', [PersonaController::class, 'getMunicipioByDepartament'])->name('list-municipio');
    Route::get('list-departament-by-municipio/{id_municpio}', [PersonaController::class, 'getDepartamentByMunicipio'])->name('list-departament-by-municipio');
});
