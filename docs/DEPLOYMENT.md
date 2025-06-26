# Guide de déploiement automatique

Ce guide explique comment configurer le déploiement automatique de votre thème WordPress sur OVH.

## Configuration des secrets GitHub

1. Allez dans votre repository GitHub
2. Settings → Secrets and variables → Actions
3. Ajoutez les secrets suivants :

| Secret | Description | Exemple |
|--------|-------------|---------|
| `SSH_HOST` | L'adresse IP ou domaine fourni par OVH | `123.45.67.89` ou `ssh.cluster123.hosting.ovh.net` |
| `SSH_USER` | Le nom d'utilisateur SSH fourni par OVH | `votreuser` |
| `SSH_PASSWORD` | Le mot de passe SSH fourni par OVH | `VotreMotDePasse` |
| `SSH_PORT` | Le port SSH (optionnel, 22 par défaut) | `22` |
| `THEME_PATH` | Le chemin complet vers le dossier du thème | `/home/user/www/wp-content/themes/st-jo` |
| `BACKUP_DIR` | Le chemin pour les sauvegardes | `/home/user/backups` |

## Utilisation

Le déploiement se fait automatiquement quand :
- Vous poussez sur la branche `main`
- Vous mergez une Pull Request vers `main`

Le workflow va :
1. Builder le thème (`npm run bundle`)
2. Uploader le fichier ZIP sur le serveur
3. Faire une sauvegarde du thème actuel
4. Remplacer le thème par la nouvelle version
5. Ajuster les permissions des fichiers

## Vérification

Pour vérifier que tout fonctionne, vous pouvez déclencher manuellement le workflow depuis l'onglet Actions de votre repository GitHub.

## Sécurité

- Les secrets GitHub sont chiffrés et ne sont jamais exposés dans les logs
- Assurez-vous que le dossier de backup existe sur le serveur
- Vérifiez que l'utilisateur SSH a les permissions nécessaires sur les dossiers