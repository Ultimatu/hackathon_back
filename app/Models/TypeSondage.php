<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *    schema="TypeSondage",
 *   title="TypeSondage",
 *  description="Model for representing a survey type.",
 * @OA\Property(property="id", type="integer", format="int64", description="Survey type ID"),
 * @OA\Property(property="nom", type="string", description="Name of the survey type"),
 * @OA\Property(property="description", type="string", description="Description of the survey type"),
 * @OA\Property(property="created_at", type="string", format="date-time", description="Creation date"),
 * @OA\Property(property="updated_at", type="string", format="date-time", description="Last update date")
 * )
 * 
 */

class TypeSondage extends Model
{
    use HasFactory;

    protected $table = 'types_sondages';

    protected $fillable = [
        'nom',
        'description',
        'created_at',
        'updated_at',
    ];

    public function sondages()
    {
        return $this->hasMany(Sondage::class, 'id_type_sondage');
    }

    public function scopeSearch($query, $val)
    {
        return $query
            ->where('nom', 'like', '%' . $val . '%')
            ->orWhere('description', 'like', '%' . $val . '%');
    }

    public function scopeFilter($query, $val)
    {
        return $query
            ->where('id', $val);
    }
}
