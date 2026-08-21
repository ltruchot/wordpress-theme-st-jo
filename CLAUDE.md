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

### Où l'on a le droit d'écrire, et où l'on n'a pas le droit

**Le thème nous appartient.** À l'intérieur de `theme/`, `tailwind/` et `javascript/`, on fait ce
qu'on veut. Mais on le fait **autonome et auto-portant** : le thème doit tenir seul, sans rien
supposer d'un autre thème ni d'une extension. Une extension s'active, se désactive, change de
version, change de balisage. Un style ou un comportement qui dépend de ce qu'elle produit
aujourd'hui cassera le jour où elle bougera, et personne ne fera le lien.

**On ne corrige jamais dans le cœur WordPress ni dans une extension.** Leurs fichiers sont
**remplacés à la prochaine mise à jour** : une retouche y disparaît sans prévenir, sans trace, et
le défaut revient. Quand le problème vient de là, dans l'ordre :

1. le corriger **depuis le thème** — un filtre, un `add_action`, `wp_dequeue_style`, une règle
   CSS qui prend le dessus ;
2. sinon, le contourner dans un **`mu-plugin`**, qui est notre code et survit aux mises à jour ;
3. sinon, le **signaler en amont** et consigner le contournement avec sa raison.

Ce qui vaut aussi pour les données : une valeur figée dans le contenu (une URL absolue, par
exemple) se corrige dans l'éditeur, pas en la rattrapant en CSS.


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

## Pièges d'outillage, déjà payés

Cette section grandit exprès. Chaque friction rencontrée s'y écrit le jour même, avec ce qu'il faut
faire **et** ce qu'il ne faut pas faire — sinon elle se repaie au complet la fois suivante.

**`git` obéit au dossier courant, pas à l'intention.** Trois dépôts vivent côte à côte, et un `cd`
posé plus tôt dans la commande suffit à faire remiser, basculer ou dépiler dans le mauvais. Le
symptôme est déroutant : « No stash entries found » alors qu'on vient de remiser. La parade est
d'écrire le dépôt : `git -C ../wordpress-theme-st-jo status`, jamais `cd … && git …`.

**Une sortie tronquée ne prouve rien.** `| tail -2`, `| head -3`, une chaîne de `&&` : tout cela
cache les échecs au milieu du flot, et `run-p` entrelace en plus plusieurs processus. C'est ainsi
qu'un « ESLint : rien à signaler » a été annoncé alors que vingt erreurs sortaient. On conclut sur
le **code de retour**, pas sur ce qu'on a sous les yeux :

```bash
npm run lint > /tmp/lint.txt 2>&1; echo "code de retour : $?"
```

Corollaire pour les scripts : sous `set -euo pipefail`, une commande dont le code non nul est
normal — `diff` qui trouve une différence, `grep -c` qui compte zéro — met fin au script en
silence. Mais un `|| true` sec corrige le symptôme en avalant tout le reste : `diff` répond **2**
quand il ne peut pas comparer du tout, et un résultat vide présenté comme une comparaison réussie
est pire que pas de comparaison. On accepte les codes attendus, on s'arrête sur les autres :

```bash
resultat=$(diff a b) && code=0 || code=$?
[ "$code" -le 1 ] || die "Comparaison impossible."
```

**Le shell est `zsh`, et `zsh` ne découpe pas une variable non quotée.** En `bash`,
`set -- $spec` éclate `"a b"` en deux paramètres ; en `zsh`, le paramètre reste `a b` d'un seul
tenant, et la boucle traite un dépôt nommé « ltruchot/depot 31 ». Aucun message d'erreur, juste un
résultat absurde. Même famille : `${PIPESTATUS[0]}` est du `bash` — `zsh` écrit `$pipestatus[1]`, et
la version `bash` s'affiche vide sans rien signaler. La parade n'est pas de mémoriser les écarts,
c'est de **passer les valeurs en paramètres explicites d'une fonction** plutôt que de compter sur un
découpage :

```bash
verifier() { depot="$1"; pr="$2"; ...; }   # et non : for r in "a 1" "b 2"; do set -- $r
```

**Un lanceur de tests vise la production par défaut.** `playwright.config.ts` retombe sur
`https://ecole.st-joseph.fr` quand `BASE_URL` est absent. Lancer `npx playwright test` à la main
mesure donc **le site en ligne** en croyant mesurer le clone — sept échecs qu'on s'apprête à
attribuer à sa propre modification. On passe toujours par les scripts npm, qui portent la cible dans
leur nom : `npm run check:local`, `check:seo`, `check:a11y`. Et quand un chiffre surprend, la
première question est **« sur quoi ai-je mesuré ? »**.

**On ne laisse rien derrière soi, et on n'écrase pas ce qui servira.** Un outil qui écrit des
fichiers en écrit *encore* au passage suivant. Lighthouse ajoute ses rapports à côté des anciens :
relire le dossier revient alors à mélanger deux états du site et à annoncer un chiffre qui n'a été
vrai ni de l'un ni de l'autre — c'est ainsi qu'une page notée 100 a été relue à 89. Un lanceur mal
configuré fait pire : quarante-deux dossiers `C:\Users\...` sont apparus dans le dépôt en une
commande.

Deux gestes, pas un. **Archiver** ce qui a une valeur de comparaison — un avant et un après, c'est
l'essentiel de ce à quoi servent des mesures — sous une clé horodatée, avec une profondeur bornée
pour que personne n'ait à ranger plus tard. Puis **repartir propre**, pour que le dossier de travail
décrive exactement une exécution, la dernière. Une commande qui produit des fichiers dit dans son
en-tête où ils vont et ce qu'elle fait des précédents.

**Un garde-fou qui simplifie l'environnement finit par cacher ce qu'on cherche.** Le clone forçait
`blog_public` à 0 pour n'être jamais indexé — au passage, il coupait les sitemaps par un chemin que
la production ne prend pas, et le défaut qu'on cherchait était invisible en local. Quand une
vérification s'avère impossible sur le clone, la première question est **« qu'est-ce qui, chez nous,
rend ça impossible ? »**, pas « comment fait-on sans ».

**Un rapport d'exploration n'est pas une mesure.** Trois constats sévères de l'audit SEO se sont
révélés faux à la vérification : le `h1` prétendument absent de toutes les pages (il y en a
exactement un partout), le favicon prétendument non référencé (`site_icon` est réglé et WordPress
émet les balises), et une correction de titres annoncée comme neutre au pixel alors que les tailles
sont pilotées par le nom de l'élément. On vérifie avant de citer, y compris — surtout — ce qui vient
de soi-même.

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
- **Ouvrir la PR n'est pas la finir.** Une PR ouverte appelle trois gestes, dans cet ordre, et
  aucun ne se saute :
  1. **attendre la review de qodo** — elle met quelques minutes à venir, et passer outre revient à
     décider qu'elle ne sert à rien ;
  2. **repasser dessus avec la skill `qodo-pr-review` chargée**, pas de mémoire. La skill décrit
     une procédure — un sous-agent par retour, un commit atomique par correction, `/agentic_review`
     pour relancer — et la suivre de tête, c'est en oublier la moitié. C'est déjà arrivé ;
  3. **traiter chaque retour** : corrigé, ou refusé avec sa raison écrite dans le fil, puis résolu.
     La protection de `main` l'exige de toute façon.

  Puis **une seule** `/agentic_review`, et on s'arrête là. La nouvelle salve fait souvent
  apparaître d'autres points : c'est normal, et c'est Loïc qui décide d'une passe de plus. Enchaîner
  tout seul, c'est boucler sur un bot jusqu'à épuisement du sujet ou du budget.
- **Le corps d'une PR dit aussi ce qui n'a pas été vérifié.** Lister ses contrôles est facile ;
  nommer ce qu'on n'a pas pu atteindre l'est moins, et c'est ce qui a de la valeur. Un rendu
  d'éditeur qui demande une session authentifiée, un navigateur qu'on n'a pas, un cas qu'on n'a pas
  su reproduire : ça s'écrit, et ça indique à qui revient la vérification.
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

Quatre skills portent la connaissance du thème et du site. Elles se chargent à la demande —
inutile de les lire d'avance, mais il faut savoir qu'elles existent :

| Skill | Quand |
|---|---|
| `st-jo-design-system` | écrire ou modifier du CSS : jetons, breakpoints, choix du fichier, parité éditeur, motifs de blocs |
| `st-jo-build` | construire, linter, déployer : scripts, build de production, exigences de la CI, retour arrière |
| `st-jo-actualites` | publier des actualités à partir d'un e-mail : dates du module d'accueil, détail sur la page Actualités, rotation vers les archives |
| `st-jo-seo` | référencement, données structurées, accessibilité, mesure : sitemap, titres, `<head>`, seuils Lighthouse et axe |

Les autres skills de `.claude/skills/` sont des références WordPress amont, laissées telles
quelles.

## Fichier à lire avant de démarrer
- `theme/theme.json` et `tailwind/tailwind-theme.css`
- `README.md`
- `package.json`
- `composer.json`
- `docs/DEPLOYMENT.md`
- le `README.md` et le `LEADS.md` de `st-jo-ops` (carnet de bord : faits vérifiés, pièges rencontrés)
