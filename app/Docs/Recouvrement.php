<?php

namespace App\Docs;

use OpenApi\Attributes as OA;

class Recouvrement
{
    #[OA\Get(
        path: '/api/recouvrement/me',
        summary: 'Profil de l\'agent de recouvrement',
        tags: ['Recouvrement'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Informations du profil récupérées')
        ]
    )]
    public function me() {}

    #[OA\Post(
        path: '/api/recouvrement/scan-qrcode',
        summary: 'Scan du QR code d\'un contribuable',
        tags: ['Recouvrement'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['num_commerce'],
                properties: [
                    new OA\Property(property: 'num_commerce', type: 'string', example: 'C-12345'),
                    new OA\Property(property: 'qr_data', type: 'string', nullable: true, example: 'Numéro commerce: C-12345')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Contribuable trouvé'),
            new OA\Response(response: 404, description: 'Non trouvé'),
            new OA\Response(response: 422, description: 'Erreur de validation')
        ]
    )]
    public function scanQrCode() {}

    #[OA\Post(
        path: '/api/recouvrement/encaissement',
        summary: 'Encaisser le paiement d\'une taxe',
        tags: ['Recouvrement'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['num_commerce', 'taxe_id', 'nombre'],
                properties: [
                    new OA\Property(property: 'num_commerce', type: 'string', example: 'C-12345'),
                    new OA\Property(property: 'taxe_id', type: 'integer', example: 1),
                    new OA\Property(property: 'nombre', type: 'integer', minimum: 1, example: 2)
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
        summary: 'Récupérer le dernier paiement et les périodes dues',
        tags: ['Recouvrement'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['num_commerce', 'taxe_id'],
                properties: [
                    new OA\Property(property: 'num_commerce', type: 'string', example: 'C-12345'),
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
        path: '/api/recouvrement/encaissements/non-verses',
        summary: 'Liste des encaissements non versés',
        tags: ['Recouvrement'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Liste récupérée')
        ]
    )]
    public function listEncaissementsNonVerses() {}

    #[OA\Get(
        path: '/api/recouvrement/encaissements/verses',
        summary: 'Liste des encaissements versés',
        tags: ['Recouvrement'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Liste récupérée')
        ]
    )]
    public function listEncaissementsVerses() {}

    #[OA\Get(
        path: '/api/recouvrement/encaissements/{id}',
        summary: 'Détails d\'un encaissement',
        tags: ['Recouvrement'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Détails de l\'encaissement'),
            new OA\Response(response: 404, description: 'Non trouvé')
        ]
    )]
    public function showEncaissement() {}

    #[OA\Get(
        path: '/api/recouvrement/contribuable/{id}',
        summary: 'Détails d\'un contribuable',
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

    #[OA\Post(
        path: '/api/recouvrement/contribuable/{id}',
        summary: 'Modifier un contribuable',
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

    #[OA\Get(
        path: '/api/recouvrement/encaissements',
        summary: 'Liste unifiée des encaissements',
        tags: ['Recouvrement'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'statut', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['versé', 'non versé'])),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Liste récupérée')
        ]
    )]
    public function listAllEncaissements() {}

    #[OA\Get(
        path: '/api/recouvrement/dettes',
        summary: 'État des dettes de l\'agent',
        tags: ['Recouvrement'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Dettes récupérées')
        ]
    )]
    public function dettes() {}

    #[OA\Get(
        path: '/api/recouvrement/versements',
        summary: 'Historique des versements',
        tags: ['Recouvrement'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Liste des versements récupérée')
        ]
    )]
    public function versements() {}

}
