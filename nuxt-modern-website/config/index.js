// i18n messages
const en = require('../lang/translations/en.json')
const fr = require('../lang/translations/fr.json')

// const en = {}
// const fr = {}

// i18n config
const LOCALES = [
    { code: 'fr', iso: 'fr-FR', name: 'FR' }
    ,{ code: 'en', iso: 'en-US', name: 'EN' }
]
const DEFAULT_LOCALE = 'fr'
const I18N = {
  en,
  fr
}

// Define custom paths for localized routes
// If a route/locale is omitted, defaults to Nuxt's generated path
const ROUTES_ALIASES = {
    'index': {
       fr: '/',
       en: '/',
       entities: { page: 'webPage' }
    },
    'contact': {
       fr: '/contact',
       en: '/contact',
       entities: { page: 'webPage' }
    },
    'about_us': {
      fr: '/lagence',
      en: '/lagence',
      entities: { page: 'webPage' }
    },
    'page': {
       fr: '/:page',
       en: '/:page',
       entities: { page: 'webPage' }
    },
    'news': {
        fr: '/actualite',
        en: '/news',
        entities: { }
    },
    'news-category': {
        fr: '/actualite/:category',
        en: '/news/:category',
        entities: { category: 'tag' }
    },
    'news-category-slug': {
        fr: '/actualite/:category/:slug',
        en: '/news/:category/:slug',
        entities: { category: 'tag', slug: 'article' }
    },
    'renting': {
        fr: '/location',
        en: '/renting',
        entities: { }
    },
    'renting-category': {
        fr: '/location/:category',
        en: '/renting/:category',
        entities: { category: 'accommodationType'}
    },
    'renting-category-slug': {
        fr: '/location/:category/:slug',
        en: '/renting/:category/:slug',
        entities: { category: 'accommodationType', slug: 'accommodation' }
    },
    'selling': {
        fr: '/vente',
        en: '/selling',
        entities: { }
    },
    'selling-category': {
        fr: '/vente/:category',
        en: '/selling/:category',
        entities: { category: 'accommodationType' }
    },
    'selling-category-slug': {
        fr: '/vente/:category/:slug',
        en: '/selling/:category/:slug',
        entities: { category: 'accommodationType', slug: 'accommodation' }
    }
}

module.exports = {
  LOCALES,
  DEFAULT_LOCALE,
  I18N,
  ROUTES_ALIASES
}
