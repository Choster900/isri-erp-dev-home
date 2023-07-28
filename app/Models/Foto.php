<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Foto extends Model
{
    use HasFactory;

    protected $table = 'foto';
    protected $primaryKey = 'id_foto';
    public $timestamps = false;

    protected $fillable = [
        'url_foto',
        'estado_foto',
        'fecha_reg_foto',
        'fecha_mod_foto',
        'usuario_foto',
        'ip_foto'
    ];

    public function persona()
    {
        return $this->belongsTo('App\Models\Persona','id_persona','id_persona');
    }
}
