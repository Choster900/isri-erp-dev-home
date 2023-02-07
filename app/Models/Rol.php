<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;

    protected $table = 'rol';
    protected $primaryKey = 'id_rol';
    public $timestamps = false;

    protected $fillable = [
        'id_sistema',
        'nombre_rol',
        'estado_rol',
        'fecha_reg_rol',
    ];

    public function sistema()
    {
        return $this->belongsTo('App\Models\Sistema','id_sistema','id_sistema');
    }

    public function usuarios()
    {
        return $this->belongsToMany('App\Models\User', 'permiso_usuario', 'id_rol', 'id_usuario')
            ->using('App\Models\PermisoUsuario')
            ->withPivot([
                'id_permiso_usuario',
                'estado_permiso_usuario'
            ]);
    }

    public function menus()
    {
        return $this->belongsToMany('App\Models\Menu', 'acceso_menu', 'id_rol', 'id_menu')
            ->using('App\Models\AccesoMenu')
            ->withPivot([
                'id_acceso_menu',
                'estado_acceso_menu'
            ]);
    }

    //Check if the menu is active and if the rol has this menu assigned.
    public function hasMenu($id_rol,$id_menu)
    {
        $menu = Menu::find($id_menu);
        $contador = 0;
        if ($menu) {
            foreach ($menu->roles as $rol) {
                if ($rol->pivot->id_rol==$id_rol && $rol->pivot->estado_acceso_menu==1) {
                    $contador++;
                }
            }
        }
        if ($contador == 0) {
            return false;
        } else {
            return true;
        }
        
    }

}
