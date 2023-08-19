<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use OpenApi\Annotations\OpenApi;
use OpenApi\Generator;

/**
 * @OA\Schema(
 *     schema="User",
 *     title="User",
 *     description="User model",
 *     @OA\Property(property="id", type="integer", description="User ID auto incremented"),
 *     @OA\Property(property="nom", type="string", description="User last name"),
 *     @OA\Property(property="prenom", type="string", description="User first name"),
 *     @OA\Property(property="elector_card", type="string", description="User elector card"),
 *     @OA\Property(property="adresse", type="string", description="User address"),
 *     @OA\Property(property="numero_cni", type="string", description="User national ID number"),
 *     @OA\Property(property="commune", type="string", description="User commune"),
 *     @OA\Property(property="role_id", type="integer", description="User role ID (1=admin, 2=candidat, 3=user/electeur,, default=3)"),
 *     @OA\Property(property="phone", type="string", description="User phone number"),
 *     @OA\Property(property="email", type="string", format="email", description="User email"),
 *     @OA\Property(property="password", type="string", format="password", description="User password"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="User creation date and time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="User last update date and time"),
 *    @OA\Property(property="photo_url", type="image", description="photo de l'utilisateur"),
 *
 *
 * )
 */
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

    public function commentaire()
    {
        return $this->hasMany(Commentaire::class, 'id_user');
    }

    public function response(){
        return $this->hasMany(CommentaireReplique::class, 'id_user');
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
