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
            'pt_id', ['required', 'integer', 'exists:parti_politiques,id'],
            'nom', ['required', 'string'],
            'prenom', ['required', 'string'],
            'bio', 'string',
            'photo_url', ['image', 'mimes:jpeg,png,jpg,gif,svg', 'nullable'],
            'commune', ['required', 'string'],
            'phone', ['required', 'string'],
            'email', ['required', 'string', 'email', 'unique:users'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'pt_id.required' => 'Le parti politique est obligatoire',
            'pt_id.integer' => 'Le parti politique doit être un entier',
            'pt_id.exists' => 'Le parti politique n\'existe pas',
            'nom.required' => 'Le nom est obligatoire',
            'nom.string' => 'Le nom doit être une chaîne de caractères',
            'prenom.required' => 'Le prénom est obligatoire',
            'prenom.string' => 'Le prénom doit être une chaîne de caractères',
            'bio.string' => 'La bio doit être une chaîne de caractères',
            'photo_url.image' => 'La photo doit être une image',
            'photo_url.mimes' => 'La photo doit être une image de type jpeg, png, jpg, gif ou svg',
            'commune.required' => 'La commune est obligatoire',
            'commune.string' => 'La commune doit être une chaîne de caractères',
            'phone.required' => 'Le numéro de téléphone est obligatoire',
            'phone.string' => 'Le numéro de téléphone doit être une chaîne de caractères',
            'email.required' => 'L\'email est obligatoire',
            'email.string' => 'L\'email doit être une chaîne de caractères',
            'email.email' => 'L\'email doit être une adresse email valide',
            'email.unique' => 'L\'email doit être unique',
        ];
    }



}



