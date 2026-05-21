┌─────────────┐ ┌─────────────┐ ┌─────────────┐ ┌─────────────┐
│ Client │ │ Serveur │ │ Email │ │ Base de │
│ (Front) │ │ (API) │ │ Service │ │ données │
└─────────────┘ └─────────────┘ └─────────────┘ └─────────────┘
│ │ │ │
│ 1. POST /auth/login/magic-link │ │
│ {email: "user@example.com"} │ │
│──────────────────>│ │ │
│ │ │ │
│ │ 2. Générer token unique │
│ │ (random_bytes) │
│ │──────────────────────────────────────>│
│ │ │ │
│ │ 3. Stocker token + email + expiration │
│ │<──────────────────────────────────────│
│ │ │ │
│ │ 4. Construire lien magique │
│ │ https://app.com/auth/verify?token=xyz│
│ │ │ │
│ │ 5. Envoyer email avec lien │
│ │──────────────────>│ │
│ │ │ │
│ 6. 200 OK │ │ │
│<──────────────────│ │ │
│ │ │ │
│ │ │ │
│ 7. User clique sur lien dans l'email │
│ │ │ │
│ 8. GET /auth/verify-magic-link?token=xyz │
│──────────────────>│ │ │
│ │ │ │
│ │ 9. Vérifier token valide │
│ │ - Existe ? │
│ │ - Non utilisé ? │
│ │ - Pas expiré ? │
│ │──────────────────────────────────────>│
│ │ │ │
│ │ 10. Marquer token comme utilisé │
│ │──────────────────────────────────────>│
│ │ │ │
│ │ 11. Récupérer ou créer l'utilisateur │
│ │ │ │
│ │ 12. Générer tokens JWT │
│ │ - Access token │
│ │ - Refresh token │
│ │ │ │
│ 13. Tokens JWT │ │ │
│<──────────────────│ │ │
│ │ │ │
