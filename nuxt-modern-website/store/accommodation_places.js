export const state = () => ({
    item: {},
    list: []
  })
  
  export const mutations = {
    setList(state, data) {
      state.list = data
    },
    setItem(state, data) {
      state.item = data
    }
  }
  
  export const getters = {
  
  }
  
  export const actions = {
   async getList({commit , context}) {
      await this.$axios.get('/accommodation_places')
        .then((response) => {
          commit('setList', response.data['hydra:member'])
        }).catch(error => {
          console.log(error)
          console.log('error store accommodation_places getList')
        })
    },
    async getOneBy({commit , context}, params) {
      await this.$axios.get('/accommodation_places'
        , { 
          params
        })
        .then((response) => {
          commit('setItem', response.data['hydra:member'][0])
        }).catch(error => {
          console.log(error)
          console.log('error store accommodation_places getOneBy')
        })
    }
  }
  