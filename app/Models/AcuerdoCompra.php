<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcuerdoCompra extends Model
{
    use HasFactory;


    protected $table = 'acuerdo_compra';
    protected $primaryKey = 'id_acuerdo_compra';
    public $timestamps = false;

    protected $fillable = [
        'nombre_acuerdo_compra',
        'estado_acuerdo_compra',
    ];

    
    public function quedan()
    {
        return $this->hasMany(Quedan::class, "id_acuerdo_compra", "id_acuerdo_compra");
    }
}