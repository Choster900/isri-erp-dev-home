<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menu';
    protected $primaryKey = 'id_menu';
    public $timestamps = false;

    protected $fillable = [
        'id_sistema',
        'id_menu_padre',
        'nombre_menu',
        'url_menu',
        'estado_menu',
        'nombre_ruta',
        'page',
    ];

    public function sistema()
    {
        return $this->belongsTo('App\Models\Sistema','id_sistema','id_sistema');
    }

    public function roles()
    {
        return $this->belongsToMany('App\Models\Rol', 'acceso_menu', 'id_menu', 'id_rol')
            ->using('App\Models\AccesoMenu')
            ->withPivot([
                'id_acceso_menu',
                'estado_acceso_menu',
                'insertar_acceso_menu',
                'actualizar_acceso_menu',
                'eliminar_acceso_menu',
                'ejecutar_acceso_menu',
            ]);
    }

    public function childrenMenus()
    {
        return $this->hasMany('App\Models\Menu', 'id_menu_padre', 'id_menu')->with('roles');
    }

    public function allChildrenMenus()
    {
        return $this->childrenMenus()->with('allChildrenMenus');
    }

    public function parentMenu()
    {
        return $this->hasOne('App\Models\Menu', 'id_menu', 'id_menu_padre');
    }

}
