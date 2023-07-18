<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UpltDependencia extends Pivot
{
    use HasFactory;

    protected $table = 'uplt_dependencia';
    protected $primaryKey = 'id_uplt_dependencia';
    public $timestamps = false;

    protected $fillable = [
        'id_dependencia',
        'id_linea_trabajo',
        'estado_uplt_dependencia',
        'fecha_reg_uplt_dependencia',
        'fecha_mod_uplt_dependencia',
        'usuario_uplt_dependencia',
        'ip_uplt_dependencia',
    ];

    public function linea_trabajo()
    {
        return $this->belongsTo('App\Models\LineaTrabajo','id_linea_trabajo','id_linea_trabajo');
    }
    public function dependencia()
    {
        return $this->belongsTo('App\Models\Dependencia','id_dependencia','id_dependencia');
    }
}
