# SOCLE - Laravel 6      

## Exigences :
     
 - PHP: >= 7.2      
 - Laravel: 6.3


## Sommaire
1. [À propos](#à-propos)
2. [Fonctionnalités](#fonctionnalités)
    1. [CRUD Basic](#crud-basic)
    2. [Authentification Tierce](#authentification-tierce)
    3. [Docker - Jenkins](#docker---jenkins)
3. [Installation](#installation)
4. [Autre](#autre)

## À propos

C'est une application Laravel, qui contient les fonctionnalités nécessaires pour un projet Laravel.
Il suffit de cloner le socle et le considérer comme un projet de départ.

## Fonctionnalités

### CRUD Basic

- Le projet est livré avec un CRUD Basic pour le model ``City``.
- Pour plus de détails et pour activer cette fonctionnalité, vous devez accéder à la branche ``feature-CrudBasics``.

### Authentification Tierce

#### L'authentification avec les réseaux sociaux

- Le socle utilise le package ``Socialite`` pour l'authentification tierce, et supporte les services suivants : github, facebook, linkedin, bitbucket, twitter et google

- Pour l'activer, il suffit d'ajouter les informations d'identification au fichier .env selon le service utilisé, par exemple pour facebook, on modifie les variables d'environnement suivantes :

```bash
FACEBOOK_ACTIVE=true
#FACEBOOK_CLIENT_ID=
#FACEBOOK_CLIENT_SECRET=
#FACEBOOK_REDIRECT=${APP_URL}/login/facebook/callback
```
- Par la suite, l'authentification par les réseaux sociaux va être supportée par le socle.

#### L'authentification avec le serveur Keycloak

- Pour activer cette fonctionnalité, vous devez accéder à la branche ``feature-ThirdPartyAuthentication-Keycloak``.

- Le serveur est utilisé dans le socle pour mettre en place un système d’authentification robuste qui gère les authorisations et donc sécuriser l'application.

- Keycloak offre une API Rest pour gérer la partie administration et propose une console d’administration pour la gestion centralisée des utilisateurs.

### Docker - Jenkins

- Le socle est livré avec son environnement de développement grâce à l'utilisation du docker.

- Vous pouvez aussi lancer le build et les tests du socle automatiquement à l'aide de Pipeline de Jenkins pour tester le socle.

- Visiter la branche : ``feature-DockerizeSocle``

## Installation 

### 1. Cloner le projet

```bash
git clone https://github.com/Zineup/Laravel6-Socle.git
```
Après, vous devez se positionner dans le répértoire du projet.

```bash
cd Laravel6-Socle
```

### 2. Fichier d'environnement

Ce package est livré avec un fichier .env.example à la racine du projet.

Exécuter la commande suivante pour créer votre fichier .env à partir du fichier .env.example.

```bash
cp .env.example .env
```

### 3. Composer

Installer les dépendances du projet en éxécutant:

```bash
composer install
```

### 4. NPM/Yarn

Installer les packages Javascript pour le développement frontend en utilisant:

**Node Package Manager :**  
```bash 
npm install
```

ou bien :

**Yarn Package Manager :**  
```bash 
yarn
```

### 5. Créer la base de données

Créer votre base de données sur votre serveur et mettre à jour les lignes suivantes du fichier .env selon les paramètres de votre base de données:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret
```

### 6. Commandes d'Artisan

**1.** Définir la clé que Laravel utilisera lors du cryptage:

```bash
php artisan key:generate
```

**2.**  Exécuter les migrations pour créer les tables de base de données

```bash
php artisan migrate
php artisan db:seed
```

### 7. NPM Run *

Maintenant, c'est le temps de compiler les feuilles de styles et les scripts en éxecutant :

```bash
npm run <commande>
```
Les commandes disponibles sont répertoriées en haut du fichier package.json sous la clé 'scripts'.

À ce stade, vous avez terminé, vous devriez pouvoir accéder au projet dans votre navigateur local et voir le projet, ainsi que pouvoir vous connecter avec l'administrateur et afficher le backend.

### 8. PHPUnit

Exécuter la suite de tests pour vous assurer que toutes les parties fonctionnent correctement.
 
```bash
vendor\bin\phpunit.bat
```

### 9. Stockage

Une fois votre projet installé, vous devez exécuter cette commande pour lier votre dossier de stockage public pour les téléchargements d'avatar des utilisateurs.

```bash
php artisan storage:link
```

### 10. Connexion

Une fois votre projet installé et que vous pouvez y accéder dans un navigateur, cliquez sur le bouton de connexion à droite de la barre de navigation.

Les informations d'identification de l'administrateur sont:

**Nom d'utilisateur:** admin@admin.com    
**Mot de passe:** secret

Les informations d'identification d'un utilisateur normal sont:

**Nom d'utilisateur:** user@user.com    
**Mot de passe:** secret

## Autre

Pour plus de détails, visitez la documentation officielle : [lien de documentation](https://laravel-boilerplate.com/6.0/documentation.html)


