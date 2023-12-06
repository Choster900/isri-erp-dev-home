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
        'dep_id_dependencia',
        'id_centro_atencion',
        'id_persona',
        'jerarquia_organizacion_dependencia',
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

    public function plazas_asignadas()
    {
        return $this->hasMany('App\Models\PlazaAsignada', 'id_dependencia', 'id_dependencia');
    }

    public function detalle_quedan()
    { //(FOREING KEY, PRIMARY KEY)
        return $this->hasMany(DetalleQuedan::class, "id_quedan", "id_quedan");
    }

    public function parent_dependency()
    {
        return $this->hasOne('App\Models\Dependencia', 'id_dependencia', 'dep_id_dependencia');
    }
    //I must check this to know if it's still necessary
    public function children_dependencies()
    {
        return $this->hasMany('App\Models\Dependencia', 'dep_id_dependencia', 'id_dependencia');
    }
    //I must check this to know if it's still necessary
    public function jefatura()
    {
        return $this->belongsTo('App\Models\Persona', 'id_persona', 'id_persona');
    }
    
    public function dependencias_inferiores()
    {
        return $this->hasMany('App\Models\Dependencia', 'jerarquia_organizacion_dependencia', 'id_dependencia');
    }
    public function dependencia_superior()
    {
        return $this->hasOne('App\Models\Dependencia', 'id_dependencia', 'jerarquia_organizacion_dependencia');
    }
    /**
     * The function "centro_atencion" returns a relationship between the current model and the
     * "CentroAtencion" model.
     */
    public function centro_atencion()
    {
        return $this->belongsTo('App\Models\CentroAtencion', 'id_centro_atencion', 'id_centro_atencion');
    }
}
