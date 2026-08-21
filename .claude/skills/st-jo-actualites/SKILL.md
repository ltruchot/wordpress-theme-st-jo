---
name: st-jo-actualites
description: À utiliser pour mettre à jour les actualités du site à partir d'e-mails de la direction, de l'APEL ou de l'OGEC — extraire les dates, remplir le module « Les prochaines dates à retenir » de l'accueil, rédiger le détail sur la page Actualités, et faire descendre les mois révolus dans les archives par trimestre. À consulter dès qu'on reçoit un pavé de texte à publier.
---

# Publier des actualités à partir d'un e-mail

On reçoit un e-mail — souvent long, mal structuré, mêlant dates, consignes et informations
permanentes. Il en sort **deux choses** :

| Où | Quoi |
|---|---|
| Module rouge de l'accueil, page 82 | les **dates à venir**, une ligne chacune |
| Page Actualités, page 97 | le **texte complet**, une actualité par sujet |

Le module est un **agenda** : il annonce ce qui arrive. La page est un **journal** : elle raconte,
et garde la mémoire. Les deux ne se rangent donc pas dans le même sens.

## 1. Sortir les dates de l'e-mail

Une entrée du module est une **date réelle reliée à un événement**. « Les listes seront envoyées
d'ici quelques semaines » n'en est pas une ; « porte ouverte le vendredi 28 août » en est une.

**Vérifier chaque jour de la semaine annoncé contre le calendrier réel** :

```bash
date -d 2026-08-28 +'%A %-d %B %Y'
```

Un e-mail écrit vite se trompe, et l'erreur se propage sur le site public. Si l'écart existe, le
**signaler** — ne jamais corriger en silence : c'est l'école qui sait ce qu'elle voulait dire.

**Écarter les dates passées.** Le module n'annonce que ce qui reste à venir. Comparer à la date du
jour, pas à un mois entier.

**Ne rien inventer.** Une date ambiguë (« début juillet », « courant septembre ») ne devient pas
une date précise. Elle va dans le texte de l'actualité, pas dans le module.

## 2. Le module de l'accueil

Groupé par mois, **du plus proche au plus lointain** — c'est un agenda.

Une ligne obéit à quatre règles : **un emoji** qui dit l'événement d'un coup d'œil, **pas de
déterminant en tête** (« Rentrée des classes », jamais « La rentrée des classes »), **pas de
ponctuation finale**, et **le jour avec la date**. L'emoji est suivi d'une **espace insécable**,
pour qu'il ne se retrouve jamais seul en fin de ligne.

Chaque mois se termine par son propre **« En savoir plus › » pointant vers l'ancre de ce mois** sur
la page Actualités.

```html
<!-- wp:paragraph -->
<p><strong>Septembre 2026:</strong></p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul class="wp-block-list"><!-- wp:list-item -->
<li>🎒&nbsp;Rentrée des classes – mardi 1er septembre</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li> <a href="…/actualites#septembre-2026" data-type="page" data-id="97">En savoir plus ›</a></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->
```

Le séparateur entre l'événement et la date est un tiret demi-cadratin `–`, pas un trait d'union.

## 3. La page Actualités

**Ordre antichronologique** : le mois le plus récent en haut. C'est un journal.

Structure, et elle ne se négocie pas — l'ordre des titres doit rester lisible pour un lecteur
d'écran :

```
h2   Septembre 2026            ancre : septembre-2026
       bloc « Actualité »      h3 titre · p.actualite-date · texte
       bloc « Actualité »
h2   Août 2026                 ancre : aout-2026
h2   Archives — 1er trimestre 2025
h3     Novembre 2025
         bloc « Actualité »    h4 titre
```

**Une actualité = un bloc groupe** portant `is-style-actualite`. C'est tout l'intérêt : la
rédactrice sélectionne le bloc, le déplace ou le supprime d'un geste, titre et date compris. Le
motif « Actualité » de l'inserteur produit exactement cette structure.

**Tout ce que dit l'e-mail est repris**, rattaché à l'actualité concernée. La liste des
enseignants par classe appartient au bloc de la rentrée, pas à un bloc séparé : c'est là que le
parent la cherche. Rien ne se perd en route — c'est la personne qui relit qui décide de retirer.

**Des listes, jamais de tableaux.** Un tableau se lit mal sur un téléphone, et la moitié des
familles ouvrent le site depuis un téléphone. Une correspondance classe → date devient une liste
à puces.

**Un service nommé devient un lien, et le lien vient du site.** Quand l'e-mail cite Noéfil,
Educartable ou un service de l'école, le lien officiel existe déjà quelque part — pied de page,
autre actualité — et c'est celui-là qu'on reprend. Une recherche suffit :

```bash
grep -rIoE 'https?://[^"'"'"' <]*noefil[^"'"'"' <]*' theme/
```

Attention à ne pas confondre deux adresses d'un même service : Noéfil a un portail de règlement et
une adresse d'inscription, et elles ne mènent pas au même endroit.

**Une ancre sur chaque actualité pointée de l'extérieur**, en plus des ancres de mois. Elles se
posent dans l'onglet Avancé du bloc, et se conservent d'une refonte à l'autre : un lien envoyé
aux familles il y a six mois doit continuer d'arriver au bon endroit.

## 4. Faire descendre un mois dans les archives

Quand un mois est **entièrement passé**, sa section rejoint les archives du trimestre, en bas de
page. Les trimestres sont **scolaires** :

| Trimestre | Mois |
|---|---|
| 1er | septembre à décembre |
| 2e | janvier à mars |
| 3e | avril à juillet |

Le mois descend tel quel sous son `h3`, et les titres de ses actualités passent en `h4` : les
archives se lisent alors comme secondaires, sans changer d'aspect.

## 5. Publier

Toujours dans cet ordre, et jamais directement en production :

1. modifier les pages **dans le clone** (`st-jo-ops`), par WP-CLI ou dans wp-admin ;
2. regarder le résultat sur `http://localhost:8210/` et `/actualites/`, **y compris en 393 px de
   large** ;
3. `bash scripts/parity-check.sh` — l'écart doit se limiter aux blocs modifiés ;
4. **montrer le résultat à Loïc** ;
5. `bash scripts/deploy-content.sh ecole-maternelle-elementaire-saint-joseph actualites` — il
   affiche le diff et exige un `OUI` tapé à la main ;
6. après publication, réancrer les références visuelles (`npm run baseline` dans `e2e`).

## Pièges déjà rencontrés

**`docker compose exec` n'hérite de rien.** Une variable exportée dans le shell appelant
n'atteint pas le conteneur : il faut `-e SLUG="…"`. Sans ça, `getenv` revient vide et le script
accuse le slug d'être faux.

**WP-CLI en conteneur écrit en root.** Lancer `docker run` avec `--user 1000:1000`, sinon les
fichiers déposés dans `site/www` appartiennent à root et bloquent ensuite `git`.

**Le contenu du clone porte les URLs locales.** `deploy-content.sh` les réécrit vers le domaine de
production au moment de l'envoi ; il ne faut donc pas les remplacer à la main.

**Les emoji du contenu sont des caractères, pas des images.** WordPress les convertit lui-même à
l'affichage. On écrit `🎒` dans le balisage.
