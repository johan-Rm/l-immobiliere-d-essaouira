"""Create fictitious public exports from scratch, without production data."""
import json
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent
OUT = ROOT / 'demo'

def write(name, value):
    path = OUT / name
    path.parent.mkdir(parents=True, exist_ok=True)
    path.write_text(json.dumps(value, ensure_ascii=False, indent=2) + '\n')

def collection(items):
    return {'hydra:member': items, 'hydra:totalItems': len(items), 'hydra:view': {}}

def named(slug, name, **extra):
    return {'slug': slug, 'name': name, 'isActive': True, **extra}

image = {'filename': 'demo.svg', 'url': '/demo.svg', 'alt': 'Illustration de démonstration', 'dimensions': [1200, 800], 'hideStamp': True}
gallery = {'imageGalleries': [{'image': image, 'position': 1}]}
category = named('conseils', 'Conseils')
service_tag = named('nos-services', 'Nos services')
place = named('quartier-demo', 'Quartier fictif', location=named('ville-demo', 'Ville de démonstration'))
kind = named('appartement', 'Appartement', id=1, isLocation=False)
agent = {'@id': '/api/real_estate_agents/demo', 'id': 1, 'name': 'Équipe de démonstration', 'description': 'Profil fictif', 'phone': '', 'email': 'contact@example.invalid', 'primaryImage': {**image, 'filename': 'avatar.svg', 'url': '/avatar.svg'},
         'person': {'firstname': 'Alex', 'lastname': 'Démo', 'phone': '', 'email': 'contact@example.invalid', 'jobTitle': 'Conseiller', 'position': named('conseiller', 'Conseiller'), 'contactRole': 'transactions'}}
organization = {'name': 'Agence Démo — projet immobilier', 'legalName': 'Agence fictive', 'phone': '', 'email': 'contact@example.invalid', 'url': 'http://localhost:3000', 'primaryImage': {**image, 'filename': 'logo.svg', 'url': '/logo.svg', 'dimensions': [240, 80]}, 'secondaryImage': image, 'foundingDate': '2020-01-01', 'numberOfProjects': 3, 'addresses': [{'address': 'Adresse fictive', 'streetAddress': 'Adresse fictive', 'city': 'Ville de démonstration', 'postcode': '00000', 'country': 'Démonstration'}]}

def content(slug, title, ident):
    text = 'Ce contenu et les biens présentés sont fictifs. Cette démonstration illustre les fonctionnalités du projet.'
    return {'id': ident, 'slug': slug, 'headline': title, 'name': title, 'alternativeHeadline': title, 'pushForward': 'Démonstration du projet', 'text': '<p>' + text + '</p>', 'textResume': text, 'articleBody': '<p>' + text + '</p>', 'articleResume': text, 'description': text, 'metaTitle': title, 'metaDescription': text, 'isActive': True, 'primaryImage': image, 'secondaryImage': image, 'gallery': gallery, 'galleryVertical': gallery, 'category': category, 'tags': [service_tag], 'videos': [], 'components': [], 'comments': [], 'media': [], 'blockquote': '', 'blockquoteTitle': '', 'hideStamp': True, 'datePublished': '2026-01-01', 'dateCreated': '2026-01-01', 'dateModified': '2026-01-01', 'url': '/' + slug, 'webPages': []}

slugs = [('accueil', 'Accueil'), ('lagence', 'Une agence fictive'), ('contact', 'Contact de démonstration'), ('nos-services', 'Nos services'), ('faq', 'Questions sur la démonstration'), ('achat-vente-immobilier', 'Biens fictifs à vendre'), ('location-immobilier', 'Biens fictifs à louer')]
pages = [content(slug, title, i + 1) for i, (slug, title) in enumerate(slugs)]
for page in pages:
    page['article'] = {}; page['webPageTemplate'] = {'name': 'PageRightSidebar'}
articles = [content('bienvenue-demo', 'Bienvenue dans la démonstration', 1), content('lagence', 'Présentation du projet', 2), content('nos-bureaux-demo', 'Nos espaces fictifs', 3)]
for page in pages:
    page['article'] = articles[0]
properties = []
for i, (nature, price, title) in enumerate([('vente', 180000, 'Appartement de démonstration'), ('vente', 240000, 'Maison de démonstration'), ('location', 850, 'Location de démonstration')], 1):
    item = content('bien-demo-' + str(i), title, i)
    item.update(reference='DEMO-' + str(i), numberOfPieces=3, numberOfRooms=2, numberOfBathrooms=1, maximumOccupants=4, floorSize=85, areaSize=100, areaTerrace=15, price=price, labelPrice='', place=place, nature=named(nature, 'Vente' if nature == 'vente' else 'Location'), type=kind, duration=named('longue-duree', 'Longue durée'), label=named('coup-de-coeur', 'Sélection démo'), realEstateAgent=agent, rentalPriceType=named('mensuel', 'Par mois') if nature == 'location' else None, amenities=[], details=[], pdfs=[], rentals=[], informations=[], ourOpinion='Bien entièrement fictif', urbanTaxes=0, unionCharges=0, turnover=0, geo={'latitude': 0, 'longitude': 0}, pdfUrl='', websiteUrl='')
    properties.append(item)

exports = {'web_pages': collection(pages), 'articles': collection(articles), 'accommodations': collection(properties), 'accommodation_types': collection([kind]), 'accommodation_types_vente': collection([kind]), 'accommodation_types_location': collection([{**kind, 'isLocation': True}]), 'accommodation_locations': collection([named('ville-demo', 'Ville de démonstration')]), 'tags': collection([category, service_tag]), 'organization': organization, 'organization-lien-reseau-social': collection([]), 'components': collection([{'slug': slug} for slug in ['bienvenue-demo', 'lagence', 'lagence', 'bienvenue-demo', 'nos-bureaux-demo']]), 'real-estate-agent': agent, 'real-estate-agents': collection([agent, {**agent, 'id': 2, 'person': {**agent['person'], 'firstname': 'Sam', 'contactRole': 'locations'}}]), 'menu-articles-nos-services': collection(articles), 'review-links': {'desktop': '', 'mobile': ''}, 'testimonials': {'result': {'reviews': []}, 'status': 'OK'}}
filters = ['original', 'grid', 'grid_nostamp', 'grid_small', 'grid_small_nostamp', 'large', 'large_nostamp', 'vertical', 'vertical_nostamp', 'vertical_large', 'vertical_large_nostamp', 'team_square', 'team_square_medium', 'team_square_small', 'blog_horizontal', 'blog_horizontal_small', 'blog', 'blog_vertical', 'carousel', 'carousel_medium', 'carousel_small', 'mini_thumbnail']
exports['filter_sets'] = {name: {'filters': {'thumbnail': {'size': [1200, 800]}}} for name in filters}
for name, value in exports.items(): write('data/' + name + '.json', value)

fr = {text: text for text in ['Accueil', 'Vente', 'Location', 'Appartement', 'Conseils', 'Nos services', 'Longue durée', 'Contact', 'Rechercher', 'Achat', 'Vacances', 'Budget mini.', 'Budget maxi.', 'Lancer votre recherche', 'Une agence fictive', 'Démonstration du projet']}
en = {**fr, 'Accueil': 'Home', 'Vente': 'For sale', 'Location': 'For rent', 'Appartement': 'Apartment', 'Conseils': 'Advice', 'Nos services': 'Our services', 'Longue durée': 'Long-term rental', 'Rechercher': 'Search', 'Achat': 'Purchase', 'Vacances': 'Holidays', 'Budget mini.': 'Minimum budget', 'Budget maxi.': 'Maximum budget', 'Lancer votre recherche': 'Search properties', 'Une agence fictive': 'A fictitious agency', 'Démonstration du projet': 'Project demonstration'}
for item in pages + articles + properties:
    fr[item['headline']] = item['headline']
    en[item['headline']] = {'Accueil': 'Home', 'Appartement de démonstration': 'Demo apartment', 'Maison de démonstration': 'Demo house', 'Location de démonstration': 'Demo rental', 'Biens fictifs à vendre': 'Fictitious properties for sale', 'Biens fictifs à louer': 'Fictitious properties for rent', 'Contact de démonstration': 'Demo contact', 'Bienvenue dans la démonstration': 'Welcome to the demonstration', 'Présentation du projet': 'Project overview', 'Nos espaces fictifs': 'Our fictitious spaces', 'Une agence fictive': 'A fictitious agency', 'Nos services': 'Our services', 'Questions sur la démonstration': 'Questions about the demonstration'}[item['headline']]
    fr[item['description']] = item['description']
    en[item['description']] = 'All content and properties are fictitious. This demonstration illustrates the project features.'
    fr[item['text']] = item['text']; en[item['text']] = '<p>' + en[item['description']] + '</p>'
ui = json.loads((OUT / 'ui-translations.json').read_text())
fr.update(ui['fr']); en.update(ui['en'])
for locale, messages in [('fr', fr), ('en', en)]:
    for entity, items in [('webpage', pages), ('article', articles), ('accommodation', properties)]:
        for item in items:
            for field in ['text', 'articlebody', 'articleresume', 'textresume', 'description', 'ouropinion']:
                messages[f'{locale}.{field}.{entity}.{item["slug"]}'] = messages[item['text']]
    write('lang/translations/' + locale + '.json', messages)
slug_maps = {'webPage': {p['slug']: p['slug'] for p in pages}, 'article': {p['slug']: p['slug'] for p in articles}, 'accommodation': {p['slug']: p['slug'] for p in properties}, 'accommodationType': {'appartement': 'appartement'}, 'tag': {'conseils': 'conseils', 'nos-services': 'nos-services'}}
for name in ['en', 'fr', 'en-fr']: write('lang/translations/slug/' + name + '.json', slug_maps)
print('Fictitious demo exports generated.')
