import * as types from './mutation-types'

export const state = () => ({
  item: {
    alternativeHeadline: '',
    textResume: '',
    slug: '',
    gallery: {},
    category: {},
    primaryImage: {}
  },
  list: [],
  tags:[]
})

export const mutations = {
  setList(state, data) {
    state.list = data
  },
  setItem(state, data) {
    state.item.alternativeHeadline = data.alternativeHeadline
    state.item.textResume = data.textResume
    state.item.slug = data.slug
    state.item.gallery = data.gallery
    state.item.category = data.category
    state.item.primaryImage = data.primaryImage
  },
  setTags(state, data) {
    state.tags = data
  }
}

const getWebPages = () => import('~/data/web_pages.json').then(r => r.default || r)

export const actions = {
  async getList({commit , context}) {
    await this.$axios.get('/articles')
      .then((response) => {
        commit('setList', response.data['hydra:member'])
      }).catch(error => {
          console.log('error store who_we_are getList')
        })
  },
  async getOneBy({commit , context}, params) {
    const slug = params.slug
    delete params.slug
    if('API' === types.DATA_TYPE) {
      await this.$axios.get('/web_pages/' + slug
      , { 
        params
      })
      .then((response) => {
        commit('setItem', response.data)
      }).catch(error => {
          console.log('error store api who_we_are getOneBy')
        })
    } else {

      const result = await getWebPages()
      var data = result["hydra:member"].find(r => r.slug === slug)
      commit('setItem', data)

      // import('~/data/web_pages/' + slug + '.json').then((data) => {
      //       commit('setItem', data.default)
      //   }).catch(error => {
      //       console.log(error)
      //       console.log('error store json who_we_are getOneBy')
      //   })
    }
  }
}
