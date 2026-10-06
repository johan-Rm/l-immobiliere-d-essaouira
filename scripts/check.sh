#!/usr/bin/env bash
# Dependency-free syntax and repository hygiene checks, not application tests.
set -euo pipefail
repo_root="$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$repo_root"
python3 scripts/check-repository.py
while IFS= read -r -d '' file; do
    bash -n "$file"
done < <(find scripts -type f -name '*.sh' -print0)
bash -n site-sync.sh
bash -n analyse_formats_liip.sh
bash -n digital-management-system/docker-entrypoint.sh
while IFS= read -r -d '' file; do
    php -l "$file" >/dev/null
done < <(find digital-management-system/src digital-management-system/config digital-management-system/public \
    -type f -name '*.php' ! -path '*/uploads/*' ! -path '*/media/*' -print0)
git diff --check
printf 'Repository hygiene, JSON, shell and PHP syntax checks passed.\n'
