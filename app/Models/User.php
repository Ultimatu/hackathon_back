<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [

        'nom',
        'prenom',
        'elector_card',
        'adresse',
        'numero_cni',
        'commune',
        'role_id',
        'phone',
        'email',
        'password',
        'photo_url',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }


    public function candidat()
    {
        return $this->hasOne(Candidat::class, 'user_id');
    }

    public function admin()
    {
        return $this->hasOne(Admin::class, 'id_user');
    }

    public function vote()
    {
        return $this->hasMany(Vote::class, 'voter_id');
    }

    public function sondage()
    {
        return $this->hasMany(Sondage::class, 'id_user');
    }

    public function scopeSearch($query, $val)
    {
        return $query
            ->where('nom', 'like', '%' . $val . '%')
            ->orWhere('prenom', 'like', '%' . $val . '%')
            ->orWhere('numero_cni', 'like', '%' . $val . '%')
            ->orWhere('elector_card', 'like', '%' . $val . '%')
            ->orWhere('adresse', 'like', '%' . $val . '%')
            ->orWhere('commune', 'like', '%' . $val . '%')
            ->orWhere('phone', 'like', '%' . $val . '%')
            ->orWhere('email', 'like', '%' . $val . '%');
    }

    public function scopeFilter($query, $val)
    {
        return $query
            ->where('role_id', $val);
    }


    
}
