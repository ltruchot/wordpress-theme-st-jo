# Documentation du composant Bouton

## Vue d'ensemble

Le composant bouton du thème St-Jo offre une interface cohérente et flexible pour créer des boutons dans WordPress. Il est conçu pour être facilement utilisable par les éditeurs de contenu non-développeurs tout en restant idiomatique de WordPress et du thème _tw.

## Utilisation pour les éditeurs de contenu

### Via l'éditeur de blocs (Gutenberg)

1. **Ajouter un bouton depuis les modèles** :
   - Dans l'éditeur, cliquez sur le bouton "+" pour ajouter un bloc
   - Recherchez "Modèles" ou cliquez sur l'onglet "Modèles"
   - Trouvez la catégorie "Boutons"
   - Choisissez parmi les modèles disponibles :
     - **Bouton principal** : Pour les actions principales
     - **Bouton secondaire** : Pour les actions secondaires
     - **Bouton avec icône** : Avec une icône intégrée
     - **Groupe de boutons** : Plusieurs boutons alignés
     - **Section appel à l'action** : Section complète avec titre et bouton

2. **Personnaliser le bouton** :
   - Après avoir inséré un modèle, cliquez sur le bouton
   - Modifiez le texte directement
   - Pour changer l'URL, éditez le code HTML (vue "Code" du bloc)

### Via l'éditeur classique

Si vous utilisez l'éditeur classique, passez en mode "Texte" et utilisez le shortcode suivant :

```html
<!-- Pour un bouton simple -->
<div><?php st_jo_button( array( 'text' => 'Mon bouton', 'url' => '/ma-page' ) ); ?></div>
```

## Utilisation pour les développeurs

### Dans les templates PHP

```php
// Bouton basique
st_jo_button( array(
    'text' => 'Cliquez ici',
    'url'  => '/contact'
) );

// Bouton secondaire avec icône
st_jo_button( array(
    'text'    => 'Télécharger',
    'url'     => '/fichier.pdf',
    'variant' => 'secondary',
    'icon'    => 'dashicons-download'
) );

// Bouton large désactivé
st_jo_button( array(
    'text'     => 'Bientôt disponible',
    'variant'  => 'secondary',
    'size'     => 'large',
    'disabled' => true
) );

// Bouton pleine largeur
st_jo_button( array(
    'text'       => 'S\'inscrire',
    'url'        => '/inscription',
    'full_width' => true
) );

// Récupérer le HTML sans l'afficher
$button_html = st_jo_button( array(
    'text' => 'Mon bouton',
    'echo' => false
) );
```

### Dans le header ou d'autres éléments du thème

```php
// Dans header.php
<nav class="estjo-header__nav">
    <?php
    // Bouton CTA du header
    st_jo_button( array(
        'text'  => 'Inscription',
        'url'   => '/inscription',
        'class' => 'estjo-header__cta'
    ) );
    ?>
</nav>
```

### Options disponibles

| Paramètre | Type | Défaut | Description |
|-----------|------|---------|-------------|
| `text` | string | 'Bouton' | Texte du bouton |
| `url` | string | '' | URL de destination (crée un lien si rempli) |
| `variant` | string | 'primary' | Style : primary, secondary |
| `size` | string | 'medium' | Taille : small, medium, large |
| `icon` | string | '' | Icône (nom dashicon ou HTML personnalisé) |
| `icon_position` | string | 'start' | Position de l'icône : start, end |
| `class` | string | '' | Classes CSS additionnelles |
| `attributes` | array | array() | Attributs HTML additionnels |
| `disabled` | bool | false | État désactivé |
| `full_width` | bool | false | Bouton pleine largeur |
| `type` | string | 'button' | Type pour les boutons (button, submit, reset) |
| `echo` | bool | true | Afficher ou retourner le HTML |

### Exemples d'icônes

```php
// Avec une dashicon WordPress
st_jo_button( array(
    'text' => 'Envoyer',
    'icon' => 'dashicons-email-alt'
) );

// Avec du SVG personnalisé
st_jo_button( array(
    'text' => 'Retour',
    'icon' => '<svg>...</svg>',
    'icon_position' => 'start'
) );
```

### Personnalisation avancée

```php
// Avec attributs personnalisés
st_jo_button( array(
    'text' => 'Ouvrir la modal',
    'attributes' => array(
        'data-toggle' => 'modal',
        'data-target' => '#myModal',
        'aria-label' => 'Ouvrir la fenêtre modale'
    )
) );

// Bouton de formulaire
st_jo_button( array(
    'text' => 'Envoyer',
    'type' => 'submit',
    'variant' => 'primary',
    'class' => 'my-form-submit'
) );
```

## Structure CSS

Les classes CSS suivantes sont disponibles :

- `.estjo-button` : Classe de base
- `.estjo-button--primary` : Variante principale
- `.estjo-button--secondary` : Variante secondaire
- `.estjo-button--small` : Taille petite
- `.estjo-button--large` : Taille grande
- `.estjo-button--full` : Pleine largeur
- `.estjo-button--disabled` : État désactivé
- `.estjo-button--loading` : État de chargement
- `.estjo-button__icon` : Conteneur d'icône
- `.estjo-button__icon--start` : Icône au début
- `.estjo-button__icon--end` : Icône à la fin

## Accessibilité

Le composant bouton suit les meilleures pratiques d'accessibilité :

- Focus visible pour la navigation au clavier
- Attribut `aria-disabled` pour les liens désactivés
- Support des lecteurs d'écran
- Contraste des couleurs conforme WCAG

## Maintenance

Pour modifier les styles des boutons :
1. Éditez `/tailwind/custom/components/buttons.css`
2. Recompilez avec `npm run dev` ou `npm run build`

Les couleurs utilisent les variables du design system définies dans `theme.json`.