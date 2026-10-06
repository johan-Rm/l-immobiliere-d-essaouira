#!/usr/bin/env bash
# Compatibility entrypoint; configuration now comes from environment variables.
set -euo pipefail
repo_root="$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")" && pwd)"
exec bash "$repo_root/scripts/sync-private.sh" "$@"
