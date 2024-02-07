<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogoUnspsc extends Model
{
    use HasFactory;

    protected $table = 'catalogo_unspsc';
    protected $primaryKey = 'id_catalogo_unspsc';
    public $timestamps = false;

    protected $fillable = [
        'id_padre_catalogo_unspsc',
        'codigo_catalogo_unspsc',
        'nombre_catalogo_unspsc',
        'nombre_catalogo_english_unspsc',
        'fecha_reg_catalogo_unspsc',
    ];
}
