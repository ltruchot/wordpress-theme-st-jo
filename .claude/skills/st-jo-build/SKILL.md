---
name: st-jo-build
description: À utiliser pour construire, linter, tester ou déployer le thème st-jo — scripts npm et composer, différence entre build de développement et de production, fichiers générés non versionnés, checks obligatoires en CI, mise en production par rsync et retour arrière. À consulter avant de proposer un changement livrable.
---

# Chaîne de build et mise en production du thème st-jo

## Les commandes

| Commande | Ce qu'elle fait |
|---|---|
| `npm install` | dépendances de build (Tailwind, esbuild, ESLint, Prettier) |
| `npm run watch` | reconstruit en continu — **la commande de travail** |
| `npm run dev` | un build de développement, non minifié |
| `npm run production` | le build livrable, minifié et purgé |
| `npm run lint` | ESLint + Prettier |
| `npm run lint-fix` | corrige ce qui peut l'être |
| `composer install` | outillage PHP (PHP_CodeSniffer) |
| `composer run php:lint` | PHP_CodeSniffer, règles WordPress |
| `composer run php:lint:autofix` | `phpcbf` |

`dev`, `watch`, `production` et `lint` sont des `run-p` : ils lancent leurs sous-tâches **en
parallèle**. Les trois commandes de build produisent chacune quatre sorties — `style.css`,
`style-editor.css`, `style-editor-extra.css` et les bundles JavaScript.

`bundle`, lui, est un `run-s` : `production` puis `zip`, dans cet ordre, parce que l'archive a
besoin du build terminé.

`npm run bundle` (→ archive zip) existe, hérité de `_tw`, mais **ce n'est pas ainsi que ce site
est déployé**. Voir plus bas.

## Le piège : ne jamais valider sur un build de développement

`npm run dev` et `npm run production` ne produisent **pas** le même rendu. La sortie non
minifiée diverge à l'affichage — écart de hauteur constaté sur des pages réelles, suffisant
pour faire échouer une comparaison visuelle au pixel.

Conséquence pratique : **avant toute capture de référence, tout test visuel ou toute
comparaison avec la production, lancer `npm run production`.** Un écart inexpliqué entre le
clone local et la production commence presque toujours par cette vérification.

Le `doctor.sh` du dépôt d'exploitation contrôle ce point ; en cas de doute, `theme/style.css`
doit être minifié (quelques lignes très longues, pas plusieurs milliers de lignes lisibles).

## Les fichiers générés ne sont pas versionnés

`theme/style.css`, `theme/style-editor.css`, `theme/style-editor-extra.css` et
`theme/js/*.min.js` sont dans `.gitignore`.

**Un `git clone` seul ne donne donc pas un thème valide** : WordPress lit l'en-tête
`Theme Name:` dans `style.css`, qui n'existe pas tant qu'on n'a pas construit. Après un clone
ou un changement de branche : `npm install && npm run production`.

Ne jamais éditer ces fichiers à la main — ils sont écrasés au build suivant. On modifie
`tailwind/` et `javascript/`.

## JavaScript

`javascript/script.js` et `javascript/block-editor.js` sont bundlés par esbuild vers
`theme/js/*.min.js` (cible `esnext`). Le bundle a une portée de module : ce qui doit être
global s'attache explicitement à `window`.

## Ce que la CI exige

Deux vérifications sont **obligatoires** sur les Pull Requests, et bloquent le merge :

- **`javascript-css`** — ESLint, puis un build complet. Stylelint et Prettier tournent en mode
  informatif (ils ne bloquent pas).
- **`php`** — contrôle de syntaxe PHP. PHP_CodeSniffer est informatif.

Lancer `npm run lint` et `npm run production` en local avant de pousser évite l'aller-retour.

Attention en lisant la sortie de ces commandes : `run-p` entrelace plusieurs processus et un
échec peut se trouver au milieu du flot. Ne pas conclure « rien à signaler » sur une sortie
tronquée — vérifier le code de retour.

## Mise en production

- **`main` est protégé : aucun push direct.** Un hook `pre-push` versionné dans `.githooks/`
  le refuse aussi en local (`git config core.hooksPath .githooks` pour l'installer). Une Pull
  Request, toujours.
- Le déploiement part **au merge sur `main`** : `.github/workflows/deploy-rsync.yml` installe
  les dépendances, lance `npm run production`, vérifie que `style.css` porte bien son en-tête,
  **sauvegarde le thème en ligne**, affiche un `rsync --dry-run` lisible, envoie, puis exécute
  un test de fumée. **En cas d'échec, il restaure automatiquement la sauvegarde.**
- Seul le dossier `theme/` part sur le serveur.
- Retour arrière : le workflow manuel **Rollback Production** (`rollback.yml`), qui prend
  l'horodatage d'une sauvegarde.
- **Rien n'est écrit sur le serveur** en dehors de ces workflows.

## Validation avant de proposer un changement

Le thème est monté en direct dans le clone iso-production du dépôt d'exploitation voisin. La
boucle complète est décrite dans `CLAUDE.md` : le site tourne d'un côté, `npm run watch` de
l'autre, et le harnais de non-régression visuelle compare le clone à la production.

On ne livre pas ce qu'on n'a pas vu tourner.

## Dépannage

**`npm run watch` ne reconstruit pas.** Si le dépôt est posé sur un disque monté (sous WSL, un
chemin en `/mnt/c/…`), la surveillance de fichiers de Tailwind ne reçoit pas les notifications.
Remplacer `--watch` par `--poll` dans les scripts `watch:*`, ou — bien mieux — travailler dans
le système de fichiers Linux.

**Erreurs à `npm install`.** La CI utilise Node 22 ; s'aligner dessus.

**Une classe utilitaire ajoutée dans l'éditeur de blocs ne s'applique pas.** Tailwind ne génère
que les classes qu'il trouve dans le dépôt. Une classe saisie dans wp-admin n'existe donc pas
dans `style.css`. Il faut soit la faire apparaître dans le thème, soit la déclarer explicitement
via `@source inline(…)` dans `tailwind/tailwind.css`, où quelques classes le sont déjà.

## Documentation amont

Le thème dérive de `_tw`. La documentation générique du projet amont est intégralement liée
depuis `README.md`, section « Full Documentation ».
