<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'apellidos',
        'email',
        'password',
        'PIN',
        'NIF',
        'tel1',
        'rol_id',
        'estado',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'NIF',
        'PIN',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    public function bloqueosCamionMaterial()
    {
        return $this->hasMany(Bloqueo_Camion_Material::class, 'usuario_id');
    }

    public function bloqueosKgMaterial()
    {
        return $this->hasMany(Bloqueo_Kg_Material::class, 'usaurio_id');
    }

}
