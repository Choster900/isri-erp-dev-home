<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Profesion extends Model
{
    use HasFactory;

    protected $table = 'profesion';
    protected $primaryKey = 'id_profesion';
    public $timestamps = false;

    protected $fillable = [
        'nombre_profesion',
        'id_profesion_rnpn',
        'nombre_profesion_rnpn',
    ];
    /**
     * Get all of the empleados for the Profesion
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function personas(): HasMany
    {
        return $this->hasMany(Personas::class, 'id_profesion', 'id_profesion');
    }
    
}
