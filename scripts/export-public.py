"""Export only publishable source files; never copy old Git history or ignored data."""
import argparse
import shutil
import subprocess
from pathlib import Path

root = Path(__file__).resolve().parent.parent
parser = argparse.ArgumentParser(description=__doc__)
parser.add_argument('destination', type=Path)
args = parser.parse_args()
destination = args.destination.resolve()
if destination == root or destination.exists():
    raise SystemExit('The destination must be a new directory.')
tracked = subprocess.check_output(['git', 'ls-files', '-z'], cwd=root).decode().split('\0')
new = subprocess.check_output(['git', 'ls-files', '--others', '--exclude-standard', '-z'], cwd=root).decode().split('\0')
private_suffixes = {'.sql', '.zip', '.pem', '.key'}
paths = []
for name in sorted(set(tracked + new) - {''}):
    source = root / name
    if not source.is_file():
        continue
    if source.is_symlink() or source.suffix in private_suffixes or '.git' in source.parts:
        raise SystemExit(f'Unsafe export candidate: {name}')
    if subprocess.run(['git', 'check-ignore', '--no-index', '-q', name], cwd=root).returncode == 0:
        continue
    paths.append((source, destination / name))
destination.mkdir(parents=True)
for source, target in paths:
    target.parent.mkdir(parents=True, exist_ok=True)
    shutil.copy2(source, target)
print(f'Exported {len(paths)} source files without Git history or ignored private resources.')
