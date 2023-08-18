<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meet_Participant extends Model
{
    use HasFactory;

    protected $table = 'meet_participants';

    protected $fillable = [
        'id_user',
        'id_meet',
        'created_at',
        'updated_at',
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function meets()
    {
        return $this->belongsTo(Meet::class, 'id_meet');
    }

    public function scopeSearch($query, $val)
    {
        return $query
            ->where('id_meet', $val);
    }


    public function scopeFilter($query, $val)
    {
        return $query
            ->where('id_user', $val);
    }


    public function scopeSort($query, $val)
    {
        if ($val == 'recent') {
            return $query->orderBy('created_at', 'desc');
        } else if ($val == 'old') {
            return $query->orderBy('created_at', 'asc');
        }
    }


}
