export const state = () => ({
    item: {
      phone: '',
      name: '',
      email: '',
      url: '',
      addresses: [],
      primaryImage: {},
      foundingDate: '',
      numberOfProjects: 0
    },
    list:[],
    modules:[],
    filterSets:[],
    headbandIsLoaded: true,
    bodyClass: "",
    closeNavbarClass: "",
    closeClass: "",
    containerResult: false,
    headbandShow: false,
    sliderShow: false
})

export const mutations = {
    setSliderShow(state, data) {
      state.sliderShow = data
    },
    setHeadbandShow(state, data) {
      state.headbandShow = data
    },
    setContainerResult(state, data) {
      state.containerResult = data
    },
    setCloseNavbarClass(state, data) {
      state.closeNavbarClass = data
    },
    setBodyClass(state, data) {
      state.bodyClass = data
    },
    setCloseClass(state, data) {
      state.closeClass = data
    },
    setHeadbandIsLoaded(state, data) {
      state.headbandIsLoaded = data
    },
    setFilterSets(state, data) {
      for (const [key, value] of Object.entries(data)) {
        state.filterSets[key] = value
      }
      
    },
    setComponents(state, data) {
      state.modules = data
    },
    setItem(state, data) {
      state.item.name = data.name
      state.item.phone = data.phone
      state.item.email = data.email
      state.item.url = data.url
      state.item.addresses = data.addresses
      state.item.primaryImage = data.primaryImage
      state.item.foundingDate = data.foundingDate
      state.item.numberOfProjects = data.numberOfProjects
    },
    setList(state, data) {
      state.list = []
      data.forEach(function (value, key) {
          let item = {
            url: value.url,
            slug: value.slug
          }
          state.list.push(item)
      })
    }
}

export const actions = {
    async getFilterSets({commit , context}) {
      return import('~/data/filter_sets.json').then((data) => {
            commit('setFilterSets', data)
        }).catch(error => {
            console.log(error)
            console.log('error store organization getFilterSets')
        })
    },
    async getComponents({commit , context}) {
      return import('~/data/components.json').then((data) => {
            commit('setComponents', data['hydra:member'])
        }).catch(error => {
            console.log(error)
            console.log('error store organization getComponents')
        })
    },
    async getItem({commit , context}) {
      return import('~/data/organization.json').then((data) => {
            commit('setItem', data)
        }).catch(error => {
            console.log(error)
            console.log('error store organization getItem')
        })
    },
    async getListBy({commit , context}, params) {
      return import('~/data/organization-lien-reseau-social.json').then((data) => {
            commit('setList', data['hydra:member'])
        }).catch(error => {
            console.log(error)
            console.log('error store organization getListBy')
        })
    }
}
