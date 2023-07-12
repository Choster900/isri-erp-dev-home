<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plaza extends Model
{
    use HasFactory;

    protected $table = 'plaza';
    protected $primaryKey = 'id_plaza';
    public $timestamps = false;

    protected $fillable = [
        'id_proy_financiado',
        'nombre_plaza',
        'codigo_plaza',
        'salario_base_plaza',
        'salario_tope_plaza',
        'fecha_reg_plaza',
        'fecha_mod_plaza',
        'usuario_plaza',
        'ip_plaza',
    ];
}
