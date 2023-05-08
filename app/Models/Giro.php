<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Giro extends Model
{
    use HasFactory;

    protected $table = 'giro';
    protected $primaryKey = 'id_giro';
    public $timestamps = false;

    protected $fillable = [
        'id_padre_giro',
        'nombre_giro',
        'codigo_giro',
        'fecha_reg_giro',
        'fecha_mod_giro',
        'usuario_giro',
        'ip_giro',
    ];

    public function proveedor()
    {
        return $this->hasMany(Proveedor::class, 'id_giro', 'id_giro');
    }

    public function Quedan()
    {
        return $this->hasManyThrough(Giro::class, Proveedor::class);
    }
}