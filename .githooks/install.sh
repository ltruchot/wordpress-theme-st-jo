#!/usr/bin/env bash
# Activates the versioned hooks of this repository.
#
#   bash .githooks/install.sh
#
# Hooks live in .git/hooks, which git never clones: every machine has to opt in
# once. Pointing core.hooksPath at a versioned directory is what makes them
# travel with the repository instead of being reinvented each time.

set -euo pipefail
cd "$(dirname "$0")/.."

git config core.hooksPath .githooks
printf 'Hooks actives : %s\n' "$(git config core.hooksPath)"
printf 'Le push direct sur main est desormais refuse par ce depot.\n'
