## 1. **Endpoints d'inscription (Signup)**

## 2. **Endpoints de connexion (Login)**

- [ok]`POST /auth/login` – email/mot de passe → retourne tokens (access + refresh) (primary)
- [ok]`POST /auth/login/magic-link` – demande un lien magique par email (primary)
- [skip]`POST /auth/login/social` – accepte un token OAuth (Google, Apple, etc.) (secondary)
- [ok]`GET /auth/verify-magic-link?token=...` – échange un magic link contre des tokens

## 3. **Endpoints de tokens** (primary)

- `POST /auth/refresh` – rafraîchir l'access token (avec refresh token)
- `POST /auth/logout` – révoquer le refresh token
- `POST /auth/revoke-all` – déconnecter tous les appareils
  5

## 4. **Endpoints de gestion de mot de passe**

- `POST /auth/forgot-password` – demande une réinitialisation
- `POST /auth/reset-password` – applique le nouveau mot de passe (avec token)
- `POST /auth/change-password` – changer le mot de passe (authentifié)

## 5. **Endpoints de 2FA** (secondary)

- `POST /auth/2fa/enable` – activer 2FA (génère secret TOTP + QR code)
- `POST /auth/2fa/verify` – valider un code TOTP pour finaliser l'activation
- `POST /auth/2fa/disable` – désactiver 2FA (nécessite mot de passe ou code)
- `POST /auth/2fa/login` – valider le code après login classique
- `GET /auth/2fa/backup-codes` – récupérer les codes de secours

## 6. **Endpoints de vérification** (primary)

- `POST /auth/verify-email` – confirmer une adresse email (avec code)
- `POST /auth/verify-phone` – confirmer un numéro de téléphone
- `POST /auth/resend-verification` – renvoyer un code de vérification

## 7. **Endpoints de session / appareils** (primary)

- listener if user is logged

- `GET /auth/sessions` – lister toutes les sessions actives
- `DELETE /auth/sessions/{id}` – révoquer une session spécifique
- `DELETE /auth/sessions` – révoquer toutes les autres sessions (sauf celle en cours)

## 8. **Endpoints de gestion de compte** (primary)

- `GET /auth/me` – obtenir le profil de l'utilisateur connecté
- `PATCH /auth/me` – mettre à jour le profil

## 9. **Endpoints d'autorisation (pour les autres services)**

- `POST /auth/verify` – vérifier qu'un access token est valide (retourne le user_id + rôles)
- `GET /auth/permissions` – lister les permissions de l'utilisateur

---

## En-têtes HTTP typiques

| Code                  | Signification            |
| --------------------- | ------------------------ |
| 200 OK                | Succès                   |
| 400 Bad Request       | Erreur de validation     |
| 401 Unauthorized      | Token invalide ou expiré |
| 403 Forbidden         | Pas les permissions      |
| 409 Conflict          | Email déjà utilisé       |
| 429 Too Many Requests | Rate limiting            |
