<?php

namespace App\Docs;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: "API SGCM",
    version: "1.0.0",
    description: "Documentation de l'API SGCM (Système de Gestion des Communes et Marchés)",
    contact: new OA\Contact(email: "support@sgcm.com")
)]
#[OA\Server(
    url: "http://127.0.0.1:8082",
    description: "Serveur Local de Développement"
)]
#[OA\SecurityScheme(
    securityScheme: "sanctum",
    type: "http",
    scheme: "bearer",
    bearerFormat: "JWT"
)]
class Auth
{
    #[OA\Post(
        path: "/api/login",
        summary: "Connexion utilisateur (Commerçant ou Agent)",
        description: "Permet l'authentification des commerçants (via email ou num_commerce) et des agents (via email ou matricule).",
        tags: ["Auth"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["identifiant", "password"],
                description: "Pour l'identifiant, utilisez : 'identifiant', 'email', 'num_commerce' ou 'agentID'. Pour le mot de passe : 'password' ou 'mot_de_passe'.",
                properties: [
                    new OA\Property(property: "identifiant", type: "string", example: "AGT001"),
                    new OA\Property(property: "password", type: "string", example: "password123"),
                    new OA\Property(property: "remember_me", type: "boolean", example: false, description: "Garder la session active plus longtemps")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Connexion réussie",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Connexion réussie"),
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(property: "token", type: "string", example: "1|abcdfgh..."),
                                new OA\Property(property: "role", type: "string", example: "agent_recouvrement"),
                                new OA\Property(
                                    property: "user",
                                    type: "object",
                                    properties: [
                                        new OA\Property(property: "id", type: "integer", example: 1),
                                        new OA\Property(property: "matricule", type: "string", example: "AGT001", description: "Matricule ou Numéro de Commerce"),
                                        new OA\Property(property: "nom", type: "string", example: "Nom de l'utilisateur"),
                                        new OA\Property(property: "email", type: "string", example: "user@example.com"),
                                        new OA\Property(property: "type", type: "string", example: "recouvrement", description: "Type d'agent (uniquement pour les agents)")
                                    ]
                                )
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Requête invalide",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "L'identifiant et le mot de passe sont requis.")
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Identifiants incorrects",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Identifiant ou mot de passe incorrect")
                    ]
                )
            )
        ]
    )]
    public function login() {}
    #[OA\Get(
        path: "/api/profile",
        summary: "Récupérer le profil de l'utilisateur connecté",
        description: "Retourne les informations de l'utilisateur actuellement authentifié (Agent ou Commerçant).",
        tags: ["Auth"],
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "Profil récupéré avec succès",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Profil récupéré avec succès"),
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(property: "role", type: "string", example: "agent_recouvrement"),
                                new OA\Property(
                                    property: "user",
                                    type: "object",
                                    properties: [
                                        new OA\Property(property: "id", type: "integer", example: 1),
                                        new OA\Property(property: "nom", type: "string", example: "Nom Apprenant"),
                                        new OA\Property(property: "email", type: "string", example: "user@example.com"),
                                        new OA\Property(property: "matricule", type: "string", example: "AGT001", description: "Présent si c'est un agent"),
                                        new OA\Property(property: "num_commerce", type: "string", example: "COM001", description: "Présent si c'est un commerçant"),
                                        new OA\Property(property: "type", type: "string", example: "recouvrement", description: "Type d'agent (si applicable)")
                                    ]
                                )
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Non authentifié",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Unauthenticated.")
                    ]
                )
            )
        ]
    )]
    public function profile() {}

    #[OA\Post(
        path: "/api/forgot-password",
        summary: "Mot de passe oublié",
        description: "Envoie un lien de réinitialisation de mot de passe à l'utilisateur.",
        tags: ["Auth"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["email"],
                properties: [
                    new OA\Property(property: "email", type: "string", example: "user@example.com")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Lien envoyé",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Lien de réinitialisation envoyé par email.")
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Utilisateur non trouvé",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Aucun utilisateur trouvé avec cet email.")
                    ]
                )
            )
        ]
    )]
    public function forgotPassword() {}

    #[OA\Post(
        path: "/api/reset-password",
        summary: "Réinitialiser le mot de passe",
        description: "Réinitialise le mot de passe en utilisant le token reçu par email.",
        tags: ["Auth"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["token", "email", "password", "password_confirmation"],
                properties: [
                    new OA\Property(property: "token", type: "string", example: "abcdef123456..."),
                    new OA\Property(property: "email", type: "string", example: "user@example.com"),
                    new OA\Property(property: "password", type: "string", example: "nouveauPassword123"),
                    new OA\Property(property: "password_confirmation", type: "string", example: "nouveauPassword123")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Mot de passe réinitialisé",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Mot de passe réinitialisé avec succès.")
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Token invalide ou expiré",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Token invalide ou expiré.")
                    ]
                )
            )
        ]
    )]
    public function resetPassword() {}
}
