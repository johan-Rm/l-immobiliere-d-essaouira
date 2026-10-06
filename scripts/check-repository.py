"""Check the current source tree without reading ignored private resources."""
import json
import re
import subprocess
from pathlib import Path

root = Path(__file__).resolve().parent.parent
tracked = subprocess.check_output(["git", "ls-files", "-z"], cwd=root).decode().split("\0")
new = subprocess.check_output(
    ["git", "ls-files", "--others", "--exclude-standard", "-z"], cwd=root
).decode().split("\0")
errors = []
for name in sorted(set(tracked + new) - {""}):
    path = root / name
    if not path.is_file():
        continue
    if path.suffix in {".sql", ".zip", ".pem", ".key"}:
        errors.append(f"Private data or archive in source tree: {name}")
    if path.stat().st_mode & 0o111 and not (name.endswith(".sh") or name.endswith("/bin/console")):
        errors.append(f"Unexpected executable file: {name}")
    if path.suffix == ".json":
        try:
            json.loads(path.read_text())
        except (ValueError, UnicodeError) as exc:
            errors.append(f"Invalid JSON: {name}: {exc}")
    if path.suffix in {".php", ".js", ".vue", ".sh", ".md", ".yaml", ".yml"} or name.endswith(".env.example"):
        content = path.read_text(errors="replace")
        if re.search(r"AIza[0-9A-Za-z_-]{30,}", content):
            errors.append(f"Embedded Google API key: {name}")

if errors:
    raise SystemExit("\n".join(errors))
print("Source hygiene and JSON checks passed.")
