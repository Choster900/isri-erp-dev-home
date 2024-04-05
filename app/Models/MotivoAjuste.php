<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MotivoAjuste extends Model
{
    use HasFactory;

    protected $table = 'motivo_ajuste';
    protected $primaryKey = 'id_motivo_ajuste';
    public $timestamps = false;

    protected $fillable = [
        'nombre_motivo_ajuste',
    ];
}
