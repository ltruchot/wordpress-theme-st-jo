---
name: qodo-preview
description: Lancer la review locale de Qodo (le même moteur que le bot de PR) sur les changements non committés ou sur la branche, AVANT de committer ou de pousser, et ramener les retours dans la session. À utiliser avant de committer un changement notable, avant de pousser une branche, ou quand on demande un « aperçu local de la review qodo ».
---

# Aperçu local de la review qodo

Le bot `qodo-code-review[bot]` qui commente les Pull Requests tourne sur le même moteur que
**Qodo Command** (`@qodo/command`), disponible en ligne de commande. Le lancer sur l'arbre de
travail **avant** de committer transforme l'aller-retour
(pousser → review du bot → correction → repousser → re-review) en boucle interne : les retours
arrivent pendant que le changement est encore ouvert, pas trois étapes plus tard.

L'intérêt n'est pas que le moteur soit plus intelligent qu'une relecture attentive. C'est
qu'il apporte trois choses qu'on ne se donne pas à soi-même : un **regard neuf sur le seul
diff**, la **liste de conformité configurée pour le dépôt**, et un **cadrage adverse**. Les
trois fonctionnent aussi bien sur un diff local que sur une PR.

## Interdits absolus

- **Ne jamais merger.** Aucune PR, sur aucun des trois dépôts. C'est la décision de Loïc, après
  relecture.
- **Ne jamais pousser sur `main`** dans `wordpress-theme-st-jo` ni dans `st-jo-ops` : le hook
  `pre-push` le refuse, et c'est voulu. Si le hook bloque, c'est qu'on s'est trompé de branche —
  on ne contourne pas avec `--no-verify`. `st-jo-backups` fait exception : son contenu est
  produit par `scripts/push-backup.sh`, jamais édité à la main.

## Prérequis, une fois pour toutes

```bash
npm install -g @qodo/command   # Node >= 18 ; la commande est `qodo`
qodo login                     # authentification par navigateur — c'est Loïc qui la fait
```

À savoir : chaque exécution consomme des crédits de la plateforme Qodo. C'est une boucle payante,
et c'est pourquoi elle reste **sur demande**, jamais automatique.

Les workflows partagés vivent dans `.qodo/workflows/`, versionnés avec le dépôt.

## Comment s'en servir

### A. Aperçu avant commit — le cas courant

```bash
qodo /review-uncommitted --ci
```

`/review-uncommitted` examine le diff de l'arbre de travail (indexé et non indexé). `--ci` donne
une sortie exploitable sans interaction.

Avant de pousser une branche, sur le diff complet contre `main` :

```bash
qodo /review-committed --ci
```

Lire les retours, les trier exactement comme le fait la skill `qodo-pr-review` — corriger, ou
refuser en citant la règle —, corriger sur place, **puis** committer.

### B. Barrière au push, sur demande uniquement

Une review par un modèle prend trente secondes à deux minutes et coûte des crédits : la brancher
sur chaque commit serait invivable. Si on veut une barrière, elle se met dans le hook `pre-push`
du dépôt (`.githooks/pre-push`), **conditionnée à une variable d'environnement** :

```bash
# .githooks/pre-push — sur demande : QODO_PREVIEW=1 git push
if [ "${QODO_PREVIEW:-}" = "1" ]; then
  qodo /review-committed --ci || {
    echo "Review locale qodo : des points bloquants. Les corriger ou les refuser avant de pousser."
    exit 1
  }
fi
```

La sortie du hook revient directement dans la session : c'est la boucle de retour au fil de
l'eau.

### C. Agent maison, quand le workflow standard passe à côté des règles du projet

Qodo Command accepte des agents définis dans le dépôt (`.qodo/agents/<nom>/agent.toml`). Si le
workflow intégré ne voit pas les règles de la maison, en définir un qui les porte :

```toml
version = "1.0"
[commands.revue_maison]
description = "Relit le diff non committé selon les règles du projet"
instructions = """
Relis `git diff HEAD` en cherchant : bugs de correction, failles OWASP,
manquements aux règles des CLAUDE.md du projet — couleurs, typographies et
points de rupture pris dans theme.json et tailwind-theme.css et jamais en dur ;
accessibilité WCAG 2.1 ; code et commentaires en anglais, documentation et
contenus en français ; rien qui référence un autre projet.
Une ligne par point : SEVERITE | fichier:ligne | titre | pourquoi.
Sortie non nulle s'il reste un point bloquant.
"""
execution_strategy = "act"
```

Puis `qodo revue_maison --ci`.

## Tri des retours

Mêmes règles que `qodo-pr-review` :

- Un retour qui contredit une décision écrite dans un `CLAUDE.md` ou dans `LEADS.md` de
  `st-jo-ops` → **refus argumenté**, en citant la règle. Ne jamais tordre le code pour lui
  plaire.
- Un 🐞 Bug qu'on accepte → correction **et** de quoi le constater : un test, ou à défaut la
  marche à suivre exacte pour le reproduire avant et vérifier après.
- Jamais de contournement de hook.

## Avant de faire confiance à la sortie

À la première utilisation sur cette machine, valider les hypothèses plutôt que les supposer :

1. `qodo --version` et `qodo --help` : confirmer le nom exact de l'option non interactive
   (`--ci` ou son équivalent courant) et celui des workflows intégrés.
2. Lancer le cas A sur un arbre modifié : vérifier que la sortie est exploitable et que le code
   de retour reflète bien les retours.
3. Seulement ensuite, brancher le cas B dans `.githooks/pre-push`, dans un commit dédié.

Tant que ces trois points ne sont pas faits, dire que la sortie n'a pas été validée plutôt que
de la présenter comme fiable.
