<?php

namespace App\Docs\Recouvrement;

/**
 * @OA\Tag(
 *     name="Recouvrement",
 *     description="Endpoints pour les agents de recouvrement"
 * )
 */
class RecouvrementDocs
{
    /**
     * @OA\Get(
     *     path="/api/recouvrement/me",
     *     summary="Profil de l'agent de recouvrement",
     *     description="Récupère les informations de l'agent de recouvrement actuellement connecté.",
     *     tags={"Recouvrement"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Informations du profil récupérées",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function me_docs() {}

    /**
     * @OA\Post(
     *     path="/api/recouvrement/scan-qrcode",
     *     summary="Scan du QR code d'un contribuable",
     *     description="Retourne les informations du contribuable, les taxes assignées et les périodes dues.",
     *     tags={"Recouvrement"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"num_commerce"},
     *             @OA\Property(property="num_commerce", type="string", example="C-12345"),
     *             @OA\Property(property="qr_data", type="string", nullable=true, example="Numéro commerce: C-12345")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Contribuable trouvé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Contribuable trouvé avec succès."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="contribuable", type="object"),
     *                 @OA\Property(property="taxes", type="array", @OA\Items(type="object")),
     *                 @OA\Property(property="total_montant_du", type="number", example=5000),
     *                 @OA\Property(property="nombre_taxes_dues", type="integer", example=2)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Contribuable non trouvé"),
     *     @OA\Response(response=422, description="Erreur de validation")
     * )
     */
    public function scanQrCode_docs() {}

    /**
     * @OA\Post(
     *     path="/api/recouvrement/encaissement",
     *     summary="Encaisser un paiement de taxe",
     *     description="Permet à l'agent d'enregistrer le paiement de plusieurs périodes pour une taxe donnée.",
     *     tags={"Recouvrement"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"num_commerce", "taxe_id", "nombre"},
     *             @OA\Property(property="num_commerce", type="string", example="C-12345"),
     *             @OA\Property(property="taxe_id", type="integer", example=1),
     *             @OA\Property(property="nombre", type="integer", example=2, description="Nombre de périodes à payer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Encaissement effectué avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Encaissement effectué avec succès."),
     *             @OA\Property(property="periodes_enregistrees", type="array", @OA\Items(type="string", example="03/2024")),
     *             @OA\Property(property="periodes_restantes", type="array", @OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(response=403, description="Taxe non assignée"),
     *     @OA\Response(response=422, description="Erreur de validation")
     * )
     */
    public function encaisserPaiement_docs() {}

    /**
     * @OA\Post(
     *     path="/api/recouvrement/paiement/periodes-dues",
     *     summary="Récupérer le dernier paiement et les périodes dues",
     *     description="Affiche la date du dernier paiement et la liste des périodes qu'il reste à payer pour une taxe spécifique.",
     *     tags={"Recouvrement"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"num_commerce", "taxe_id"},
     *             @OA\Property(property="num_commerce", type="string", example="C-12345"),
     *             @OA\Property(property="taxe_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des périodes dues récupérée",
     *         @OA\JsonContent(
     *             @OA\Property(property="dernier_paiement", type="string", nullable=true, example="02/2024"),
     *             @OA\Property(property="frequence", type="string", example="mois"),
     *             @OA\Property(property="periodes_dues", type="array", @OA\Items(type="string", example="03/2024"))
     *         )
     *     ),
     *     @OA\Response(response=404, description="Contribuable non trouvé"),
     *     @OA\Response(response=422, description="Erreur de validation")
     * )
     */
    public function dernierPaiementEtDues_docs() {}

    /**
     * @OA\Post(
     *     path="/api/recouvrement/logout",
     *     summary="Déconnexion",
     *     description="Invalide le jeton d'accès de l'agent de recouvrement.",
     *     tags={"Recouvrement"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Déconnexion réussie",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Déconnexion réussie")
     *         )
     *     )
     * )
     */
    public function logout_docs() {}

    /**
     * @OA\Get(
     *     path="/api/recouvrement/encaissements/non-verses",
     *     summary="Liste des encaissements non versés",
     *     description="Liste tous les encaissements effectués par l'agent qui n'ont pas encore été versés à la mairie.",
     *     tags={"Recouvrement"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des encaissements récupérée",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function listEncaissementsNonVerses_docs() {}

    /**
     * @OA\Get(
     *     path="/api/recouvrement/encaissements/verses",
     *     summary="Liste des encaissements versés",
     *     description="Liste tous les encaissements effectués par l'agent qui ont déjà été versés à la mairie.",
     *     tags={"Recouvrement"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des encaissements récupérée",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function listEncaissementsVerses_docs() {}

    /**
     * @OA\Get(
     *     path="/api/recouvrement/encaissements/{id}",
     *     summary="Détails d'un encaissement",
     *     description="Récupère les informations complètes d'un encaissement par son ID.",
     *     tags={"Recouvrement"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de l'encaissement",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails de l'encaissement récupérés",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Encaissement non trouvé")
     * )
     */
    public function showEncaissement_docs() {}
}
