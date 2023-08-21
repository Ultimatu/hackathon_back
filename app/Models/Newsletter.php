<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *    schema="Newsletter",
 *  title="Newsletter",
 * description="Model for representing a newsletter.",
 * @OA\Property(property="id", type="integer", format="int64", description="Newsletter ID"),
 * @OA\Property(property="email", type="string", description="Email of the newsletter"),
 * @OA\Property(property="is_active", type="boolean", description="Status of the newsletter"),
 * @OA\Property(property="created_at", type="string", format="date-time", description="Creation date"),
 * @OA\Property(property="updated_at", type="string", format="date-time", description="Last update date")
 * )
 * 
 */

class Newsletter extends Model
{
    use HasFactory;

    protected $table = 'newsletter';

    protected $fillable = [
        'email',
        'is_active',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }
}
