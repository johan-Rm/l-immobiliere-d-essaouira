import * as types from './mutation-types'
import { commitTimeout } from '../plugins/commit-timeout'

export const state = () => ({
  item: {
    metaTitle: '',
    metaDescription: '',
    headline: '',
    alternativeHeadline: '',
    pushForward: '',
    text: '',
    slug: '',
    blockquote: '',
    blockquoteTitle: '',
    primaryImage: {},
    secondaryImage: {},
    videos: [],
    tags: [],
    components: [],
    breadcrumb: [],
    category: {},
    gallery: {},
    alternativePushForward: {},
      // imageGalleries
    article: {},
    hideStamp: false
      // alternativeHeadline
      // pushForward
      // articleResume
      // category
      // slug
  },
  list: []
})

export const mutations = {
  setPushForward(state, data) {
    state.item.pushForward = data
  },
  setPrimaryImage(state, data) {
    state.item.primaryImage = data
  },
  setSecondaryImage(state, data) {
    state.item.secondaryImage = data
  },
  setBreadcrumb(state, data) {
    state.item.breadcrumb = data
  },
  setAlternativePushForward(state, data) {
    state.item.alternativePushForward = data
  },
  setList(state, data) {
    state.list = data
  },
  setSlug(state, data) {
    state.item.slug = data
  },
  setHeadline(state, data) {
    state.item.headline = data
  },
  setItem(state, data) {
    state.item.metaTitle = data.metaTitle
    state.item.metaDescription = data.metaDescription
    state.item.headline = data.headline
    state.item.alternativeHeadline = data.alternativeHeadline
    state.item.pushForward = data.pushForward
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
    state.item.category = data.category
    state.item.article = data.article
    state.item.hideStamp = data.hideStamp
  }
}

const getWebPages = () => import('~/data/web_pages.json').then(r => r.default || r)

export const actions = {
  async getList({commit , context}) {
    await this.$axios.get('/web_pages')
      .then((response) => {
        commit('setList', response.data['hydra:member'])
      }).catch(error => {
          console.log(error)
          console.log('error store pages getList')
        })
  },
  async getOneBy({commit , context}, params) {
    const slug = params.slug
    delete params.slug
    if('API_WITH_PAGES' === types.DATA_TYPE || 'API' === types.DATA_TYPE) {
      await this.$axios.get('/web_pages/' + slug
      , { 
        params
      })
      .then((response) => {
          commitTimeout(() => {
            commit('setItem', response.data)
          })
      }).catch(error => {
          console.log('error store api pages getOneBy')
        })  
    } else {
      const result = await getWebPages()
      var data = result["hydra:member"].find(r => r.slug === slug)
      commit('setItem', data)
      
      // import('~/data/web_pages/' + slug + '.json').then((data) => {
      //     commitTimeout(() => {
 
      //       commit('setItem', data.default)
      //     })
      //   }).catch(error => {
      //       console.log(error)
      //       console.log('error store json pages getOneBy')
      //   })
    }
  },
  async getOneByDefault({commit , context}, { params1, params2 } ) {
    
        return import('~/data/web_pages.json').then((result) => {
          var data = result["hydra:member"].find(r => r.slug === params1.slug)
          if(typeof data !== 'undefined') {
            commitTimeout(() => {
              commit('setItem', data)
            })
          } else {
            var data = result["hydra:member"].find(r => r.slug === params2.slug)
            commitTimeout(() => {
              commit('setItem', data)
            })
          }

        }).catch(error => {
          console.log(error)
          console.log('error store json params1.slug getOneByDefault')
        })
  }
}
