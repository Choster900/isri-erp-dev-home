<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'usuario';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_persona',
        'nick_usuario',
        'password_usuario',
        'estado_usuario',
        'fecha_reg_usuario',
        'fecha_mod_usuario',
        'usuario_usuario',
        'ip_usuario'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password_usuario',
        //'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsToMany('App\Models\Rol', 'permiso_usuario', 'id_usuario', 'id_rol')
            ->using('App\Models\PermisoUsuario')
            ->withPivot([
                'id_permiso_usuario',
                'estado_permiso_usuario'
            ]);
    }

    public function persona()
    {
        return $this->hasOne('App\Models\Persona', 'id_usuario', 'id_usuario');
    }

    //Check if the roles is active and if the user has this role assigned.
    public function hasRole($id_usuario, $id_rol)
    {
        $rol = Rol::find($id_rol);
        $contador = 0;
        if ($rol) {
            foreach ( $rol->usuarios as $user ) {
                if ($user->pivot->id_usuario == $id_usuario && $user->pivot->estado_permiso_usuario == 1 && $rol->estado_rol == 1) {
                    $contador++;
                }
            }
        }
        if ($contador == 0) {
            return false;
        } else {
            return true;
        }

    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password_usuario;
    }


    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
