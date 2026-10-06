import Vue from 'vue'
import { mapState, mapActions } from 'vuex'
import enFR from '../lang/translations/slug/en-fr.json'
import en from '../lang/translations/slug/en.json'
import { LOCALES, ROUTES_ALIASES } from '~~/config'

Vue.mixin({
  data: () => ({
    locales: LOCALES
  }),
  computed: {
    ...mapState('i18n', ['currentLocale'])
  },
  methods: {
    ...mapActions({
      setLocale: 'i18n/setLocale'
    }),
    getLocalizedRoute (route, locale) {
      
      locale = locale || this.$i18n.locale
      // If route parameters is a string, consider it as the route's name
      if (typeof route === 'string') {
        route = { name: route }
      }
      // Build localized route options
      const baseRoute = Object.assign({}, route, { name: `${route.name}-${locale}` })
     
      // Resolve localized route
      const resolved = this.$router.resolve(baseRoute)
      let { href } = resolved
      // Handle exception for homepage
      if (route.name === 'index') {
        href += '/'
      }

      // Cleanup href
      href = (href.match(/^\/\/+$/)) ? '/' : href
      return href
    },
    getRouteBaseName (route) {
      route = route || this.$route
      if (!route.name) {
        return null
      }
      for (let i = LOCALES.length - 1; i >= 0; i--) {
        const regexp = new RegExp(`-${LOCALES[i].code}$`)
        if (route.name.match(regexp)) {
          return route.name.replace(regexp, '')
        }
      }
    },
    getSwitchLocaleRoute (translatelocale) {

      const name = this.getRouteBaseName()
      if (!name) {
        return ''
      }
      const route = {}
      route['name'] = this.$route.name
      if(Object.values(this.$route.params).length >= 1) {
        const params = {}
        Object.entries(this.$route.params).forEach(entry => {

          let key = entry[0]
          let value = entry[1]
          let lastIndex = route['name'].lastIndexOf('-');
          let routeIndex = route['name'].substr(0, lastIndex)
          let entity = ROUTES_ALIASES[routeIndex]['entities'][key]
          let slug = value

          if('en' == this.currentLocale && 'fr' == translatelocale) {
              slug = enFR.hasOwnProperty(entity)? enFR[entity][value]: value
          } else if('fr' == this.currentLocale && 'en' == translatelocale) {
              slug = en.hasOwnProperty(entity)? en[entity][value]: value
          }

          params[key] = slug
        })
        route['params'] = params
      }
      const baseRoute = Object.assign({}, route, { name })
    
      return this.getLocalizedRoute(baseRoute, translatelocale)
    }
  }
})
