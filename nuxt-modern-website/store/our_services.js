import * as types from './mutation-types'
import { commitTimeout } from '../plugins/commit-timeout'

export const state = () => ({
    list:[]
})

export const mutations = {
  setList(state, data) {
    state.list = []
    data.forEach(function (value, key) {
        let item = {
          id: value.id,
          slug: value.slug,
          category: value.category,
          headline: value.headline,
          articleResume: value.articleResume,
          secondaryImage: value.secondaryImage,
          primaryImage: value.primaryImage
        }
        state.list.push(item)
    })
  }
}

export const actions = {
    async getListBy({commit , context}, params) {
        if('API' === types.DATA_TYPE) {
          await this.$axios.get('/articles'
          , { 
            params
          })
          .then((response) => {
            commit('setList', response.data['hydra:member'])
          }).catch(error => {
            console.log('error store our_services getListBy')
          })
        } else {
          return import('~/data/articles.json').then((result) => {
            var data = result["hydra:member"].filter(function(elem) {
              return elem.tags.find(r => r.slug === params['tags.slug'])
            })
          
            if(typeof data !== 'undefined') {
              commitTimeout(() => {
                commit('setList', data)
              })
            } else {
              console.log('error store json our_services getListBy => filter()')
            }
          }).catch(error => {
              console.log(error)
              console.log('error store json our_services getListBy')
          })  
        }

    }
}
