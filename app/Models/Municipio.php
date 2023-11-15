<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Municipio extends Model
{
    use HasFactory;

    protected $table = 'municipio';
    protected $primaryKey = 'id_municipio';

    protected $casts = [ 'id_municipio' => 'string' ];

    public $timestamps = false;

    protected $fillable = [
        'id_departamento',
        'nombre_municipio',
        'codigo_admon_municipio',
    ];

    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class,'id_departamento','id_departamento');
    }
    /**
     * Get all of the personas for the Municipio
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function personas(): HasMany
    {
        return $this->hasMany(Persona::class, 'id_municipio', 'id_municipio');
    }
    
    /**
     * Get all of the residencia for the Municipio
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function residencia(): HasMany
    {
        return $this->hasMany(Residencia::class, 'id_municipio', 'id_municipio');
    }
}
