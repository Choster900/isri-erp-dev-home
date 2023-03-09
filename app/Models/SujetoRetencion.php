<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SujetoRetencion extends Model
{
    use HasFactory;

    protected $table = 'sujeto_retencion';
    protected $primaryKey = 'id_sujeto_retencion as p_id_sujeto_retencion';
    public $timestamps = false;

    protected $fillable = [
        'nombre_sujeto_retencion',
        'iva_sujeto_retencion',
        'isrl_sujeto_retencion',
    ];

    public function proveedores()
    {
        return $this->hasMany('App\Models\Proveedor', "id_sujeto_retencion", "p_id_sujeto_retencion");
    }


}