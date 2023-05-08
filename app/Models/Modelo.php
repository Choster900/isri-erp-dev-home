<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;

    protected $table = 'modelo';
    protected $primaryKey = 'id_modelo';
    public $timestamps = false;

    protected $fillable = [
        'id_marca',
        'nombre_modelo',
        'estado_marca',
        'fecha_reg_modelo',
        'fecha_mod_modelo',
        'ip_modelo',
        'usuario_modelo'
    ];

    public function marca()
    {
        return $this->belongsTo('App\Models\Marca','id_marca','id_marca');
    }
}
