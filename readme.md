# SOCLE - Laravel 6   

## Serveur Keycloak

- Le serveur est utilisé dans le socle pour mettre en place un système d’authentification robuste qui gère les autorisations et donc sécuriser l'application.

- Keycloak offre une API Rest pour gérer la partie administration et propose une console d’administration pour la gestion centralisée des utilisateurs.

## Configuration de Serveur

### Roles :

- Après avoir installé le serveur et créé un Realm personnalisé, vous devez créer des rôles sur votre Realm qui seront globaux pour tous les clients.
- Ces rôles doivent être composites pour qu'ils soient composés par les rôles des clients qui expriment la même chose.
- par exemple :
    - Créer un rôle ``admin`` pour le client utilisé dans le projet
    - Créer un rôle global pour le Realm ``app-admin``
    - Modifier le rôle pour qu'il soit composite
    - Ajouter le rôle ``admin`` du client dans le rôle ``app-admin`` du Realm
    
### Propriété created_at :

- Par défaut, le serveur n'intègre pas la propriété ``created_at`` avec les propriétés des utilisateurs, donc vous devez la configurer manuellement, et vous trouverez que le projet l'utilise déjà pour afficher plus de détails sur les utilisateurs

### Installation

#### 1. Cloner le projet

```bash
git clone https://github.com/Zineup/Laravel6-Socle.git
```
#### 2. Se positionner dans le répértoire du projet

```bash
cd Laravel6-Socle
```

#### 3. Accéder à la branche 

```bash
git checkout feature-ThirdPartyAuthentication-Keycloak
```

#### 4. Créer le fichier .env

```bash
cp .env.example .env
```

#### 4. Modifier les informations du Serveur

```bash
KEYCLOAK_BASE_URL=http://localhost:8080/auth
KEYCLOAK_REALM=
KEYCLOAK_REALM_PUBLIC_KEY=
KEYCLOAK_CLIENT_ID=
KEYCLOAK_CLIENT_SECRET=
```
#### 5. Exécuter les commandes suivantes:

a. ``composer install``

b. ``npm install``

c. ``npm run dev``

d. ``php artisan key:generate``

e. ``php artisan storage:link``

#### 5. Démarrer l'application

```bash
php artisan serve
```
