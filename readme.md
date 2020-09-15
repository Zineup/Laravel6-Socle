# SOCLE - Laravel 6   

## Utilisation de Docker

- L'utilisation du Docker nous permet de créer un environnement de développement qui intègre :
    - PHP
    - MySQL
    - Nginx
    - phpMyAdmin
    - Jenkins

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
git checkout feature-DockerizeSocle
```

#### 4. Créer le fichier .env

```bash
cp .env.example .env
```

#### 5. Démarrer l'application

```bash
docker-compose up -d
```

#### 6. Accéder au Workspace

```bash
docker-compose exec --user=laradock workspace bash
```
- Maintenant vous pouvez exécuter les commandes de composer et de migrations de la base de données
- Et vous pouvez accéder à votre application sur le port de nginx : ``http://localhost:8077/``

## Utilisation de Jenkins :

- Vous pouvez accéder au Jenkins via l'url : ``http://localhost:8085/``

- L'application est livrée avec Jenkinsfile, donc vous pouvez le lancer depuis Jenkins en utilisant Pipeline
