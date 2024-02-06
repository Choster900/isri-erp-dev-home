<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcesoCompra extends Model
{
    use HasFactory;

    protected $table = 'proceso_compra';
    protected $primaryKey = 'id_proceso_compra';
    public $timestamps = false;

    protected $fillable = [
        'nombre_proceso_compra',
    ];
}
