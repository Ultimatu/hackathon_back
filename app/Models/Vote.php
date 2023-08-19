<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 *     schema="Vote",
 *     title="Vote",
 *     description="Model for representing a vote.",
 *     @OA\Property(property="id", type="integer", format="int64", description="Vote ID"),
 *     @OA\Property(property="election_id", type="integer", description="Election ID that the vote belongs to"),
 *     @OA\Property(property="voter_id", type="integer", description="ID of the user who cast the vote"),
 *     @OA\Property(property="candidat_id", type="integer", description="ID of the candidate the vote is cast for"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation date"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Last update date")
 * )
 */
class Vote extends Model
{
    use HasFactory;

    protected $table = 'votes';

    protected $fillable = [
        'election_id',
        'voter_id',
        'candidat_id',
        'created_at',
        'updated_at',
    ];

    public function election()
    {
        return $this->belongsTo(Election::class, 'election_id');
    }


    public function candidat()
    {
        return $this->belongsTo(Candidat::class, 'candidat_id');
    }

    public function voter()
    {
        return $this->belongsTo(User::class, 'voter_id');
    }


    public function scopeFilter($query, $val)
    {
        return $query
            ->where('election_id', $val);
    }

    public function scopeFilterByCandidat($query, $val)
    {
        return $query
            ->where('candidat_id', $val);
    }

    public function scopeFilterByVoter($query, $val)
    {
        return $query
            ->where('voter_id', $val);
    }

}
