import * as types from './mutation-types'
import { commitTimeout } from '../plugins/commit-timeout'

export const state = () => ({
  item: {
    alternativeHeadline: '',
    articleResume: '',
    slug: '',
    gallery: {},
    galleryVertical: {},
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
    state.item.articleResume = data.articleResume
    state.item.articleBody = data.articleBody
    state.item.slug = data.slug
    state.item.gallery = data.gallery
    state.item.galleryVertical = data.galleryVertical
    state.item.category = data.category
    state.item.primaryImage = data.primaryImage
  },
  setTags(state, data) {
    state.tags = data
  }
}

const getArticles = () => import('~/data/articles.json').then(r => r.default || r)

export const actions = {
  async getOneBy({commit , context}, params) {
    const slug = params.slug
    delete params.slug
    if('API' === types.DATA_TYPE) {
      await this.$axios.get('/articles/' + slug
      , { 
        params
      })
      .then((response) => {
          // commitTimeout(() => {
            commit('setItem', response.data)
          // })
      }).catch(error => {
          console.log('error store api about_us getOneBy')
        })
    } else {
      const result = await getArticles()
      var data = result["hydra:member"].find(r => r.slug === slug)
      commit('setItem', data)

      // import('~/data/articles/' + slug + '.json').then((data) => {
      //       // commitTimeout(() => {
      //         commit('setItem', data.default)
      //       // })
      //   }).catch(error => {
      //       console.log(error)
      //       console.log('error store json about_us getOneBy')
      //   })
    }
  }
}
