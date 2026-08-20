# Prompt de départ pour Claude

## Comportement de claude
- Claude parle et répond et produit des contenu en français impeccable.
- Claude est très rigoureux et précis, jamais verbeux ni lyrique.

## Langues
- Le thème et sa documentation doivent être en français.
- Les contenus doivent être en français.
- ATTENTION: le code lui meme et ses commentaires sont toujours en anglais.

## Code
- Claude produit du code en anglais US d’excellente qualité, lisible et maintenable, respectant les contraintes de linting et de style.
- Claude maintient une structure/organisation du code et des fichiers optimale.
- Claude produit du code idiomatique de HTML5, CSS3, JavaScript E6+, PHP 8+, WordPress, Tailwind CSS 4, respectant les meilleurs pratiques et styles guides de ces communautés.
- Claude produit du code responsive et adapté à la lecture sur mobile.
- Claude produit du code optimisé pour le référencement naturel, conforme aux normes SEO.
- Claude produit du code optimisé pour la vitesse de chargement et la performance, conforme aux normes Google PageSpeed Insights et Lighthouse.
- Claude produit du code optimisé pour l'accessibilité, conforme aux normes WCAG 2.1.
- Claude produit du code optimisé pour la sécurité, conforme aux normes OWASP.
- Claude réutilise les variables de couleur, typo, de breakpoints du thème sans redefinir de valeurs dans le css.


## Context global du projet
- Le projet est un thème wordpress pour le site internet de l'école primaire Saint-Joseph (st-jo) à la Bouëxière en France, basé sur le projet _tw.
- Le thème est toujours adapté à un public familial, enfants inclus.
- Le thème est simple à utiliser, avec un design moderne et minimaliste.
- Le design system du thème est fortement variabilisé, basé sur `theme/theme.json` et `tailwind/tailwind-theme.css`


## Niveau d'exigence

Travail de qualité professionnelle sur un site en production, consulté par les familles d'une
école. Concrètement :

- **On ne livre pas ce qu'on n'a pas vu tourner.** Une modification se constate sur le clone
  local avant d'être proposée. « Ça devrait marcher » n'est pas un état livrable.
- **On dit ce qui a été vérifié et ce qui ne l'a pas été.** Un test non lancé se signale, un
  point resté incertain se nomme.
- **On corrige la cause, pas le symptôme.** Un contournement se documente comme tel, avec sa
  raison et ce qu'il faudrait faire à la place.
- Le code respecte déjà les exigences listées plus haut (accessibilité, SEO, performance,
  sécurité, responsive) : elles ne sont pas des objectifs lointains, ce sont les conditions
  d'acceptation.

## Les trois dépôts, et ce qui va où

| Dépôt | Visibilité | Ce qu'il porte | Ce qu'on n'y met **jamais** |
|---|---|---|---|
| `wordpress-theme-st-jo` | **public** | Le thème `st-jo` seul : PHP, Tailwind, motifs de blocs, workflows de déploiement. | Quoi que ce soit venant de `site/www` ou de la base — le dépôt est public. |
| `st-jo-ops` | privé | Le clone iso-production : fichiers exacts du serveur, dump de la base, scripts, harnais de tests, sauvegardes. | Le thème : il est monté en volume depuis son dépôt, jamais copié. |
| `st-jo-backups` | privé | La part irremplaçable de chaque sauvegarde : base, médias, thème en ligne, `wp-config.php`. Un commit et une étiquette par prise. | Le cœur WordPress, les thèmes par défaut, les extensions : paquets publics, retéléchargeables aux versions du manifeste. |

Les trois sont clonés côte à côte dans le **même dossier parent**. Les scripts s'appuient sur
cette disposition (`THEME_REPO=../wordpress-theme-st-jo`, `BACKUPS_REPO=../st-jo-backups`).

```
github-ltruchot/
├── wordpress-theme-st-jo/   public   ← le thème
├── st-jo-ops/               privé    ← l'environnement, les scripts, la base
└── st-jo-backups/           privé    ← les sauvegardes hors-machine
```

**Sens de circulation.** Le thème se développe dans son dépôt et se voit tourner dans le clone
de `st-jo-ops`. `st-jo-ops` aspire la production et pousse vers `st-jo-backups`. Rien ne remonte
jamais d'un dépôt privé vers le dépôt public.

**Tu es ici : `wordpress-theme-st-jo`.**

## Environnement de travail

Le clone local iso-production vit dans le dépôt privé **`st-jo-ops`**, à côté de celui-ci : les
fichiers exacts du serveur, le contenu réel du site, la même version de PHP et le même moteur SQL.

```bash
(cd ../st-jo-ops && docker compose up -d)   # le site, sur http://localhost:8210
npm run watch                               # ici, dans le dépôt du thème
```

Les parenthèses ne sont pas décoratives : sans elles, le `cd` persiste et `npm run watch`
s'exécuterait dans `st-jo-ops`, qui n'a pas ce script.

Le thème est monté en direct dans le clone : on modifie ici, on rafraîchit là, on voit le résultat
sur les vraies pages. Avant de proposer un changement :

```bash
cd ../st-jo-ops/e2e && npm run check:local
```

Les références visuelles sont capturées sur la production. Un écart signifie qu'on a changé autre
chose que ce qu'on croyait — c'est précisément ce qu'on veut apprendre avant le déploiement, pas
après.

## Mise en production

- **`main` est protégé : jamais de push direct.** Une Pull Request, toujours.
- Le déploiement part au merge. Il sauvegarde le thème en ligne, affiche ce qu'il va changer,
  vérifie le site ensuite, et **restaure automatiquement** la sauvegarde en cas d'échec.
- Avant un changement notable, dans `st-jo-ops` : `bash scripts/backup-live.sh`, puis
  `bash scripts/restore-check.sh <horodatage>`. **Une sauvegarde qu'on n'a jamais restaurée n'est
  pas une sauvegarde** — le script la remonte dans une pile jetable et le prouve.
  L'instantané est horodaté, complet et autonome (base + racine complète du serveur, cœur,
  extensions, thème en ligne, médias), rangé hors des dépôts dans `~/st-jo-backups/`. Procédure
  détaillée : `RUNBOOK.md` §5 de `st-jo-ops`.
- Retour arrière : le workflow **Rollback Production**, ou `scripts/rollback-theme.sh`.
- **Rien n'est écrit sur le serveur** en dehors des scripts de déploiement — pas de fichier
  déposé, même temporaire, même auto-effaçant.
- Les mots de passe sont saisis par Loïc. Aucun secret n'est écrit dans le dépôt, ni affiché.

## Étanchéité

Aucun fichier de ce dépôt — code, commentaire, documentation, message de commit — ne fait
référence à un autre projet, client ou site, **pas même par comparaison ou par négation**. Ce qui
est écrit ici se tient seul.

## Skills du dépôt

Deux skills portent la connaissance du thème. Elles se chargent à la demande — inutile de les
lire d'avance, mais il faut savoir qu'elles existent :

| Skill | Quand |
|---|---|
| `st-jo-design-system` | écrire ou modifier du CSS : jetons, breakpoints, choix du fichier, parité éditeur, motifs de blocs |
| `st-jo-build` | construire, linter, déployer : scripts, build de production, exigences de la CI, retour arrière |

Les autres skills de `.claude/skills/` sont des références WordPress amont, laissées telles
quelles.

## Fichier à lire avant de démarrer
- `theme/theme.json` et `tailwind/tailwind-theme.css`
- `README.md`
- `package.json`
- `composer.json`
- `docs/DEPLOYMENT.md`
- le `README.md` et le `LEADS.md` de `st-jo-ops` (carnet de bord : faits vérifiés, pièges rencontrés)
