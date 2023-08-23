<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 *     schema="Follower",
 *    title="Follower",
 *    description="Model pour un follower",
 *   @OA\Property(property="id", type="integer", format="int64", description="Follower ID"),
 *  @OA\Property(property="id_candidat", type="integer", format="int64", description="Candidat ID"),
 * @OA\Property(property="id_user", type="integer", format="int64", description="User ID"),
 * @OA\Property(property="created_at", type="string", format="date-time", description="Creation date"),
 * @OA\Property(property="updated_at", type="string", format="date-time", description="Last update date")
 * )
 *
 */
class Follower extends Model
{
    use HasFactory;

    protected $table = 'followers';

    protected $fillable = [
        'id_candidat',
        'id_user',
        'created_at',
        'updated_at',

    ];

    public function candidat()
    {
        return $this->belongsTo(Candidat::class, 'id_candidat');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    
}
