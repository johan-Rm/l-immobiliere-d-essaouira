import * as types from './mutation-types'
import { commitTimeout } from '../plugins/commit-timeout'

export const state = () => ({
  logo: {},
  recent_posts: [],
  recent_accommodations: [],
  useful_links:[],
  tags:[],
  article: {
    articleResume: ''
  },
  review: {},
  reviewLinks: {
    mobile: '',
    desktop: ''
  }
})

export const mutations = {
  setLogo(state, data) {
    state.logo = data
  },
  setRecentPosts(state, data) {
    state.recent_posts = data
  },
  setRecentAccommodations(state, data) {
    state.recent_accommodations = []
    data.forEach(function (value, key) {
        let item = {
          type: value.type,
          slug: value.slug,
          primaryImage: value.primaryImage,
          price: value.price,
          nature: value.nature.name,

        }
        state.recent_accommodations.push(item)
    })
  },
  setUsefulLinks(state, data) {
    data.forEach(function (value, key) {
        let item = {
          alternativeHeadline: value.alternativeHeadline,
          slug: value.slug,
          headline: value.headline,
          category: value.category
        }
        state.useful_links.push(item)
    })
  },
  setTags(state, data) {
    data.forEach(function (value, key) {
        let item = {
          name: value.name,
          slug: value.slug
        }
        state.tags.push(item)
    })
  },
  setArticle(state, data) {
    state.article.articleResume = data.articleResume
  },
  setReview(state, data) {
    state.review = data
  },
  setReviewLinks(state, data) {
    state.reviewLinks.mobile = data.mobile
    state.reviewLinks.desktop = data.desktop
  }
}

export const actions = {
  async getRecentPosts({commit , context}, params) {
    await this.$axios.get('/articles'
      , { 
        params
      })
      .then((response) => {
        commit('setRecentPosts', response.data['hydra:member'])
      }).catch(error => {
          console.log('error store footer getRecentPosts')
        })
  },
  async getRecentAccommodations({commit , context}) {

    const params = { 
      'itemsPerPage': 3, 
      'order[id]': 'desc', 
      'isActive': 'true', 
      'label.slug': 'exclusivite' 
    }
     if('API' === types.DATA_TYPE) {
          await this.$axios.get('/accommodations'
          , { 
            params
          })
          .then((response) => {
            // console.log('respoooooonse store footer getRecentAccommodations')
            // console.log(response.data['hydra:member'])
            commit('setRecentAccommodations', response.data['hydra:member'])
        }).catch(error => {
              console.log('error store footer getRecentAccommodations')
            })
        } else {
          return import('~/data/accommodations.json').then((result) => {
            var data = result["hydra:member"].filter(r => ( null !== r.label && r.label.slug === params['label.slug']))
            if(typeof data !== 'undefined') {
              commitTimeout(() => {
                commit('setRecentAccommodations', data.slice(0, params.itemsPerPage))
              })
            } else {
              console.log('error store json footer getRecentAccommodations => filter()')
            }
          }).catch(error => {
              console.log(error)
              console.log('error store json footer getRecentAccommodations')
          })  
        }
  },
  async getUsefulLinks({commit , context}) {
    
      const params = { 
        'tags.slug': 'nos-services', 
        'itemsPerPage': 6, 
        'order[id]': 'desc' 
      }

      if('API' === types.DATA_TYPE) {
          await this.$axios.get('/articles'
            , { 
              params
            })
            .then((response) => {
                commit('setUsefulLinks', response.data['hydra:member'])
          }).catch(error => {
              console.log('error store footer getUsefulLinks')
            })
        } else {
          return import('~/data/articles.json').then((result) => {
            var data = result["hydra:member"].filter(function(elem) {
              return elem.tags.find(r => r.slug === params['tags.slug'])
            })
          
            if(typeof data !== 'undefined') {
              commitTimeout(() => {
                commit('setUsefulLinks', data.slice(0, params.itemsPerPage))
              })
            } else {
              console.log('error store json footer getUsefulLinks => filter()')
            }
          }).catch(error => {
              console.log(error)
              console.log('error store json footer getUsefulLinkssss')
          })  
        }

  },
  async getTags({commit , context}, params ) {
      if('API' === types.DATA_TYPE) {
           await this.$axios.get('/accommodation_types', { params })
            .then((response) => {
              commit('setTags', response.data['hydra:member'])
            }).catch(error => {
                console.log('error store footer getTags')
            })
        } else {
          
          return import('~/data/accommodation_types.json').then((result) => {
            var data = result["hydra:member"].filter(r => r.isLocation === (params.isLocation === 'true'))
            
          
            if(typeof data !== 'undefined') {
              commitTimeout(() => {
                commit('setTags', data)
              })
            } else {
              console.log('error store json footer getTags => find()')
            }
          }).catch(error => {
              console.log(error)
              console.log('error store json footer getTags')
          })  
        }
  },
  async getArticle({commit , context}, params) {
    const slug = params.slug
    delete params.slug
      if('API' === types.DATA_TYPE) {
          await this.$axios.get('/articles/' + slug
            , { 
              params
            })
            .then((response) => {
              
              commit('setArticle', response.data)
            }).catch(error => {
                console.log('error store footer getArticle')
              })
        } else {
          return import('~/data/articles.json').then((result) => {
            var data = result["hydra:member"].find(r => r.slug === slug)
            if(typeof data !== 'undefined') {
              commitTimeout(() => {
                commit('setArticle', data)
              })
            } else {
              console.log('error store json footer getArticle => find()')
            }
          }).catch(error => {
              console.log(error)
              console.log('error store json footer getArticle')
          })  
        }
  },
  async getReview({commit}, params) {
    const slug = params.slug
    delete params.slug
    await this.$axios.get('/articles/' + slug
      , { 
        params
      })
      .then((response) => {
        commit('setReview', response.data)
      }).catch(error => {
          console.log('error store footer getReview')
        })
  },
  async getReviewLinks({commit , context}) {
    return import('~/data/review-links.json').then((data) => {
      
          commit('setReviewLinks', data)
      }).catch(error => {
          console.log('error store footer getReviewLinks')
      })
  }
}
