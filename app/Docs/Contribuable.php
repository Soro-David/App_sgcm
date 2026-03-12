<?php

namespace App\Docs;

use OpenApi\Attributes as OA;

class Contribuable
{
    #[OA\Get(
        path: "/api/contribuable/me",
        summary: "Informations sur le contribuable connecté",
        description: "Retourne les détails du commerçant actuellement authentifié.",
        tags: ["Contribuable"],
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "Détails du contribuable",
                content: new OA\JsonContent(type: "object")
            ),
            new OA\Response(response: 401, description: "Non authentifié")
        ]
    )]
    public function me() {}

    #[OA\Get(
        path: "/api/contribuable/solde",
        summary: "Consulter le solde du compte",
        description: "Retourne le montant actuel disponible sur le compte du contribuable.",
        tags: ["Contribuable - Recharge"],
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "Solde récupéré",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "solde", type: "number", example: 5000)
                    ]
                )
            )
        ]
    )]
    public function getSolde() {}

    #[OA\Post(
        path: "/api/contribuable/recharger",
        summary: "Recharger le compte (Simulation)",
        description: "Permet de créditer le compte du contribuable. Actuellement en mode simulation.",
        tags: ["Contribuable - Recharge"],
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["montant"],
                properties: [
                    new OA\Property(property: "montant", type: "number", minimum: 100, example: 1000)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Compte rechargé avec succès",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string"),
                        new OA\Property(property: "nouveau_solde", type: "number"),
                        new OA\Property(property: "recharge", type: "object")
                    ]
                )
            ),
            new OA\Response(response: 422, description: "Données invalides")
        ]
    )]
    public function recharger() {}

    #[OA\Get(
        path: "/api/contribuable/rechargements",
        summary: "Historique des rechargements",
        description: "Liste paginée des derniers rechargements effectués.",
        tags: ["Contribuable - Recharge"],
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "Liste des rechargements"
            )
        ]
    )]
    public function historiqueRecharges() {}

    #[OA\Get(
        path: "/api/contribuable/taxes",
        summary: "Liste des taxes assignées",
        description: "Retourne les taxes que le commerçant doit payer pour son établissement.",
        tags: ["Contribuable - Paiement"],
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "Liste des taxes"
            )
        ]
    )]
    public function listTaxes() {}

    #[OA\Get(
        path: "/api/contribuable/taxes/{taxeId}/periodes",
        summary: "Calculer les périodes impayées pour une taxe",
        description: "Retourne la liste des périodes (mois, jours ou ans) dues pour une taxe spécifique.",
        tags: ["Contribuable - Paiement"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(name: "taxeId", in: "path", required: true, schema: new OA\Schema(type: "integer")),
            new OA\Parameter(name: "nombre_periodes", in: "query", required: false, schema: new OA\Schema(type: "integer"), description: "Limiter le nombre de périodes à calculer")
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Périodes calculées avec succès",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "count", type: "integer"),
                        new OA\Property(property: "unite", type: "string"),
                        new OA\Property(property: "periods_list", type: "array", items: new OA\Items(type: "string")),
                        new OA\Property(property: "montant_par_periode", type: "number"),
                        new OA\Property(property: "total_a_payer", type: "number")
                    ]
                )
            )
        ]
    )]
    public function periodesImpayees() {}

    #[OA\Post(
        path: "/api/contribuable/paiement",
        summary: "Effectuer le paiement des taxes",
        description: "Débite le solde du compte pour payer un certain nombre de périodes d'une taxe.",
        tags: ["Contribuable - Paiement"],
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["taxe_id", "nombre_periodes"],
                properties: [
                    new OA\Property(property: "taxe_id", type: "integer", example: 1),
                    new OA\Property(property: "nombre_periodes", type: "integer", minimum: 1, example: 1)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Paiement réussi",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "success"),
                        new OA\Property(property: "message", type: "string"),
                        new OA\Property(property: "nouveau_solde", type: "number")
                    ]
                )
            ),
            new OA\Response(response: 400, description: "Solde insuffisant"),
            new OA\Response(response: 403, description: "Taxe non assignée"),
            new OA\Response(response: 422, description: "Données invalides")
        ]
    )]
    public function effectuerPaiement() {}

    #[OA\Get(
        path: "/api/contribuable/paiements",
        summary: "Historique des paiements de taxes",
        description: "Liste paginée de tous les paiements de taxes effectués par le commerçant.",
        tags: ["Contribuable - Paiement"],
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "Historique des paiements"
            )
        ]
    )]
    public function historiquePaiements() {}
}
