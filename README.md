Wordpress Thème St Jo
=====

Thème du site web de l’école St Joseph de La Bouëxière

## Démarrage rapide pour les dev: installer, developer, deployer

- installer "Local": https://localwp.com/ et node.js en v22.x.x https://nodejs.org/en/download
- créer/importer un worpress à jour via "Local" (bouton + en bas à gauche) (ex: `C:\Users\<remplacer-par-mon-username>\Local Sites\wp-st-jo` sous windows 11)
- lancer ce site avec local (run site, open in browser)

Enfin, cloner ce repertoire dans les themes du  site concerné (ex: `cd C:\Users\<remplacer-par-mon-username>\Local Sites\wp-st-jo\app\public\wp-content\themes` sous windows 11)
- `git clone https://github.com/ltruchot/wordpress-theme-st-jo.git`
- `cd wordpress-theme-st-jo`
- `npm install` à la premiere utilisation
- `npm run watch` démarre le theme en mode developpement local

À la première utilisation:
- dans le navigateur, accéder à l’Admin en local: http://wp-st-jo.local/wp-admin
- passer le site en français si besoin: http://wp-st-jo.local:10004/wp-admin/options-general.php
- puis activer le theme:
  - Tableau de bord -> Apparence -> Thèmes -> wordpress-theme-st-jo -> activer

Changer quelque chose dans un template, par exemple ajouter "bonjour" juste après `<main id="main">` dans `theme/page.php`  
Recharger la page d’accueil de site http://wp-st-jo.local/ : le texte devrait s’afficher après l’entete du site  


Pour voir ce changement sur https://ecole.st-joseph.fr: 
- `git add -A && git commit -m "test deploiement" && git push` sur la branche `main` ou faire un PR depuis une nouvelle branche vers `main` et merger
- aller sur https://github.com/ltruchot/wordpress-theme-st-jo/actions
- une fois que l’action "deploy" est terminée, vérifier le changement: https://ecole.st-joseph.fr/  



---------------------------------------------------------

Generated documentation

### Installation

1. Move this folder to `wp-content/themes` in your local development environment
2. Run `npm install && npm run dev` in this folder
3. Activate this theme in your local WordPress installation

### Development

4. Run `npm run watch`
5. Add [Tailwind utility classes](https://tailwindcss.com/docs/utility-first) with abandon

### Deployment

6. Run `npm run bundle`
7. Upload the resulting zip file to your site using the “Upload Theme” button on the “Add Themes” administration page

Or [deploy with the tool of your choice](https://underscoretw.com/docs/deployment/#h-other-deployment-options)!

## Full Documentation

### Fundamentals

* [Installation](https://underscoretw.com/docs/installation/)  
  Generate your custom theme, install it in WordPress and run your first Tailwind builds
* [Development](https://underscoretw.com/docs/development/)  
  Watch for changes, build for production and learn more about how _tw, WordPress and Tailwind work together
* [Deployment](https://underscoretw.com/docs/deployment/)  
  Share your new WordPress theme with the world
* [Troubleshooting](https://underscoretw.com/docs/troubleshooting/)  
  Find solutions to potential issues and answers to frequently asked questions

### In Depth

* [Using Tailwind Typography](https://underscoretw.com/docs/tailwind-typography/)  
  Customize front-end and back-end typographic styles
* [JavaScript Bundling with esbuild](https://underscoretw.com/docs/esbuild/)  
  Install and bundle JavaScript libraries (very quickly)
* [Adding custom fonts](https://underscoretw.com/docs/custom-fonts/)
  Host your fonts yourself or use a third party—and then add those fonts to your WordPress theme
* [Linting and Code Formatting](https://underscoretw.com/docs/linting-code-formatting/)  
  Catch bugs and stop thinking about formatting
* [Keeping your theme up-to-date](https://underscoretw.com/docs/updating/)
  How to update (and whether or not you should)

### Extras

* [On Tailwind and WordPress](https://underscoretw.com/docs/wordpress-tailwind/)  
  Understand how WordPress and Tailwind work together
* [Styling HTML from outside the theme](https://underscoretw.com/docs/styling-html-from-outside-the-theme/)
  Work with WordPress core, plugins and JavaScript libraries
* [Managing Styles for Custom Blocks](https://underscoretw.com/docs/custom-blocks/)  
  Learn strategies for using Tailwind in theme-specific custom blocks
* [Setting Up Browsersync](https://underscoretw.com/docs/browsersync/)  
  Add live reloads and synchronized cross-device testing to your workflow
