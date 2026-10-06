export const state = () => ({
    item: {},
    list: [],
    mainContact: {}
  })
  
  export const mutations = {
    setList(state, data) {
      state.list = []
      data.forEach(function (value, key) {
          let item = {
            primaryImage: value.primaryImage,
            person: value.person,
            description: value.description
          }
          state.list.push(item)
      })
    },
    setItem(state, data) {
      state.item = data
    },
    setMainContact(state, data) {
      state.mainContact = data
    }
  }
  
  export const actions = {
    async getList({commit , context}) {
      return import('~/data/real-estate-agents.json').then((data) => {
        
            commit('setList', data['hydra:member'])
        }).catch(error => {
            console.log(error)
            console.log('error store team getList')
        })
    },
    async getOneBy({commit , context}, params) {
      await this.$axios.get('/real_estate_agents'
        , { 
          params
        })
        .then((response) => {
          commit('setItem', response.data['hydra:member'][0])
        }).catch(error => {
          console.log(error)
          console.log('error store team getOneBy')
        })
    },
    async getMainContact({commit , context}) {
        return import('~/data/real-estate-agent.json').then((data) => {
          
              commit('setMainContact', data['@id'])
          }).catch(error => {
              console.log(error)
              console.log('error store team getMainContact')
          })
    }
  }
  