Wordpress Thème St Jo
=====

Thème du site web de l’école St Joseph de La Bouëxière

## Démarrage rapide pour les dev

Deux dépôts travaillent ensemble :

- **celui-ci** — le thème ;
- **`st-jo-ops`** (privé) — un clone local de la production : les fichiers exacts du serveur, le
  contenu réel du site, la même version de PHP. C'est là qu'on voit l'effet d'un changement
  avant qu'il ne soit public.

```bash
git clone git@github.com:ltruchot/st-jo-ops.git
cd st-jo-ops && docker compose up -d --build     # http://localhost:8210

cd .. && git clone git@github.com:ltruchot/wordpress-theme-st-jo.git
cd wordpress-theme-st-jo && npm install && npm run watch
```

Le thème est monté en direct dans le clone : on modifie ici, on rafraîchit
<http://localhost:8210>, on voit. La mise en route complète du clone est décrite dans le
`README.md` de `st-jo-ops`.

Pour essayer : ajouter « bonjour » juste après `<main id="main">` dans `theme/page.php`, puis
recharger une page — le texte apparaît sous l'en-tête du site.

### Alternative : LocalWP

Sans Docker, on peut installer [LocalWP](https://localwp.com/) et Node 22.x, créer un site
WordPress, puis cloner ce dépôt dans son dossier `wp-content/themes`, `npm install`,
`npm run watch`, et activer le thème depuis l'administration. Le contenu ne sera pas celui du
site en ligne.

## Mettre un changement en production

- créer une branche, faire une Pull Request vers `main`, la faire relire, la merger ;
- le déploiement part au merge et se vérifie tout seul (voir [`docs/DEPLOYMENT.md`](docs/DEPLOYMENT.md)) ;
- suivre le run sur <https://github.com/ltruchot/wordpress-theme-st-jo/actions>, puis vérifier
  <https://ecole.st-joseph.fr/>.

En cas de problème, le workflow **Rollback Production** remet la version précédente en ligne.

## Changements en profondeurs
- demander à Loïc TRUCHOT le mot de passe SSH de la gestion de Thème
- `ssh stjoseu@ssh.cluster027.hosting.ovh.net`, entrer password
- `cd /homez.1929/stjoseu/www/wp-content/themes/st-jo` pour retrouver le thème 
- des backups faits à chaque déploiements sont présents dans /homez.1929/stjoseu/themes-backup
- on peut donc restaurer une version précédente avec: `cp -r /homez.1929/stjoseu/themes-backup/20250626_124849/themes/* /homez.1929/stjoseu/www/wp-content/themes/` ou encore, en revenant en arrière sur un changement dans le versionning git et en redeployant

## Hébergement
- demander à Loïc TRUCHOT l’accès à l’hébergement OVH
- https://www.ovh.com/manager/#/web/hosting/stjoseu.cluster027.hosting.ovh.net

## Déploiement automatique
- `.github/workflows/deploy-rsync.yml` définit le déploiement automatique sur le serveur OVH

## Design 
- overview: https://www.figma.com/proto/nT7ysMo8BiuyKMqMuUTyqN/Ecole-Saint-Joseph?page-id=4772%3A22037&node-id=31-4104&viewport=797%2C-682%2C0.3&t=KJYxIoTOQnBwxFzd-1&scaling=min-zoom&content-scaling=fixed&starting-point-node-id=31%3A4104&show-proto-sidebar=1
- editor view for dev: https://www.figma.com/design/nT7ysMo8BiuyKMqMuUTyqN/Ecole-Saint-Joseph?node-id=31-4104


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
