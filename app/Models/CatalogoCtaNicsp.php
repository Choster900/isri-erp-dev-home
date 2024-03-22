<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogoCtaNicsp extends Model
{
    use HasFactory;

    protected $table = 'catalogo_cta_nicsp';
    protected $primaryKey = 'id_ccta_nicsp';
    public $timestamps = false;

    protected $fillable = [
        'id_padre_ccta_nicsp',
        'codigo_ccta_nicsp',
        'nombre_ccta_nicsp',
        'nombre_catalogo_english_unspsc',
        'descripcion_ccta_nicsp',
    ];
}
