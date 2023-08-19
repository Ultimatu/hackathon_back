<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 *     schema="Admin",
 *     title="Admin",
 *     description="Admin model",
 *     @OA\Property(property="id_user", type="integer", description="User ID associated with the admin"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Admin creation date and time", readOnly="true"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Admin last update date and time", readOnly="true")
 * )
 */
class Admin extends Model
{
    use HasFactory;

    protected $table = 'admin';

    protected $fillable = [
        'id_user',
        'created_at',
        'updated_at',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }


}
