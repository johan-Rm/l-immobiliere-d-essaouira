export const state = () => ({
  article_nosServices: [],
  accommodation_locations: [],
})

export const mutations = {
  setArticleNosServices(state, data) {
    state.article_nosServices = []
    data.forEach(function (value, key) {
          let item = {
            alternativeHeadline: value.alternativeHeadline,
            slug: value.slug
          }
          state.article_nosServices.push(item)
      })
  },
  setAccommodationLocations(state, data) {
    state.accommodation_locations = data
  },
}

export const actions = {
  async getArticleNosServices({commit , context}, params) {
    return import('~/data/menu-articles-nos-services.json').then((data) => {
          commit('setArticleNosServices', data['hydra:member'])
      }).catch(error => {
          console.log(error)
          console.log('error store menu getArticleNosServices')
      })
  },
  async getAccommodationLocations({commit , context}) {
    return import('~/data/accommodation_locations.json').then((data) => {
          commit('setAccommodationLocations', data['hydra:member'])
      }).catch(error => {
          console.log(error)
          console.log('error store menu getAccommodationLocations')
      })
  }
}

