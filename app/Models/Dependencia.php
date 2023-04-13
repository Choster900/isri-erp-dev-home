<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dependencia extends Model
{
    use HasFactory;

    protected $table = 'dependencia';
    protected $primaryKey = 'id_dependencia';
    public $timestamps = false;

    protected $fillable = [
        'dep_id_dependencia',
        'id_tipo_dependencia',
        'nombre_dependencia',
        'codigo_dependencia',
        'telefono_dependencia',
        'email_dependencia',
        'direccion_dependencia',
        'estado_dependencia',
        'fecha_reg_dependencia',
        'fecha_mod_dependencia',
        'usuario_dependencia',
        'ip_dependencia',
    ];

    public function parentDependency()
    {
        return $this->hasOne('App\Models\Dependencia', 'id_dependencia', 'dep_id_dependencia');
    }

    public function childrenDependencies()
    {
        return $this->hasMany('App\Models\Dependencia', 'dep_id_dependencia', 'id_dependencia');
    }

}
