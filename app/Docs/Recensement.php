<?php

namespace App\Docs;

use OpenApi\Attributes as OA;

class Recensement
{
    #[OA\Get(
        path: '/api/recensement/contribuable',
        summary: "Liste des contribuables (commerçants) recensés par l'agent",
        description: "Retourne la liste paginée des contribuables enregistrés par l'agent de recensement actuellement connecté.",
        tags: ['Recensement'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Liste des contribuables récupérée avec succès'
            ),
            new OA\Response(
                response: 403,
                description: 'Accès refusé'
            ),
        ]
    )]
    public function index() {}

    #[OA\Get(
        path: '/api/recensement/generate-num-commerce',
        summary: 'Générer un numéro de commerce automatique',
        description: "Génère un nouveau numéro de commerce basé sur le préfixe de la mairie de l'agent et le dernier numéro utilisé.",
        tags: ['Recensement'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Numéro de commerce généré',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'num_commerce', type: 'string', example: 'MAIR0001'),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Mairie non trouvée'
            ),
        ]
    )]
    public function generateNumCommerce() {}

    #[OA\Post(
        path: '/api/recensement/contribuable',
        summary: 'Ajouter un nouveau contribuable (Commerçant)',
        description: "Enregistre un nouveau commerçant. Si le secteur_id n'est pas fourni, celui de l'agent est utilisé par défaut.",
        tags: ['Recensement'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    required: ['nom', 'type_contribuable_id', 'taxe_ids', 'type_piece'],
                    properties: [
                        new OA\Property(property: 'nom', type: 'string', description: 'Nom complet du commerçant'),
                        new OA\Property(property: 'email', type: 'string', format: 'email'),
                        new OA\Property(property: 'telephone', type: 'string'),
                        new OA\Property(property: 'adresse', type: 'string'),
                        new OA\Property(property: 'secteur_id', type: 'integer', description: "ID du secteur (Optionnel si l'agent a un secteur)"),
                        new OA\Property(property: 'type_contribuable_id', type: 'integer', description: 'ID du type de contribuable'),
                        new OA\Property(property: 'taxe_ids[]', type: 'array', items: new OA\Items(type: 'integer'), description: 'Tableau des IDs de taxes'),
                        new OA\Property(property: 'type_piece', type: 'string', enum: ['cni', 'attestation', 'passeport', 'consulaire', 'autre']),
                        new OA\Property(property: 'numero_piece', type: 'string'),
                        new OA\Property(property: 'autre_type_piece', type: 'string', description: "Requis si type_piece est 'autre'"),
                        new OA\Property(property: 'photo_profil', type: 'string', format: 'binary'),
                        new OA\Property(property: 'photo_recto', type: 'string', format: 'binary'),
                        new OA\Property(property: 'photo_verso', type: 'string', format: 'binary'),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Commerçant ajouté avec succès'
            ),
            new OA\Response(
                response: 422,
                description: 'Erreur de validation ou secteur manquant'
            ),
            new OA\Response(
                response: 403,
                description: 'Accès refusé'
            ),
        ]
    )]
    public function store() {}

    #[OA\Get(
        path: '/api/recensement/contribuable/{id}',
        summary: "Détails d'un commerçant",
        tags: ['Recensement'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Détails du commerçant'
            ),
            new OA\Response(
                response: 404,
                description: 'Non trouvé'
            ),
        ]
    )]
    public function show() {}

    #[OA\Get(
        path: '/api/recensement/contribuables-liste',
        summary: 'Liste de tous les contribuables de la mairie',
        description: "Retourne la liste paginée de tous les contribuables appartenant à la même mairie que l'agent de recensement connecté.",
        tags: ['Recensement'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Liste des contribuables récupérée avec succès'
            ),
            new OA\Response(
                response: 403,
                description: 'Accès refusé'
            ),
        ]
    )]
    public function listContribuables() {}

    #[OA\Put(
        path: '/api/recensement/contribuable/{id}',
        summary: 'Modifier un contribuable existant',
        description: "Met à jour les informations d'un commerçant. Les champs sont optionnels (via multipart/form-data ou JSON).",
        tags: ['Recensement'],
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
            new OA\Response(
                response: 200,
                description: 'Contribuable mis à jour avec succès'
            ),
            new OA\Response(
                response: 404,
                description: 'Non trouvé'
            ),
            new OA\Response(
                response: 422,
                description: 'Erreur de validation'
            )
        ]
    )]
    public function update() {}
}
