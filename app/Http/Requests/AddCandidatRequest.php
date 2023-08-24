<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

//definir comme un model pour swagger

/**
 * @OA\Schema(
 *     schema="CandidatRequest",
 *     title="Candidat-Add-Request",
 *     description="Model for a Candidat",
 * @OA\Property(property="pt_id", type="integer"),
 *    @OA\Property(property="nom", type="string"),
 *   @OA\Property(property="prenom", type="string"),
 *  @OA\Property(property="bio", type="string"),
 * @OA\Property(property="photo_url", type="string"),
 * @OA\Property(property="commune", type="string"),
 * @OA\Property(property="phone", type="string"),
 * @OA\Property(property="email", type="string"),
 * )
 */

class AddCandidatRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }



    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            
        ];
    }





}



