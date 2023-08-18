<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElectionParticipant extends Model
{
    use HasFactory;

    protected $table = 'participant_elections';

    protected $fillable = [
        'id_election',
        'id_candidat',
        'created_at',
        'updated_at',
    ];

    public function election()
    {
        return $this->belongsTo(Elections::class, 'id_election');
    }

    public function candidat()
    {
        return $this->belongsTo(Candidat::class, 'id_candidat');
    }

    public function scopeFilter($query, $val)
    {
        return $query
            ->where('id_election', $val);
    }

    public function scopeSort($query, $val)
    {
        if ($val == 'recent') {
            return $query->orderBy('created_at', 'desc');
        } else if ($val == 'old') {
            return $query->orderBy('created_at', 'asc');
        }
    }

    public function scopeSearch($query, $val)
    {
        return $query
            ->where('id_candidat', $val);
    }
}


