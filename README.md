## PROJET DOCKER : Déploiement Laravel Multi-Serveurs

Ce projet utilise **Docker Compose** pour déployer une architecture conteneurisée complexe, comprenant deux serveurs web distincts, des services d'application PHP/Laravel et une base de données MySQL commune.

### Objectifs Principaux

  * **Architecture Double :** Lancer deux instances Nginx et deux instances PHP (FPM) distinctes, partageant une seule base de données MySQL.
  * **Conteneurisation PHP :** Créer l'image PHP (FPM) via un **Dockerfile custom**.
  * **Déploiement Laravel :** Exécuter automatiquement les commandes de démarrage de Laravel au premier lancement.
  * **Accès et Différenciation :** Accéder aux deux serveurs via le navigateur et afficher "Serveur 1" ou "Serveur 2" tout en conservant les fonctionnalités de connexion/inscription.

-----

### Commandes de Démarrage et Explication

La commande ci-dessous lance tous les services, configure les permissions (`UID`/`GID`) pour l'utilisateur hôte, et définit les identifiants pour le service de stockage d'objets MinIO (bonus).

```bash
UID=$(id -u) GID=$(id -g) \
AWS_ACCESS_KEY_ID=minioaccess AWS_SECRET_ACCESS_KEY=miniokey \
MINIO_ROOT_USER=minioaccess MINIO_ROOT_PASSWORD=miniokey \
docker compose up -d --build
```

#### Décomposition

| Élément | Rôle | Contexte |
| :--- | :--- | :--- |
| `UID=$(id -u) GID=$(id -g)` | **Permissions Hôtes** | Garantit que les fichiers générés dans les volumes appartiennent à l'utilisateur hôte (Bonus). |
| `AWS_...` / `MINIO_ROOT_...` | **Identifiants MinIO** | Définit les clés d'accès pour le service de stockage d'objets S3-compatible (Bonus : MinIO). |
| `docker compose up -d` | **Démarrage** | Lance les services définis dans `docker-compose.yml` en mode détaché. |
| `--build` | **Reconstruction** | Force la reconstruction des images à partir de leurs `Dockerfile`. |

