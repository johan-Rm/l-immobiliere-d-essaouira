import * as types from './mutation-types'
import { commitTimeout } from '../plugins/commit-timeout'

export const state = () => ({
  item: {
    metaTitle: '',
    metaDescription: '',
    url: '',
    name: '',
    headline: '',
    alternativeHeadline: '',
    pushForward: '',
    articleBody: '',
    datePublished: '',
    dateModified: '',
    text: '',
    slug: '',
    blockquote: '',
    blockquoteTitle: '',
    primaryImage: {},
    secondaryImage: {},
    videos: [],
    tags: [],
    components: [],
    gallery: {},
    galleryVertical: {},
    category: {},
    media: {},
    userCreated: {},
    hideStamp: false
  },
  list: []
})

export const mutations = {
  setList(state, data) {
    state.list = []
    data.forEach(function (value, key) {
        let item = {
          headline: value.headline,
          primaryImage: value.primaryImage,
          secondaryImage: value.secondaryImage,
          datePublished: value.datePublished,
          articleResume: value.articleResume,
          category: value.category,
          slug: value.slug
        }
        state.list.push(item)
    })
  },
  setItem(state, data) {
    state.item.metaTitle = data.metaTitle
    state.item.metaDescription = data.metaDescription
    state.item.url = data.url
    state.item.name = data.name
    state.item.headline = data.headline
    state.item.alternativeHeadline = data.alternativeHeadline
    state.item.pushForward = data.pushForward
    state.item.articleBody = data.articleBody
    state.item.datePublished = data.datePublished
    state.item.dateModified = data.dateModified
    state.item.text = data.text
    state.item.slug = data.slug
    state.item.blockquote = data.blockquote
    state.item.blockquoteTitle = data.blockquoteTitle
    state.item.primaryImage = data.primaryImage
    state.item.secondaryImage = data.secondaryImage
    state.item.videos = data.videos
    state.item.tags = data.tags
    state.item.components = data.components
    state.item.gallery = data.gallery
    state.item.galleryVertical = data.galleryVertical
    state.item.category = data.category
    state.item.article = data.article
    state.item.media = data.media
    state.item.userCreated = data.userCreated
    state.item.hideStamp = data.hideStamp
  }
}

export const getters = {

}

const getArticles = () => import('~/data/articles.json').then(r => r.default || r)

export const actions = {
  async getList({commit , context}) {
    await this.$axios.get('/articles')
      .then((response) => {
        commit('setList', response.data['hydra:member'])
      }).catch(error => {
          console.log(error)
          console.log('error store articles getList')
        })
  },
  async getListBy({commit , context}, params) {
    await this.$axios.get('/articles'
      , { 
        params
      })
      .then((response) => {
        commit('setList', response.data['hydra:member'])
      }).catch(error => {
          console.log(error)
          console.log('error store articles getListBy')
        })
  },
  async getOneBy({commit}, params) {
    const slug = params.slug
    delete params.slug
    if('API' === types.DATA_TYPE) {
      await this.$axios.get('/articles/' + slug
      , { 
        params
      }
    ).then((response) => {
          commitTimeout(() => {
            commit('setItem', response.data)
          })
      }).catch(error => {
          console.log(error)
          console.log('error store api articles getOneBy')
        })
    } else {

      const result = await getArticles()
      var data = result["hydra:member"].find(r => r.slug === slug)
      commit('setItem', data)

      // import('~/data/articles/' + slug + '.json').then((data) => {
      //       commitTimeout(() => {
              
      //         commit('setItem', data)
      //       })
      //   }).catch(error => {
      //       console.log(error)
      //       console.log('error store json articles getOneBy')
      //   })
    }
  }
}
