# Déploiement

Le thème est déployé sur OVH par GitHub Actions. Trois workflows, dans
`.github/workflows/` :

| Workflow | Déclenchement | Rôle |
|---|---|---|
| **Deploy to Production** | push sur `main`, ou à la main | Construit, sauvegarde, déploie, vérifie |
| **Rollback Production** | à la main | Restaure une sauvegarde du serveur |
| **Lint** | sur chaque Pull Request | ESLint et build (bloquants), Stylelint / Prettier / PHPCS (informatifs) |

## Ce que fait un déploiement

L'ordre est celui du workflow, et il n'est pas négociable :

1. **Construction** du thème (`npm run production`), puis vérification que
   `theme/style.css` existe et porte bien son en-tête `Theme Name`. Ce fichier est généré et
   gitignoré : sans lui, WordPress ne reconnaît plus le thème.
2. **Sauvegarde** du thème en ligne, sur le serveur, dans `$BACKUP_DIR/<horodatage>`.
   L'horodatage est écrit dans le résumé du run.
3. **Aperçu** : un `rsync --dry-run --itemize-changes` affiche exactement ce qui va changer,
   avant que quoi que ce soit ne parte. C'est visible dans le résumé du run.
4. **Envoi** par `rsync --delete`, puis ajustement des permissions (755 / 644).
5. **Test de fumée** : la page d'accueil doit répondre 200, charger le thème `st-jo` et avoir un
   titre. Trois tentatives espacées de dix secondes.
6. **Retour arrière automatique** si l'une des étapes échoue : la sauvegarde prise à l'étape 2
   est restaurée, sans intervention.

Deux déploiements ne peuvent pas se chevaucher (`concurrency: production-deploy`), sans quoi
leurs `rsync` et leurs sauvegardes s'entremêleraient.

> Le serveur répond **403 aux User-Agent inconnus**. Toute vérification par `curl` doit donc
> envoyer un en-tête `User-Agent` de navigateur, sinon un site parfaitement sain paraît en panne.

## Revenir en arrière

Onglet **Actions** → **Rollback Production** → *Run workflow*.

- Champ laissé **vide** : le workflow liste les sauvegardes disponibles sur le serveur.
- Champ rempli avec un horodatage : cette version est restaurée, puis vérifiée.

La restauration s'exécute **entièrement sur le serveur** : rien n'est ré-uploadé, et la copie
temporaire est constituée avant d'être basculée en place — le dossier du thème n'est jamais vide,
même en cas de coupure.

En ligne de commande, l'équivalent se trouve dans le dépôt `st-jo-ops` :
`bash scripts/rollback-theme.sh`.

## Secrets GitHub

*Settings → Secrets and variables → Actions.*

| Secret | Description |
|---|---|
| `SSH_HOST` | Hôte SSH OVH, `ssh.cluster027.hosting.ovh.net` |
| `SSH_USER` | Utilisateur SSH, `stjoseu` |
| `SSH_PASSWORD` | Mot de passe SSH |
| `SSH_PORT` | Port SSH (facultatif, 22 par défaut) |
| `THEME_PATH` | `/homez.1929/stjoseu/www/wp-content/themes/st-jo` |
| `BACKUP_DIR` | `/homez.1929/stjoseu/themes-backup` |

### À faire : passer à une clé SSH

L'authentification par mot de passe impose `sshpass` et fait transiter le mot de passe dans
chaque commande. Une fois une clé publique déposée dans le manager OVH
(*Hébergements → FTP-SSH → Clés SSH*), remplacer `SSH_PASSWORD` par un secret `SSH_PRIVATE_KEY`
et retirer `sshpass` des workflows.

## Vérifier avant de déployer

Le vrai garde-fou n'est pas dans la CI : c'est de **voir le changement en local d'abord**. Le
dépôt `st-jo-ops` fait tourner un clone de la production, avec le contenu réel du site. Y lancer
`docker compose up -d`, puis `npm run watch` ici.
