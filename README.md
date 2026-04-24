# Ancres Logicielles

Ancres Logicielles est une application web communautaire francophone dédiée au partage de liens autour des logiciels. Pensée comme un espace de veille et d’échanges, elle centralise des liens, permet de découvrir des outils, d’évaluer des propositions et de discuter de bonnes pratiques.

Ce dépôt contient une application MVC écrite en PHP, livrée avec un environnement Docker (FrankenPHP + MariaDB) pour faciliter l’exécution locale.

⚠️ Avertissement
- Ce projet a un objectif strictement pédagogique et de découverte/apprentissage.
- Il n’a pas vocation à être déployé en production.
- La sécurité, la robustesse, la performance et le support long terme ne sont pas garantis.

## Sommaire
- Contexte et objectifs
- Fonctionnalités principales
- Architecture du projet
- Prérequis
- Démarrage rapide (Docker)
- Configuration (variables d’environnement)
- Base de données (initialisation)
- Développement (structure MVC, routes, logs)
- Limitations actuelles et pistes d’amélioration
- Licence


## Contexte et objectifs
- Projet réalisé dans le cadre de l’examen RNCP 37273 (https://www.francecompetences.fr/recherche/rncp/37273/).
- Application « from scratch » en architecture MVC avec opérations CRUD.
- Objectif double : démonstration de compétences et base pour de futures évolutions.


## Fonctionnalités principales
- Parcourir la liste des logiciels proposés (accueil).
- Afficher le détail d’un logiciel.
- Authentification basique (inscription, connexion, déconnexion).
- Outils de modération (publier/dépublier, bannir/débannir) pour les rôles adéquats.

Remarque : L’application est encore partiellement incomplète et certaines fonctionnalités peuvent être non finalisées.


## Architecture du projet
Arborescence condensée :

- app/
  - public/
    - index.php (point d’entrée HTTP)
    - css/, js/, img/, fonts/ (actifs front)
  - config/
    - config.php, paths.php (constantes), routes.php (définition des routes), databases.php (PDO)
  - src/
    - Core/ (noyau MVC : Application, Router, Request, Response, ViewBuilder, etc.)
    - Controllers/ (AccountsController, PostsController, SupportsController, ErrorsController)
    - Models/ (PostModel, AccountModel, GradeModel)
    - Views/ (Vues PHP pour comptes, posts, supports et erreurs; layouts)
    - Utils/ (Logger, SessionManager, helpers)
  - var/log/
    - messages.log (journal applicatif)
- deployment/
  - frankenphp/ (Dockerfile PHP)
  - database/ (Dockerfile MariaDB + SQL d’initialisation)
  - default.env (variables d’environnement par défaut)
- compose.yaml (stack Docker)
- README.md (ce fichier)

Points clés techniques :
- Autoload « maison » des classes de l’espace de noms App\ vers app/src.
- Gestion d’erreurs centralisée dans app/public/index.php ; affichage des vues d’erreur.
- Logger applicatif écrivant dans app/var/log/messages.log.
- Routes déclaratives dans app/config/routes.php (regex sur chemin, requête, méthode, rôle, contrôleur, action et nature Ajax/non-Ajax).


## Prérequis
- Docker et Docker Compose (v2) installés.
- OS compatible (Linux, macOS, Windows avec WSL2 recommandé).


## Démarrage rapide (Docker)
1: Cloner le dépôt :
- git clone <url-du-depot>
- cd ancres-logicielles

2: Lancer la stack :
- docker compose up --build --wait --remove-orphans

3 : Accéder à l’application :
- https://127.0.0.1:8443/

Le service « appserver » expose un serveur FrankenPHP en HTTPS sur le port 8443 (bouclé sur 127.0.0.1). Un volume monte le dossier app/ dans le conteneur pour faciliter le développement.

Arrêt et nettoyage du volume database (ATTENTION, supprime les données) :
- docker compose down -v --remove-orphans

## Configuration (variables d’environnement)
Les valeurs par défaut sont définies dans deployment/default.env et injectées dans les services Docker. Variables principales :
- DATABASE_DRIVER: mysql (par défaut)
- MARIADB_HOST: database (nom du service Docker)
- MARIADB_DATABASE: ancres-logicielles
- MARIADB_USER: al
- MARIADB_PASSWORD: al-password
- MARIADB_ALLOW_EMPTY_ROOT_PASSWORD: 1 (pour l’environnement local)

Vous pouvez dupliquer default.env et adapter les valeurs selon vos besoins (ne pas commiter des secrets en clair).


## Base de données (initialisation)
- Le service « database » est basé sur l’image MariaDB 12.
- Un fichier SQL d’initialisation est copié au build : deployment/database/docker-entrypoint-initdb.d/ancres-logicielles.sql.
- Au premier démarrage (volume vide), MariaDB initialisera la base avec ce script.

Changer ou rejouer l’initialisation :
- Supprimer le volume pour forcer une réinitialisation : docker compose down -v && docker compose up --build


## Développement
Entrée applicative :
- app/public/index.php configure l’autoload, installe des handlers d’erreurs et lance App\Core\Application.

Logs :
- Les erreurs/exceptions sont journalisées via App\Utils\Logger dans app/var/log/messages.log.

Routes (extraits majeurs depuis app/config/routes.php) :
- GET / ou /posts/indexSoftwares → PostsController::indexSoftwares (accueil)
- GET /posts/showSoftware?id=ID → PostsController::showSoftware
- GET|POST /posts/addSoftware (rôles: moderator|admin)
- POST (Ajax) /posts/{publish|unpublish|ban|unban|updatePostboxModTool|updateSoftwareStatus} (rôles: moderator|admin)
- GET /supports/about, /supports/policies
- GET|POST /accounts/signup (guest), /accounts/login (guest)
- GET|POST /accounts/logout (registered|moderator|admin)

Vues et layouts :
- app/src/Views/... regroupe les pages, layouts de navigation, notifications et statuts des posts.

Modèles :
- app/src/Models/ gère l’accès aux données (PDO MySQL) conformément aux paramètres de app/config/databases.php.

Front-end :
- app/public/css/style.scss (SASS) → style.css. JavaScript dans app/public/js/main.js. Polices et icônes sous app/public/.

Certificats/HTTPS local :
- FrankenPHP latest gère le HTTPS interne. L’URL est exposée sur https://127.0.0.1:8443/. Selon votre navigateur, vous devrez peut-être accepter un certificat non approuvé en local.


## Rôles, statut invité et comptes de test
- Statut invité (guest) : toute personne qui visite le site sans être authentifiée dispose du statut « guest ».
  - Accès en lecture seule aux pages publiques (accueil, fiches logiciels, pages "À propos" et "Politiques").
  - Les actions d’écriture (insérer, publier, modérer, etc.) nécessitent une authentification et les rôles appropriés.
- Comptes de test (environnement local) :
  - Utilisateur régulier (registered) :
    - Identifiant/pseudo : regularuser
    - Mot de passe : regularuserPasswd0
  - Administrateur (admin) :
    - Identifiant/pseudo : admin
    - Mot de passe : adminPasswd0
- Connexion et déconnexion :
  - Connexion : https://localhost:8443/accounts/login (ou via le bouton « Connexion »)
  - Déconnexion : https://localhost:8443/accounts/logout
- Remarque : ces comptes de démonstration sont provisionnés via le script d’initialisation de la base (deployment/database/docker-entrypoint-initdb.d/ancres-logicielles.sql).

## Limitations actuelles et pistes d’amélioration
- Fonctionnalités incomplètes : certaines parties CRUD et flux d’authentification/modération peuvent être partiellement implémentées.
- Base de données de démonstration : les données de test sont limitées au script d’initialisation fourni.
- Sécurité à renforcer pour une mise en production (gestion des secrets, configuration TLS, en-têtes HTTP, validation/filtrage).
- Tests automatisés absents : ajouter des tests unitaires/fonctionnels (PHPUnit) et un workflow CI.
- Gestion des requêtes Ajax en erreur : TODO mentionné dans index.php pour mieux gérer les réponses JSON en cas d’exception/erreur.
- Documentation développeur à enrichir (contributions, conventions de code, roadmap détaillée).

Pistes futures :
- Pagination/filtrage avancés pour la liste des logiciels.
- Système de votes/commentaires.
- Rôles et permissions plus granulaires.
- API REST/JSON pour intégrations tierces.


## Licence
Code : MIT License © 2025 jacques jdevelopquest — voir le fichier LICENSE.
Documentation : CC BY 4.0 © 2025 jacques jdevelopquest — https://creativecommons.org/licenses/by/4.0/
Éléments tiers (icônes, polices, images, etc.) : conservent leurs licences respectives — voir THIRD_PARTY.md.
