<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoMine extends Model
{
    use HasFactory;
    protected $table = 'tipo_mine';
    protected $primaryKey = 'id_tipo_mime';
    public $timestamps = false;
    protected $fillable = [
        'extension_tipo_mime',
        'img_tipo_mime',
    ];
}
