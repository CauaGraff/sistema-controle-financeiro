<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Empresas;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'id_typeuser',
        'id_escritorio',
        'active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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

    public function isAdmim(): bool
    {
        return in_array($this->id_typeuser, ['1']);
    }

    public function isEscritorio(): bool
    {
        return in_array($this->id_typeuser, ['2']);
    }

    public function isCliente(): bool
    {
        return in_array($this->id_typeuser, ['3']);
    }

    public function empresas()
    {
        return $this->belongsToMany(Empresas::class, 'users_empresas', 'id_user', 'id_empresas')->withTimestamps();
    }

}
