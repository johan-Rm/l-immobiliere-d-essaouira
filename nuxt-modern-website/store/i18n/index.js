import { ROUTES_ALIASES, DEFAULT_LOCALE, LOCALES } from '~~/config'

import getters from './getters'
import actions from './actions'
import mutations from './mutations'



// Default module's state
const state = () => ({
  locales: LOCALES,
  currentLocale: DEFAULT_LOCALE,
  defaultLocale: DEFAULT_LOCALE,
  routes: ROUTES_ALIASES
})

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}
