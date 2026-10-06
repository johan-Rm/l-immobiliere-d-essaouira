import * as types from './mutation-types'
import { commitTimeout } from '../plugins/commit-timeout'

export const state = () => ({
  item: {
    metaTitle: '',
    metaDescription: '',
    reference: '',
    headline: '',
    description: '',
    floorSize: 0,
    numberOfRooms: 0,
    price: 0,
    labelPrice: "",
    areaSize: 0,
    text: '',
    slug: '',
    ourOpinion: '',
    primaryImage: {},
    secondaryImage: {},
    place: {},
    nature: {},
    type: {},
    realEstateAgent: {},
    rentalPriceType: {},
    label: {},
    details: [],
    pdfs: [],
    amenities: [],
    videos: [],
    gallery: {},
    isActive: false,  
    hideStamp: false
  },
  list: [],
  listRelated: [],
  totalItems: 0,
  itemsPerPage: 10
})
  
export const mutations = {
  setList(state, data) {
    state.list = data
  },
  setListRelated(state, data) {
    state.listRelated = data
  },
  setTotalItems(state, data) {
    state.totalItems = data
  },
  setPagination(state, data) {
    state.pagination = data
  },
  setItem(state, data) {
    // ceci sera à supprimer avec le mapping de configuration
    // et aussi parce que tu à déja le schema from symfony
    state.item.metaTitle = data.metaTitle
    state.item.metaDescription = data.metaDescription
    state.item.reference = data.reference
    state.item.headline = data.headline
    state.item.description = data.description
    state.item.floorSize = data.floorSize
    state.item.numberOfRooms = data.numberOfRooms
    state.item.price = data.price
    state.item.labelPrice = data.labelPrice
    state.item.areaSize = data.areaSize
    state.item.text = data.text
    state.item.slug = data.slug
    state.item.ourOpinion = data.ourOpinion
    state.item.primaryImage = data.primaryImage
    state.item.secondaryImage = data.secondaryImage
    state.item.place = data.place
    state.item.nature = data.nature
    state.item.type = data.type
    state.item.realEstateAgent = data.realEstateAgent
    state.item.rentalPriceType = data.rentalPriceType
    state.item.label = data.label
    state.item.details = data.details
    state.item.pdfs = data.pdfs
    state.item.amenities = data.amenities
    state.item.videos = data.videos
    state.item.gallery = data.gallery
    state.item.isActive = data.isActive
    state.item.hideStamp = data.hideStamp
  }
}

export const getters = {

}

const getAccommodations = () => import('~/data/accommodations.json').then(r => r.default || r)

export const actions = {
 
  async getListRelated({commit}, params) {

    if('API' === types.DATA_TYPE) {
      await this.$axios.get('/accommodations', { params })
        .then((response) => {
              commit('setPagination', response.data['hydra:view'])
              commit('setTotalItems', response.data['hydra:totalItems'])
              commit('setListRelated', response.data['hydra:member'])
        }).catch((error) => {
            console.log('error store accommodations getListRelated')
            console.log(error)
        })
    } else {
      const result = await getAccommodations()
      // import('~/data/accommodations.json').then((result) => {
        var data = result["hydra:member"].filter(r => (r.nature.slug === params['nature.slug'] && r.type.slug === params['type.slug']))
        if(typeof data !== 'undefined') {
          commitTimeout(() => {
            commit('setListRelated', data.slice(0, params.itemsPerPage))
          })
        } else {
          console.log('error store json accommodations getListRelated => filter()')
        }
      // }).catch(error => {
      //       console.log(error)
      //       console.log('error store json accommodations getListRelated')
      //   })  
    }
  },
  async getListBy({commit , context}, params) {
    if (types.DATA_TYPE === 'JSON' && !params.hasOwnProperty('source')) {
      params = { ...params, source: 'JSON' }
    }

    if(!params.hasOwnProperty('source')) {
      await this.$axios.get('/accommodations'
      , { 
        params
      })
      .then((response) => {
        commitTimeout(() => {
          
          commit('setList', response.data['hydra:member'])
        })
        commit('setPagination', response.data['hydra:view'])
        commit('setTotalItems', response.data['hydra:totalItems'])
      }).catch((error) => {
        // console.log(error)
        console.log('error store accommodations getListBy')
        
        if (error.response) {
            /*
             * The request was made and the server responded with a
             * status code that falls out of the range of 2xx
             */
            console.log('error.response.data');
            console.log(error.response.data);
            console.log('error.response.status');
            console.log(error.response.status);
            // console.log(error.response.headers);
        } else if (error.request) {
            /*
             * The request was made but no response was received, `error.request`
             * is an instance of XMLHttpRequest in the browser and an instance
             * of http.ClientRequest in Node.js
             */
            console.log('error.request');
            console.log(error.request);
        } else {
            // Something happened in setting up the request and triggered an Error
            console.log('Error', error.message);
        }
        console.log(error.status);
      })
    } else if('JSON' == params.source) {
      const result = await getAccommodations()
      if(!params.hasOwnProperty('type.slug')) {
        var data = result["hydra:member"].filter(r => (
            r.nature.slug === params['nature.slug']
          )
        )

        if(params.hasOwnProperty('duration.slug')) {
          data = data.filter(r => r.duration.slug === params['duration.slug'])
        }

        if(params.hasOwnProperty('price[gte]')) {
          data = data.filter(r => r.price >= params['price[gte]'])
        }
        if(params.hasOwnProperty('price[lte]')) {
          data = data.filter(r => r.price <= params['price[lte]'])
        }

        const count = data.length
        if(params.hasOwnProperty('page')) {
          // https://stackoverflow.com/questions/42761068/paginate-javascript-array/42761393
          var page_size = 10
          data = data.slice((params['page'] - 1) * page_size, params['page'] * page_size)
        }
        commit('setList', data)
        commit('setPagination', result['hydra:view'])
        commit('setTotalItems', count)
      } else {
        
        var data = result["hydra:member"].filter(r => (
            r.nature.slug === params['nature.slug'] 
            && r.type.slug === params['type.slug']
          )
        )

        if(params.hasOwnProperty('duration.slug')) {
          data = data.filter(r => r.duration.slug === params['duration.slug'])
        }
        
        if(params.hasOwnProperty('price[gte]')) {
          data = data.filter(r => r.price >= params['price[gte]'])
        }
        if(params.hasOwnProperty('price[lte]')) {
          data = data.filter(r => r.price <= params['price[lte]'])
        }
        const count = data.length

        if(params.hasOwnProperty('page')) {
          // https://stackoverflow.com/questions/42761068/paginate-javascript-array/42761393
          var page_size = 10
          data = data.slice((params['page'] - 1) * page_size, params['page'] * page_size)
        }
        commit('setList', data)
        commit('setPagination', result['hydra:view'])
        commit('setTotalItems', count)
      }
    }
  },
  async getOneBy({commit , context}, params) {
    const slug = params.slug
    const type = params.type
    delete params.slug
    delete params.type
    if('API' === types.DATA_TYPE) {
      await this.$axios.get('/accommodations/' + slug
      , { 
        params 
      })
      .then((response) => {
        commitTimeout(() => {
          commit('setItem', response.data)
        })
      }).catch(error => {
        console.log(error)
        console.log('error store api accommodations getOneBy')
      }) 
    } else {
      const result = await getAccommodations()
      var data = result["hydra:member"].find(r => r.slug === slug)
      // import('~/data/accommodations/' + type + '/' + slug + '.json').then((data) => {
      // import('~/data/accommodations/' + slug + '.json').then((data) => {
          commitTimeout(() => {
            commit('setItem', data)
          })
        // }).catch(error => {
        //     console.log(error)
        //     console.log('error store json accommodations getOneBy')
        // })
    }
  },
   async getList({commit , context}) {
    await this.$axios.get('/accommodations')
      .then((response) => {
        commitTimeout(() => {
          commit('setList', response.data['hydra:member'])
        })
        commit('setPagination', response.data['hydra:view'])
        commit('setTotalItems', response.data['hydra:totalItems'])
      }).catch(error => {
        console.log(error)
        console.log('error store accommodations getList')
      })
  }
}
  