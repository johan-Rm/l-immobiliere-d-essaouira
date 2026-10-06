#!/usr/bin/env bash
# Pull private resources into the local development environment.
set -euo pipefail
repo_root="$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")/.." && pwd)"
usage() {
    echo 'Usage: scripts/sync-private.sh --resources | --database --replace-local-database'
    echo 'Required: SYNC_SSH_TARGET (user@host), SYNC_REMOTE_RESOURCES (absolute path).'
    echo 'Database mode requires SYNC_REMOTE_DATABASE and a remote MySQL client configuration.'
}
mode="${1:---help}"
case "$mode" in
    --help|-h) usage; exit 0 ;;
    --resources|--database) ;;
    *) usage >&2; exit 2 ;;
esac
: "${SYNC_SSH_TARGET:?Set SYNC_SSH_TARGET to an authorized SSH target}"
if [[ ! "$SYNC_SSH_TARGET" =~ ^[a-zA-Z0-9_.-]+@[a-zA-Z0-9_.-]+$ ]]; then
    echo 'Invalid SSH target.' >&2; exit 2
fi
cd "$repo_root"
if [[ "$mode" == '--resources' ]]; then
    : "${SYNC_REMOTE_RESOURCES:?Set the remote resources directory}"
    if [[ ! "$SYNC_REMOTE_RESOURCES" =~ ^/[a-zA-Z0-9_./-]+$ ]]; then
        echo 'Invalid remote resources path.' >&2; exit 2
    fi
    mkdir -p digital-management-system/public/{media,uploads}
    # No --delete: preserve local resources absent from the remote source.
    for resource in media uploads; do
        rsync -az --no-perms -- "$SYNC_SSH_TARGET:${SYNC_REMOTE_RESOURCES%/}/$resource/" \
            "digital-management-system/public/$resource/"
    done
else
    if [[ "${2:-}" != '--replace-local-database' ]]; then
        echo 'Database imports require --replace-local-database.' >&2; exit 2
    fi
    : "${SYNC_REMOTE_DATABASE:?Set the authorized remote database name}"
    if [[ ! "$SYNC_REMOTE_DATABASE" =~ ^[a-zA-Z0-9_]+$ ]]; then
        echo 'Invalid database name.' >&2; exit 2
    fi
    mkdir -p .local-backup
    # Credentials stay in the remote MySQL client configuration.
    ssh "$SYNC_SSH_TARGET" "mysqldump --single-transaction --no-tablespaces '$SYNC_REMOTE_DATABASE'" \
        > .local-backup/sync-database.sql
    docker compose exec -T db sh -c \
        'MYSQL_PWD="$MYSQL_PASSWORD" mysql -u "$MYSQL_USER" "$MYSQL_DATABASE"' \
        < .local-backup/sync-database.sql
fi
