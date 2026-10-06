import * as types from './mutation-types'
import { commitTimeout } from '../plugins/commit-timeout'

export const state = () => ({
  item: {
    slug: '',
    name: ''
  },
  list: []
})

export const mutations = {
  setList(state, data) {
    state.list = []
    data.forEach(function (value, key) {
        let item = {
          name: value.name,
          slug: value.slug
        }
        state.list.push(item)
    })
  },
  setItem(state, data) {
    state.item.slug = data.slug
    state.item.name = data.name
  }
}

export const actions = {
 async getList({commit , context}, params) {
    if('API' === types.DATA_TYPE) {
      await this.$axios.get('/accommodation_types'
      , { 
        params
      })
      .then((response, status) => {
        commitTimeout(() => {
          commit('setList', response.data['hydra:member'])
        })
      }).catch(error => {
        console.log(error)
        console.log('error store api accommodation_types getList')
      }) 
    } else {

      if('true' == params.isLocation) {
        return import('~/data/accommodation_types_location.json').then((data) => {
            commitTimeout(() => {
              commit('setList', data['hydra:member'])
            })
        }).catch(error => {
            console.log(error)
            console.log('error store json accommodation_types getList')
        })
      } else {
        return import('~/data/accommodation_types_vente.json').then((data) => {
            commitTimeout(() => {
              commit('setList', data['hydra:member'])
            })
        }).catch(error => {
            console.log(error)
            console.log('error store json accommodation_types getList')
        })
      }
      
    }
  },
  async getOneBy({commit , context}, params) {
    if('API' === types.DATA_TYPE) {
      await this.$axios.get('/accommodation_types'
        , { 
          params
        })    
        .then((response) => {
          commitTimeout(() => {
            commit('setItem', response.data['hydra:member'][0])
          })
        }).catch(error => {
          console.log(error)
          console.log('error store accommodation_types getOneBy')
        })
    } else {
      return import('~/data/accommodation_types.json').then((result) => {
        var data = result["hydra:member"].find(r => r.slug === params.slug)
        if(typeof data !== 'undefined') {
          commitTimeout(() => {
            commit('setItem', data)
          })
        } else {
          console.log('error store json accommodation_types getOneBy => find()')
        }
      }).catch(error => {
          console.log(error)
          console.log('error store json accommodation_types getOneBy')
      })  
    }
  }
}
