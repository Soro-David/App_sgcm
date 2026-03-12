<?php

namespace App\Docs;

use OpenApi\Attributes as OA;

class Recouvrement
{
    #[OA\Get(
        path: '/api/recouvrement/me',
        summary: 'Informations sur l\'agent de recouvrement connecté',
        tags: ['Recouvrement'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Détails de l\'agent')
        ]
    )]
    public function me() {}

    #[OA\Post(
        path: '/api/recouvrement/encaissement',
        summary: 'Encaisser le paiement d\'une taxe',
        description: 'Permet à l\'agent d\'enregistrer un paiement pour un commerçant.',
        tags: ['Recouvrement'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['num_commerce', 'taxe_id', 'nombre'],
                properties: [
                    new OA\Property(property: 'num_commerce', type: 'string', example: 'CONT0001'),
                    new OA\Property(property: 'taxe_id', type: 'integer', example: 1),
                    new OA\Property(property: 'nombre', type: 'integer', minimum: 1, example: 1)
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Paiement encaissé'),
            new OA\Response(response: 422, description: 'Données invalides'),
            new OA\Response(response: 403, description: 'Taxe non assignée')
        ]
    )]
    public function encaisser() {}

    #[OA\Post(
        path: '/api/recouvrement/paiement/periodes-dues',
        summary: 'Récupérer les périodes dues pour une taxe',
        tags: ['Recouvrement'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['num_commerce', 'taxe_id'],
                properties: [
                    new OA\Property(property: 'num_commerce', type: 'string', example: 'CONT0001'),
                    new OA\Property(property: 'taxe_id', type: 'integer', example: 1)
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Périodes récupérées')
        ]
    )]
    public function periodesDues() {}

    #[OA\Get(
        path: '/api/recouvrement/contribuable/{id}',
        summary: 'Détails d\'un contribuable pour l\'agent de recouvrement',
        tags: ['Recouvrement'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Détails du contribuable'),
            new OA\Response(response: 404, description: 'Non trouvé')
        ]
    )]
    public function showContribuable() {}

    #[OA\Put(
        path: '/api/recouvrement/contribuable/{id}',
        summary: 'Modifier un contribuable (Agent de Recouvrement)',
        description: 'Permet à l\'agent de recouvrement de mettre à jour les informations d\'un commerçant.',
        tags: ['Recouvrement'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: 'nom', type: 'string'),
                        new OA\Property(property: 'email', type: 'string', format: 'email'),
                        new OA\Property(property: 'telephone', type: 'string'),
                        new OA\Property(property: 'adresse', type: 'string'),
                        new OA\Property(property: 'secteur_id', type: 'integer'),
                        new OA\Property(property: 'type_contribuable_id', type: 'integer'),
                        new OA\Property(property: 'taxe_ids[]', type: 'array', items: new OA\Items(type: 'integer')),
                        new OA\Property(property: 'type_piece', type: 'string', enum: ['cni', 'attestation', 'passeport', 'consulaire', 'autre']),
                        new OA\Property(property: 'numero_piece', type: 'string'),
                        new OA\Property(property: 'autre_type_piece', type: 'string'),
                        new OA\Property(property: 'photo_profil', type: 'string', format: 'binary'),
                        new OA\Property(property: 'photo_recto', type: 'string', format: 'binary'),
                        new OA\Property(property: 'photo_verso', type: 'string', format: 'binary'),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Mis à jour avec succès'),
            new OA\Response(response: 404, description: 'Non trouvé'),
            new OA\Response(response: 422, description: 'Erreur de validation')
        ]
    )]
    public function updateContribuable() {}

    #[OA\Post(
        path: '/api/recouvrement/logout',
        summary: 'Déconnexion',
        tags: ['Recouvrement'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Déconnecté')
        ]
    )]
    public function logout() {}
}
