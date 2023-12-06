<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentroAtencion extends Model
{
    use HasFactory;

    protected $table = 'centro_atencion';
    protected $primaryKey = 'id_centro_atencion';
    public $timestamps = false;

    protected $fillable = [
        'nombre_centro_atencion',
        'codigo_centro_atencion',
        'pagaduria_centro_atencion',
        'telefono_centro_atencion',
        'email_centro_atencion',
        'direccion_centro_atencion',
        'estado_centro_atencion',
        'fecha_reg_centro_atencion',
        'fecha_mod_centro_atencion',
        'usuario_centro_atencion',
        'ip_centro_atencion'
    ];

    /**
     * The function "dependencias" returns a collection of "Dependencia" models that are associated
     * with a specific "id_centro_atencion" value.
     * 
     * @return a collection of "Dependencia" models that are associated with the current model
     * instance. The association is based on the "id_centro_atencion" attribute of both models.
     */
    public function dependencias()
    {
        return $this->hasMany('App\Models\Dependencia', 'id_centro_atencion', 'id_centro_atencion');
    }
}
