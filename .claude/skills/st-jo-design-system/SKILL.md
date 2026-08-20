---
name: st-jo-design-system
description: À utiliser pour écrire ou modifier du CSS dans le thème st-jo — couleurs, typographie, breakpoints, choix du fichier où poser une règle, parité avec l'éditeur de blocs, motifs de blocs. Donne la source de vérité des jetons de design et les pièges de l'architecture Tailwind du thème.
---

# Design system du thème st-jo

## Source de vérité : ne jamais écrire une valeur en dur

Une seule chaîne alimente tout le style du thème :

```
theme/theme.json                  les valeurs (palette, typo, largeurs)
  ↓  WordPress génère --wp--preset--color--primary, etc.
tailwind/tailwind-theme.css       les traduit en jetons Tailwind
  ↓
classes utilitaires : bg-primary, text-beige, font-heading, max-w-content
```

**Une couleur, une police, une largeur ou un breakpoint ne se réécrit jamais dans un fichier
CSS.** On ajoute la valeur dans `theme/theme.json`, on l'expose dans `tailwind-theme.css`, on
l'utilise par sa classe. Un `#CA4A28` écrit à la main est un bug : il ne suivra pas un
changement de palette et il n'existera pas dans l'éditeur de blocs.

Pour lire les valeurs courantes, aller les chercher dans `theme/theme.json` — elles ne sont
volontairement pas recopiées ici, une copie diverge.

## Ce que les noms ne disent pas

Deux jetons portent un nom trompeur, hérités de `_tw` :

- **`foreground` n'est pas la couleur du texte** : c'est le beige `#F9F7EF`, la couleur des
  blocs et cartes posés sur le fond.
- **`background` est le blanc**, pas le beige.

La couleur du texte courant est `black` (`#2D3039`, pas un noir pur).

Correspondance entre les noms de la maquette et les jetons :

| Maquette | Jeton | Classes |
|---|---|---|
| Terracotta | `primary` (+ échelle `primary-50` … `primary-900`) | `bg-primary`, `text-primary` |
| Jaune | `secondary` = `yellow` | `bg-secondary` |
| Violet | `tertiary` = `purple` | `bg-tertiary` |
| Beige | `beige` = `foreground` | `bg-beige` |

`secondary`/`yellow`, `tertiary`/`purple` et `beige`/`foreground` sont des paires de doublons
pointant sur la même valeur. Préférer le nom sémantique (`primary`, `secondary`) au nom de
couleur : il survit à un changement de charte.

## Breakpoints

Quatre paliers, définis dans `tailwind/tailwind-theme.css` :

| Palier | Largeur | Préfixe Tailwind |
|---|---|---|
| mobile | 0 – 719 px | *(aucun — c'est la base)* |
| tablette | ≥ 720 px | `sm:` |
| desktop | ≥ 960 px | `md:` |
| large | ≥ 1280 px | `lg:` |

**`xl:` et `2xl:` valent aussi 1280 px** : ce sont des alias de `lg:`, ils ne distinguent rien.
Ne pas les utiliser — un `xl:` dans le code laisse croire à un cinquième palier qui n'existe pas.

Le thème est **mobile-first** : on écrit le style mobile sans préfixe, puis on l'élargit.

Côté JavaScript, `javascript/breakpoints.js` lit les mêmes seuils via les variables
`--js-breakpoint-*` exposées dans `:root` par `tailwind-theme.css` — d'où `isMobile()`,
`getCurrentBreakpoint()`, `onBreakpointChange()`. Ajouter un palier impose donc **deux** gestes :
la variable `--breakpoint-*` dans `@theme`, et son miroir `--js-breakpoint-*` dans `:root`.
Sans le second, le JavaScript renvoie `NaN` en silence.

## Où poser une règle

```
tailwind/
├── tailwind-theme.css              jetons de design (@theme) — la porte d'entrée
├── partials/header.css             typographie, polices, en-tête WordPress  ─┐ partagés par
├── partials/footer.css             la liste des composants + utilitaires     ─┘ les 2 sorties
├── custom/base.css                 styles d'éléments nus (h1, a, body…)
├── custom/components/*.css         un fichier par composant
├── custom/utilities.css            utilitaires maison
└── custom/fonts.css                règles @font-face auto-hébergées
```

### Le piège : un composant n'est pas découvert tout seul

Créer `tailwind/custom/components/mon-bloc.css` ne suffit pas. Il faut **l'ajouter à la main**
à la liste d'`@import` de `tailwind/partials/footer.css`, sinon il n'est jamais compilé — et
l'absence est silencieuse, pas d'erreur au build.

### Corollaire utile : la parité éditeur est gratuite

`tailwind.css` (→ `style.css`, le site) et `tailwind-editor.css` (→ `style-editor.css`,
l'éditeur de blocs) importent tous les deux `partials/header.css` et `partials/footer.css`.
Un composant ajouté à cette liste s'affiche donc **automatiquement dans l'éditeur**.

Il est inutile — et nuisible — de dupliquer une règle sous `.editor-styles-wrapper` : c'est du
code mort qui divergera de l'original.

Pour du style destiné **uniquement** à l'éditeur, le fichier prévu est
`tailwind/tailwind-editor-extra.css` (→ `style-editor-extra.css`).

## Typographie

Deux familles, chargées depuis Google Fonts dans `theme/functions.php` :

- `--font-heading` → **Baloo Da 2**, pour les titres (`font-heading`)
- `--font-sans` → **Lato**, pour le texte courant (`font-sans`, appliqué par défaut)

Le corps de texte passe par **Tailwind Typography** : les classes `prose*` sont centralisées
dans la constante `ST_JO_TYPOGRAPHY_CLASSES` en tête de `theme/functions.php`, et injectées
d'un coup dans le site, l'éditeur de blocs et l'éditeur classique. Les surcharges fines vivent
dans `tailwind/tailwind-typography.config.js`.

## Contenu éditable sans écrire de code

Les **motifs de blocs** sont enregistrés dans `theme/inc/block-patterns.php`. C'est le bon
outil quand une rédactrice doit pouvoir poser un contenu récurrent (une actualité, un encadré)
depuis wp-admin sans toucher au thème : le motif décrit le balisage, le composant CSS le
style, et le contenu reste modifiable dans l'éditeur.

Poser un motif, c'est donc deux fichiers : le balisage dans `block-patterns.php`, le style dans
`custom/components/`, plus la ligne d'`@import` dans `partials/footer.css`.

## Accessibilité et responsive

Ce sont des conditions d'acceptation, pas des objectifs (voir `CLAUDE.md`) : contraste suffisant
sur le beige comme sur le terracotta, cibles tactiles confortables, pas de débordement
horizontal au palier mobile, et un ordre de titres (`h1` → `h2` → `h3`) qui reste logique.

## Maquettes

Les liens Figma (vue d'ensemble et vue développeur) sont dans `README.md`, section « Design ».

## Documentation amont

Le thème dérive de `_tw`. Pour les mécanismes génériques (Tailwind Typography, polices
personnalisées, style du HTML produit par WordPress ou une extension), la documentation amont
est intégralement liée depuis `README.md`, section « Full Documentation ».
