import * as types from './mutation-types'
import { commitTimeout } from '../plugins/commit-timeout'

export const state = () => ({
  item: {
    headline: '',
    alternativeHeadline: '',
    pushForward: '',
    articleResume: '',
    slug: '',
    gallery: {},
    category: {}
  },
  list: []
})

export const mutations = {
  setList(state, data) {
    state.list = data
  },
  setItem(state, data) {
    state.item.headline = data.headline
    state.item.alternativeHeadline = data.alternativeHeadline
    state.item.pushForward = data.pushForward
    state.item.articleResume = data.articleResume
    state.item.slug = data.slug
    state.item.gallery = data.gallery
    state.item.category = data.category
  }
}

const getArticles = () => import('~/data/articles.json').then(r => r.default || r)

export const actions = {
  async getList({commit , context}) {
    await this.$axios.get('/articles')
      .then((response) => {
        commit('setList', response.data['hydra:member'])
      }).catch(error => {
          console.log('error store our_offices getList')
        })
  },
  async getOneBy({commit , context}, params) {
    const slug = params.slug
    delete params.slug
    if('API' === types.DATA_TYPE) {
      await this.$axios.get('/articles/' + slug
      , { 
        params
      })
      .then((response) => {
            commit('setItem', response.data)
      }).catch(error => {
          console.log('error store api our_offices getOneBy')
        })
    } else {
      const result = await getArticles()
      var data = result["hydra:member"].find(r => r.slug === slug)
      commit('setItem', data)

      // import('~/data/articles/' + slug + '.json').then((data) => {
            // commitTimeout(() => {
              // commit('setItem', data.default)
            // })
        // }).catch(error => {
        //     console.log(error)
        //     console.log('error store json our_offices getOneBy')
        // })

    }
  }
}
