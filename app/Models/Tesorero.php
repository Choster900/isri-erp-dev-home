<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tesorero extends Model
{
    use HasFactory;

    protected $table = 'tesorero';
    protected $primaryKey = 'id_tesorero';
    public $timestamps = false;

    protected $fillable = [
        'dui_tesorero',
        'nombre_tesorero',
        'estado_tesorero',
        'fecha_reg_tesorero',
        'fecha_mod_tesorero',
        'usuario_tesorero',
        'ip_tesorero',

    ];

    public function Quedan()
    {
        return $this->hasMany(Quedan::class, "id_tesorero", "id_tesorero");
    }
}
