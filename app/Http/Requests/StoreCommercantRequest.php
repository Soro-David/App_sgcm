<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreCommercantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Assurez-vous que seul un agent authentifié peut faire cette requête.
        return Auth::guard('agent')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:commercants,email,' . $this->commercant,
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'num_commerce' => 'required|string|unique:commercants,num_commerce',
            'secteur_id' => 'required|exists:secteurs,id',
            'type_contribuable_id' => 'required|exists:type_contribuables,id',
            'taxe_ids' => 'required|array', // Valide que c'est un tableau
            'taxe_ids.*' => 'exists:taxes,id', // Valide que chaque ID de taxe existe dans la table 'taxes'
            'type_piece' => 'required|string|in:cni,attestation,passeport,consulaire,autre',
            'numero_piece' => 'nullable|string|max:255',
            'autre_type_piece' => 'nullable|string|max:255|required_if:type_piece,autre',
            'photo_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB Max
            'photo_recto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photo_verso' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
    
    /**
     * Messages d'erreur personnalisés pour la validation.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom et prénom sont obligatoires.',
            'taxe_ids.required' => 'Veuillez sélectionner au moins une taxe.',
            'secteur_id.required' => 'Le secteur est obligatoire.',
            'photo_profil.image' => 'Le fichier de profil doit être une image.',
            'photo_profil.max' => 'La photo de profil ne doit pas dépasser 2 Mo.',
        ];
    }
}