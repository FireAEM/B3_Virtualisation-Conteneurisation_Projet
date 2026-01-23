# Task App • PHP + MySQL Dockerisé

---

## Présentation

**Task App** est une application CRUD simple permettant de gérer des tâches.  
Elle est entièrement **dockerisée** afin d’offrir un environnement reproductible, isolé et facile à lancer, avec un objectif clair : **développer et tester l’application en une seule commande**.

L’environnement inclut :
-   un serveur **PHP 8.2 + Apache**
-   une base de données **MySQL 8**
-   un accès optionnel via **phpMyAdmin**
-   un workflow **dev/prod** propre grâce à Docker Compose et un Makefile

---

## Table des matières

-   [Prérequis](#prérequis)
-   [Installation et configuration](#installation-et-configuration)
-   [Scripts disponibles](#scripts-disponibles)
-   [Troubleshooting rapide](#troubleshooting-rapide)
-   [Sécurité et bonnes pratiques](#sécurité-et-bonnes-pratiques)
-   [Structure du projet](#structure-du-projet)

---

## Prérequis

-   **Docker Desktop** (Windows 11 + WSL2)  
    ou **Docker Engine + Docker Compose v2**
-   **WSL Ubuntu** recommandé pour exécuter les commandes sous Windows
-   Terminal Bash (WSL, macOS, Linux)

---

## Installation et configuration

1.  **Cloner le projet**
```bash
git clone https://github.com/FireAEM/B3_Virtualisation-Conteneurisation_Projet.git
cd B3_Virtualisation-Conteneurisation_Projet
```

2.  **Créer le fichier d’environnement**
```bash
cp .env.example .env
```

Adapter les variables selon vos besoins (ports, identifiants MySQL…).

3.  **Rendre les scripts exécutables (une seule fois)**
```bash
chmod +x docker/wait-for-it.sh
chmod +x scripts/init-db.sh scripts/reset.sh
```

4.  **Installer Make**
```bash
sudo apt update
sudo apt install make
```

5.  **Lancer l’environnement de développement**
```bash
make dev
```
ou :
```bash
docker compose -f docker-compose.yml -f docker-compose.dev.yml up -d --build
```

6.  **Accéder à l’application**
-   Application : http://localhost:8080
-   phpMyAdmin (optionnel) : http://localhost:8081

---

## Scripts disponibles

### Via Makefile
-   `make up` → démarre l’environnement (`docker compose up -d`)
-   `make down` → arrête les conteneurs
-   `make logs` → affiche les logs en continu
-   `make build` → reconstruit les images
-   `make reset` → reset complet (arrêt + suppression volumes + rebuild)
-   `make dev` → lance l’environnement de développement
-   `make prod` → lance l’environnement de production (image buildée, sans bind mount)

### Scripts Bash
-   `scripts/init-db.sh` → démarre uniquement la DB et exécute `init.sql` si la base est vide
-   `scripts/reset.sh` → reset complet + rebuild

----------

## Troubleshooting rapide

-   Voir l’état des services :
```bash
docker compose ps
```

-   Logs MySQL :
```bash
docker compose logs -f db
```

-   Si la DB n’est pas prête :  
    Vérifier `.env`, relancer uniquement MySQL :

```bash
docker compose up -d db
docker compose logs db
```

-   Si un port est déjà utilisé :  
    Modifier `APP_PORT` ou `PMA_PORT` dans `.env`

---

## Sécurité et bonnes pratiques

-   **Ne jamais committer `.env`** → ajouter à `.gitignore`
-   En production :
    -   éviter les mots de passe par défaut
    -   utiliser **Docker secrets**, Vault ou un gestionnaire de secrets
-   Sauvegarder régulièrement le volume MySQL (`db_data` ou `db_data_prod`)
-   Ne pas utiliser de bind mount en production (déjà géré dans `docker-compose.prod.yml`)

---

## Structure du projet

```
B3_Virtualisation-Conteneurisation_Projet/
├── docker/
│   └── wait-for-it.sh                  # script d’attente DB
├── mysql-init/
│   └── init.sql                        # initialisation automatique MySQL
├── scripts/
│   ├── init-db.sh                      # initialisation DB seule
│   └── reset.sh                        # reset complet + rebuild
├── src/                                # code PHP de l'application
├── .dockerignore                       # fichiers ignorés lors du build
├── .env.example                        # variables d’environnement
├── docker-compose.yml                  # configuration principale
├── docker-compose.dev.yml              # override développement
├── docker-compose.prod.yml             # override production
├── Dockerfile                          # image PHP/Apache
├── Makefile                            # raccourcis de commandes
└── README.md                           # documentation du projet
```