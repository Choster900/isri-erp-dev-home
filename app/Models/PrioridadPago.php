<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrioridadPago extends Model
{
    use HasFactory;
    protected $table = 'prioridad_pago';
    protected $primaryKey = 'id_prioridad_pago';
    public $timestamps = false;

    protected $fillable = [
        'nivel_prioridad_pago',
        'nombre_prioridad_pago',
    ];
}
