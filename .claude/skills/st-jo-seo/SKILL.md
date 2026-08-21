---
name: st-jo-seo
description: À utiliser pour tout ce qui touche à la visibilité du site dans les moteurs — sitemap, titres, métadonnées, données structurées, accessibilité et mesure Lighthouse/axe. À consulter avant d'ajouter une extension SEO, de toucher aux titres ou aux balises du `<head>`, ou de se demander pourquoi le site ne ressort pas sur « école La Bouëxière ».
---

# Être trouvé

L'objectif tient en une requête : **« école La Bouëxière »**, et ses variantes. Une famille qui
cherche une école tape le type d'établissement et le nom de la commune. Si le site de l'école n'est
pas là, ce sont la mairie, l'annuaire de l'Éducation nationale et trois agrégateurs qui répondent à
sa place.

Deux choses se jouent, et elles ne se ressemblent pas :

| | |
|---|---|
| **Sur le site** | que les moteurs puissent lire, comprendre et identifier les pages |
| **Hors du site** | que des domaines de confiance pointent vers l'école — et c'est le plus lourd |

## 1. Le sitemap, et pourquoi il répondait 404

**À savoir avant de rediagnostiquer ce bug une deuxième fois.** Le sitemap du cœur n'a jamais été
absent : `/wp-sitemap.xml` renvoie l'index XML complet. C'est le **statut HTTP** qui était faux, et
un moteur lit un 404 comme « ça n'existe pas ».

`WP::handle_404()` s'exécute **avant** le rendu du sitemap et n'exempte que `is_admin()`,
`is_robots()` et `is_favicon()` — il n'y a **aucune exemption pour `is_sitemap()`**. Une requête
sitemap lance une requête d'articles ordinaire ; ce site publie des **pages et zéro article**, la
requête revient vide, le cœur pose le 404. `WP_Sitemaps::render_sitemaps()` écrit ensuite le XML et
sort sans jamais remettre le statut à 200.

Deux contre-épreuves, à refaire si le doute revient :

```bash
curl -o /dev/null -w '%{http_code}\n' https://ecole.st-joseph.fr/wp-sitemap.xsl   # 200
curl -o /dev/null -w '%{http_code}\n' 'https://ecole.st-joseph.fr/?sitemap=index' # 404, même XML
```

Le `.xsl` passe par une route qui pose `sitemap-stylesheet` au lieu de `sitemap` : `is_sitemap`
reste faux et `handle_404()` le laisse tranquille. Même moteur, mêmes zéro articles, statut correct.
Et `/?sitemap=index` ne passe par aucune règle de réécriture, ce qui met celles-ci hors de cause.

Le correctif vit dans `theme/inc/seo.php`, sur `pre_handle_404`. C'est
[Trac #51912](https://core.trac.wordpress.org/ticket/51912) en amont — ouvert contre les sitemaps
paginés, mais décrivant cette chaîne exacte.

**Ce qui rendrait le correctif inutile : publier de vrais articles.** Le jour où les actualités
deviennent des `post` plutôt que du contenu écrit à la main dans une page, la requête principale
n'est plus vide et le bug disparaît de lui-même. C'est aussi le vrai idiome WordPress, et ça donne
à chaque actualité une URL indexable. Décision reportée, pas écartée.

## 2. Qui produit quoi dans le `<head>`

Le cœur fournit le `<title>`, le `robots.txt` virtuel, le sitemap, et un `rel="canonical"` **limité
aux vues singulières**. Il ne fournit **ni** meta description, **ni** Open Graph, **ni** JSON-LD.

| Quoi | Qui |
|---|---|
| `<title>`, canonical, sitemap, robots.txt | le cœur |
| meta description, Open Graph, Twitter, fil d'Ariane | **Slim SEO** |
| JSON-LD de l'école (`ElementarySchool`, `WebSite`) | **le thème**, `theme/inc/seo.php` |
| statut du sitemap | **le thème**, `theme/inc/seo.php` |

**Ne pas empiler deux extensions SEO**, et ne pas dupliquer un nœud `Organization` : si Slim SEO en
émet un, on le raccorde par `@id` au nœud du thème au lieu d'en publier deux qui se contredisent.

### Le titre de l'accueil se règle dans l'administration, pas dans le code

`wp_get_document_title()` compose **`nom du site` + séparateur + `slogan`** sur l'accueil, et
**`titre de la page` + séparateur + `nom du site`** partout ailleurs. Le titre de la page d'accueil
statique n'apparaît donc **jamais** : c'est le **slogan** (*Réglages → Général*) qu'on lit dans les
résultats de recherche.

Corollaire : une faute dans le slogan est une faute dans le premier résultat Google du site. Il y en
a eu une — « émentaire » pour « élémentaire » — pendant des mois.

Pour changer la construction, filtrer `document_title_parts`. Jamais écrire le `<title>` en dur dans
un gabarit : la pagination, la recherche et les archives cesseraient de fonctionner.

### L'image d'aperçu au partage, et par où elle passe

Quand un parent colle un lien dans le groupe WhatsApp de la classe, c'est `og:image` qui décide s'il
y a une vignette ou un rectangle vide. Les pages du site **n'ont pas d'image mise en avant** : elles
dépendent donc toutes de l'image par défaut, un seul réglage pour les sept.

Slim SEO la cherche dans cet ordre, et s'arrête au premier trouvé — `src/MetaTags/Image.php` :

1. le champ « Facebook image » de la boîte SEO **de la page** ;
2. le réglage du **type de contenu** ;
3. l'**image mise en avant** de la page ;
4. `slim_seo[default_facebook_image]`, *SEO → Réseaux sociaux → Image Facebook*.

**Le réglage stocke une URL, pas un identifiant de média.** Slim SEO remonte ensuite à la pièce
jointe *depuis* cette URL pour publier `og:image:width`, `og:image:height` et `og:image:alt`. D'où
une conséquence à ne pas découvrir en production : si l'URL n'est pas exactement celle que WordPress
sert — une variante redimensionnée, une adresse de CDN, un domaine qui a changé — la résolution
échoue en silence, `og:image` sort seul, et l'aperçu perd ses dimensions et son texte alternatif.

**`og:image:alt` est le texte alternatif du média** (`_wp_attachment_image_alt`), à défaut son titre.
Le texte alternatif d'un média n'est donc pas de la décoration d'arrière-boutique : il est publié
dans le `<head>` de toutes les pages.

### Fabriquer l'image, et l'y déposer

Trois contraintes, dans cet ordre d'importance :

- **Le poids.** L'aperçu est téléchargé par l'application qui l'affiche, dans un délai court et
  souvent en réseau mobile. Un PNG de photo est le mauvais format — 1,39 Mo pour l'image de 2026 —
  là où un JPEG progressif de qualité 88 en sous-échantillonnage 4:2:0 rend 217 Ko pour 38,5 dB de
  PSNR, soit un écart invisible à l'œil (mesuré). *Non vérifié ici, et souvent rapporté* : WhatsApp
  renoncerait à la vignette au-delà de quelques centaines de kilooctets. On n'a pas de moyen de le
  constater depuis le dépôt ; viser petit ne coûte rien et lève la question.
- **La taille.** Au-delà de 600 × 315 px, Facebook affiche la grande carte ; 1200 × 630 est
  l'optimum sur écran dense. **Ne pas agrandir pour atteindre le chiffre** : des pixels inventés se
  voient, un aperçu un peu moins net ne se voit pas. Les plateformes recadrent au centre — c'est
  donc le centre de l'image qui doit porter le sujet.
- **Les métadonnées transportées.** Une photo venue d'un téléphone porte souvent la position GPS de
  la prise de vue. Réencoder sans recopier EXIF ni profil ICC, et le vérifier plutôt que le supposer.

Le dépôt du thème est **public** : une photo d'élèves n'y entre jamais, même le temps d'un commit
(`raw/` est ignoré pour cette raison). Le trajet est `raw/` → médiathèque, par

```bash
bash scripts/deploy-media.sh photo.jpg --titre "…" --alt "…" --legende "…"
```

Le `--alt` est ce qui ressortira en `og:image:alt`. Le script affiche l'URL publique à recopier dans
le réglage.

## 3. Les données structurées de l'école

Le type le plus spécifique pour une école primaire française est **`ElementarySchool`**
(`Thing` → `Organization` → `EducationalOrganization` → `School` → `ElementarySchool`).

**Dire honnêtement ce que ça apporte.** Google **ne produit aucun résultat enrichi** pour `School`
ni pour `EducationalOrganization` : l'affichage dans les résultats ne changera pas. Le gain est la
**désambiguïsation d'entité** — rattacher un nom, une adresse, un téléphone et l'UAI à une seule
organisation, ce qu'une requête « école + commune » doit résoudre.

**Ne pas déclarer l'école en `LocalBusiness`** pour aller chercher un résultat enrichi : le type
serait inexact, et les règles sur les données structurées l'interdisent.

Trois exigences à ne pas rater :

- **Tout ce qui est balisé doit être visible** sur la page. L'adresse et le téléphone sont dans le
  pied de page, c'est ce qui rend le balisage légitime.
- **`geo` demande au moins 5 décimales.** Les 3 publiées par l'annuaire ne suffisent pas.
- **Le nœud `WebSite` va sur la racine du domaine**, nulle part ailleurs. C'est le mécanisme
  documenté par Google pour choisir le nom affiché du site et reconnaître ses variantes.

Les valeurs de référence de l'établissement :

| | |
|---|---|
| UAI | **0351195J** |
| Adresse | **Allée Henri Queffélec, 35340 La Bouëxière** — orthographe de l'annuaire officiel |
| Téléphone | 02 99 62 63 09 |
| Coordonnées | 48.184514, -1.440811 |
| Statut | privé sous contrat, réseau de l'enseignement catholique (DDEC 35) |

## 4. Les accents, et ce qu'il ne faut surtout pas faire

Google **étend les requêtes accentuées** : « Bouexiere » et « Bouëxière » ramènent les mêmes
résultats, et les francophones omettent les accents environ une fois sur deux. **L'orthographe
correcte se positionne donc sur les deux formes**, et il n'y a aucun bénéfice à retirer les
diacritiques.

À ne jamais faire, dans le contenu comme dans les balises :

- **empiler les variantes** (« école La Bouexiere, ecole la bouexière, école Bouëxière 35340 ») —
  c'est du bourrage de mots-clés au sens exact des règles anti-spam, et le code postal listé
  mécaniquement en est l'exemple nommé ;
- **créer une page par commune voisine** (Liffré, Servon-sur-Vilaine…) — c'est du *doorway abuse*,
  également nommé. Une seule page honnête sur la zone desservie suffit ;
- **déposer un `robots.txt` physique** à la racine chez l'hébergeur : le serveur le servirait
  directement, WordPress ne s'exécuterait jamais, et la ligne `Sitemap:` disparaîtrait en silence.

Les slugs restent en ASCII : `sanitize_title()` applique déjà `remove_accents()`, et c'est le bon
comportement — une URL percent-encodée casse le partage et les journaux.

## 5. Ce qui pèse le plus, et qui ne se code pas

**Rien de tout cela ne se déclenche avant que le site remplaçant ne soit jugé prêt.** C'est une
décision de l'école, pas une conséquence d'un audit — et pousser des liens vers un site inachevé
coûte plus qu'il ne rapporte.

**L'ancien site `stjo35.free.fr/ecole/` est encore en ligne et indexable** (vérifié le 21/08/2026 :
200, titre « Accueil, Ecole Saint Joseph », `robots.txt` n'interdisant que quatre dossiers
techniques). C'est lui qui fait foi jusqu'au lancement, et **une redirection vers le nouveau site
est prévue** — c'est le bon geste : une **301** transfère l'ancienneté et les liens accumulés, là où
une mise hors ligne sèche les perd.

Le seul détail à ne pas rater le jour venu : rediriger **chaque ancienne page vers son équivalent**,
pas tout vers l'accueil. Une redirection globale vers la racine est traitée comme une page
introuvable déguisée et ne transmet rien.

Ensuite seulement, par rapport impact/effort décroissant :

1. **Le champ « site web » de la fiche UAI 0351195J** à l'annuaire de l'Éducation nationale est
   **vide**. Cette page est déjà dans les premiers résultats de la requête cible. Le remplir passe
   par la direction (remontée RAMSESE / DSDEN 35).
2. **La page « Écoles » de la mairie** pointe vers `http://stjo35.free.fr/ecole/`. Ce n'est pas une
   erreur tant que c'est l'ancien site qui fait foi : le lien se change au lancement, pas avant.
3. **La fiche Google Business Profile.** C'est le pack local, donc ce qui s'affiche avant les
   résultats organiques. La vérification par **vidéo** est devenue la méthode par défaut : une prise
   unique et continue, la signalétique extérieure avec le nom exact, l'intérieur, une action de
   gestion. Échouer une fois retarde de plusieurs semaines.
4. **Search Console**, propriété de type **Domaine** (enregistrement TXT dans la zone DNS), puis
   soumission du sitemap.
5. **Le NAP figé au caractère près**, puis 6 à 8 citations : DDEC 35, `enseignement-prive.info`,
   OpenStreetMap (`amenity=school`, `ref:UAI`, `website`), Apple Business Connect, Bing Places.

**Attention à la cohérence des coordonnées** : trois adresses e-mail différentes circulent
aujourd'hui pour l'école, selon qu'on lit le thème, le site de la mairie ou l'annuaire. C'est
exactement le signal que le NAP est censé unifier.

## 6. Mesurer

Le clone est la cible de mesure ; la production répond **403 à tout User-Agent inconnu**, ce que la
configuration Playwright compense déjà.

| Outil | Où | Seuil |
|---|---|---|
| Invariants SEO | `e2e/tests/invariants.spec.ts` | bloquant |
| `@axe-core/playwright` | même suite, 3 projets | bloquant sur `serious` et `critical` |
| `@lhci/cli` | contre le clone, 3 passages, médiane | SEO **1,00**, a11y ≥ 0,95, perf ≥ 0,85 |

**Dire ce que la mesure ne dit pas.** axe attrape environ **57 % des occurrences** mais seulement
**~30 % des types** de problèmes : un build vert n'est pas une conformité AA. Le score SEO de
Lighthouse n'audite qu'une douzaine de contrôles binaires — il doit être à 100 sans négociation, et
il ne prédit aucun classement. Et le site n'aura **pas de données CrUX** faute de trafic : le
rapport « Signaux web essentiels » de la Search Console restera vide, ce n'est pas un défaut.

Toute exclusion axe est versionnée **avec sa raison écrite**.

**Les rapports Lighthouse s'archivent, puis le dossier repart vide.** `check:lighthouse` déplace le
passage précédent dans `.lighthouse-archive/<horodatage>/`, en garde cinq, et laisse `.lighthouse`
ne décrire qu'une seule exécution. Sans ça, Lighthouse ajoute ses rapports à côté des anciens et
relire le dossier mélange deux états du site : c'est ainsi qu'une page notée 100 a été relue à 89
ici même.

## 7. Le piège du clone, déjà payé

Le garde-fou local forçait `blog_public` à 0, ce qui coupait les sitemaps **par un chemin que la
production ne prend pas** : sur cette question précise, le clone était aveugle et le bug invisible.
Il interdit désormais l'indexation par trois voies qui ne touchent pas au routage — `wp_robots`, un
en-tête `X-Robots-Tag` (qui couvre aussi le XML, sans `<head>`), et un `robots.txt` en
`Disallow: /`.

**La leçon générale** : un garde-fou qui simplifie l'environnement finit par cacher ce qu'on cherche.
Quand une vérification est impossible en local, la question à se poser d'abord est « qu'est-ce qui,
chez nous, rend ça impossible ? » — pas « comment fait-on sans ».
