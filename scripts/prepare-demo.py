"""Install fictitious frontend exports; refuse to overwrite private resources."""
import shutil
from pathlib import Path

root = Path(__file__).resolve().parent.parent
site = root / 'nuxt-modern-website'
targets = [site / 'data', site / 'lang', site / '.env']
if any(p.exists() for p in targets):
    raise SystemExit('Demo preparation refused: data/, lang/ or .env already exists. Use a fresh clone or the prepared publication copy.')
for folder in ['data', 'lang']:
    shutil.copytree(root / 'demo' / folder, site / folder)
(site / '.env').write_text('''DEMO_MODE=true
URL_API=http://127.0.0.1:3000/api
URL_DMS=http://127.0.0.1:3000
URL_CDN=
URL_WEBSITE=http://localhost:3000
PATH_DEFAULT_MEDIA=/uploads/media/files/
PATH_DEFAULT_DOCUMENT=/uploads/document/files/
PATH_FORMAT_MEDIA=/media/cache/
VUE_APP_GOOGLE_MAPS_API_KEY=
GOOGLE_ANALYTICS_ID=
''')
print('Fictitious frontend exports prepared. No backend or production access is required.')
