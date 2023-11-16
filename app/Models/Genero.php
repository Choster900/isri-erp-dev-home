<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Genero extends Model
{
    use HasFactory;

    protected $table = 'genero';
    protected $primaryKey = 'id_genero';
    public $timestamps = false;

    protected $fillable = [
        'nombre_genero',
        //'estado_genero',
        //'fecha_reg_genero',
        //'fecha_mod_genero',
        //'usuario_genero',
        //'ip_genero',
    ];
    /**
     * Get all of the persona for the genero
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function persona(): HasMany
    {
        return $this->hasMany(Persona::class, 'id_genero', 'id_genero');
    }
}
