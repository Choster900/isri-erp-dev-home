<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MotivoDesvinculoLaboral extends Model
{
    use HasFactory;

    protected $table = 'motivo_desvinculo_laboral';
    protected $primaryKey = 'id_motivo_desvinculo_laboral';
    public $timestamps = false;

    protected $fillable = [
        'nombre_motivo_desvinculo_laboral',
        'estado_motivo_desvinculo_laboral',
    ];
}
