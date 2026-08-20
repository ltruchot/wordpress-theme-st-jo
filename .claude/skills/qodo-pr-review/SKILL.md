---
name: qodo-pr-review
description: Traiter une review de qodo-code-review[bot] sur une Pull Request — récupérer l'« Agent Prompt » de chaque finding, le confier à un sous-agent, vérifier soi-même, poser un commit atomique par finding, pousser, puis déclencher UNE seule nouvelle review. À utiliser quand on demande de « traiter la review qodo » d'une PR, avec ou sans numéro.
globs: []
---

# Traiter une review qodo

`qodo-code-review[bot]` publie une review dont chaque commentaire en ligne contient un
**« Agent Prompt » prêt à l'emploi** — le même bloc description / contexte / zones à corriger
qu'un humain collerait à un assistant. Cette skill automatise la boucle :

**récupérer les findings → corriger chacun via un sous-agent → vérifier soi-même → un commit
atomique par finding → pousser → déclencher UNE re-review → S'ARRÊTER.**

Ne pas boucler. Après `/agentic_review`, la nouvelle review fait souvent apparaître d'autres
points : c'est normal. Loïc relance la skill s'il veut une passe de plus. Jamais d'enchaînement
automatique.

## Interdits absolus

- **Ne jamais merger.** Aucune PR, sur aucun des trois dépôts. Le merge déclenche le déploiement
  d'un site consulté par les familles d'une école : c'est la décision de Loïc, après relecture.
  Pousser une branche n'engage rien, merger engage tout. Pas de `gh pr merge`, pas d'auto-merge.
- **Ne jamais pousser sur `main`.** Le hook `pre-push` le refuse, et c'est voulu. Si le hook
  bloque, c'est qu'on s'est trompé de branche — on ne contourne pas avec `--no-verify`.

## Quand l'utiliser

Loïc dit « traite la review qodo de la PR 17 », « occupe-toi des retours du bot ». Le numéro est
optionnel : par défaut, la PR de la branche courante.

## Anatomie d'un finding

Chaque commentaire en ligne ressemble à ceci (clôture élargie à quatre accents graves pour que
le bloc interne reste littéral) :

````text
<img ... alt="Action required">          ← badge de sévérité (ou « Review recommended »)
7\. Titre du finding <code>🐞 Bug</code> <code>≡ Correctness</code>
<pre> …description en clair… </pre>
<details>
<summary><strong>Agent Prompt</strong></summary>

```
## Issue description
…
## Fix Focus Areas
- chemin/vers/fichier.php[511-523]
```
</details>
````

- **Sévérité** : `Action required` (à corriger) > `Review recommended` (à corriger sauf refus
  argumenté).
- **L'Agent Prompt** est ce qu'on passe **verbatim** au sous-agent. Ses numéros de ligne datent
  de l'instantané de la PR et **dérivent dès la première modification** — localiser par symbole
  ou par contenu, jamais aveuglément par numéro de ligne.

## Procédure

### 1. Résoudre la PR et le dépôt

```bash
REPO=$(gh repo view --json nameWithOwner -q .nameWithOwner)
PR=$(gh pr view --json number -q .number)        # ou le numéro donné
gh pr view "$PR" --json headRefName -q .headRefName
```

Vérifier que la PR porte bien sur la branche courante. Sinon, s'arrêter et demander — on ne
corrige jamais sur la mauvaise branche.

### 2. Récupérer les findings

```bash
gh api "repos/$REPO/pulls/$PR/comments" --paginate \
  --jq '.[] | select(.user.login=="qodo-code-review[bot]") | {id, path, line, body}'
```

Le contexte général se lit aussi dans les commentaires de niveau PR :

```bash
gh api "repos/$REPO/issues/$PR/comments" \
  --jq '.[] | select(.user.login=="qodo-code-review[bot]") | .body'
```

Extraire par finding : numéro, titre, sévérité, catégorie, `path`, `line`, et l'**Agent Prompt**
(le premier bloc de code après `<summary>…Agent Prompt</summary>`).

### 3. Trier AVANT de corriger

Une review peut être relancée : certains findings sont déjà réglés. Pour chacun, lire les
fichiers concernés et décider :

- **Déjà corrigé** → noter `ignoré (déjà résolu)`, ne rien toucher.
- **Toujours présent** → mettre en file.
- **Faux ou inapplicable** — typiquement quand il contredit une décision écrite dans un
  `CLAUDE.md` ou dans `LEADS.md` de `st-jo-ops` → mettre en file comme `refusé`, avec une ligne
  de justification citant la règle. **Ne jamais tordre le code pour satisfaire un mauvais
  finding.**

### 4. Corriger chaque finding via un sous-agent

Par défaut **en série** : dispatch → vérification → commit. Des éditeurs parallèles sur le même
arbre de travail se marchent dessus, et un commit atomique par finding garde le diff de la
re-review lisible. Ne paralléliser que sur des fichiers disjoints, avec
`Agent(..., isolation: "worktree")`.

Un sous-agent `general-purpose` par finding. Le prompt doit contenir, dans cet ordre :

1. **Le cadre du projet.** « Lis d'abord le `CLAUDE.md` du dépôt. Thème WordPress classique
   basé sur `_tw` : PHP 8, Tailwind CSS 4, blocs Gutenberg. Le code et les commentaires sont en
   **anglais**, la documentation et les contenus en **français**. Les couleurs, typographies et
   points de rupture viennent de `theme/theme.json` et `tailwind/tailwind-theme.css` : aucune
   valeur en dur. Accessibilité WCAG 2.1, SEO, performance et sécurité OWASP sont des conditions
   d'acceptation, pas des objectifs. »
2. **Les skills à charger selon les fichiers touchés** — les skills WordPress officielles sont
   dans `.claude/skills/`. Pour du PHP de thème, des blocs ou des motifs, les charger avant de
   toucher au code.
3. **L'Agent Prompt de qodo, verbatim.**
4. **La définition de « terminé »** : « Applique la correction. Si le finding est un 🐞 Bug,
   ajoute de quoi le constater : un test, ou à défaut la marche à suivre exacte pour le
   reproduire avant et vérifier après. Lance les contrôles du dépôt et **colle la sortie
   réelle**. Ne commit pas — l'orchestrateur commit. Rends : fichiers modifiés, ce que tu as
   fait, la preuve. »

Un seul finding par sous-agent, et l'avertissement sur la dérive des numéros de ligne.

### 5. Vérifier soi-même — ne pas croire le sous-agent sur parole

Après chaque retour, relancer les contrôles **soi-même**, avec de vraies sorties :

- Dépôt du thème : `npm run lint`, puis `docker run --rm -v "$PWD":/app -w /app php:8.4-cli php
  -l <fichier>` pour le PHP (`php` n'est pas installé sur la machine).
- **Toute vérification visuelle exige un build de production** : `npm run production`. Le build
  de développement ne rend pas comme la production — la page des actualités sort 3 px plus
  haute, ce qui fait échouer ses captures sur une différence de dimensions.
- Puis, dans `st-jo-ops` : `bash scripts/parity-check.sh` (attendu : 0 écart sur 7 pages) et
  `cd e2e && npm run check:local`.
- Dépôt `st-jo-ops` : `bash -n` sur les scripts modifiés, puis `bash scripts/doctor.sh`.

Si la preuve fournie paraît mince ou fausse, corriger soi-même ou redispatcher. **Le verdict est
le nôtre.**

### 6. Un commit atomique par finding

Message en français, à l'impératif, qui dit ce que ça change et pourquoi — pas le nom du bot.
Terminer par `Co-Authored-By: Claude Opus 5 <noreply@anthropic.com>`.

### 7. Pousser

```bash
git push
```

Pousser une branche est sans risque et attendu : le faire d'office, sans demander.

### 8. Déclencher UNE seule nouvelle review

Une fois tout corrigé, committé et poussé :

```bash
gh pr comment "$PR" --body "/agentic_review"
```

**S'ARRÊTER ici.** Ne pas récupérer ni traiter la nouvelle salve automatiquement.

### 9. Rendre compte

Un tableau compact : chaque finding → `corrigé (commit)` / `ignoré (déjà résolu)` /
`refusé (raison)`, plus le résultat du push et la confirmation que `/agentic_review` est posté.
Dire ce qui a été vérifié et ce qui ne l'a pas été.

## Garde-fous

- **Les actions vers l'extérieur** (push, commentaire de PR) n'ont lieu que dans le cadre de
  cette skill, parce que Loïc a demandé de « traiter la review ». Sur un simple « regarde les
  retours », on lit et on rapporte, on n'écrit rien.
- **Refuser un finding est légitime** quand il contredit une décision documentée. Citer la
  règle ; ne pas s'y plier en silence, ne pas l'ignorer en silence non plus.
- **Pas de boucle.** Une passe de correction, une `/agentic_review`. Point final.
