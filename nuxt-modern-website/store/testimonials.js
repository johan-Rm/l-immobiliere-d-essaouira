export const state = () => ({
  list: []
})

export const mutations = {
  setList(state, data) {
    state.list = []
    data.forEach(function (value, key) {
        let item = {
          profile_photo_url: value.profile_photo_url,
          author_name: value.author_name,
          relative_time_description: value.relative_time_description,
          text: value.text
        }
        state.list.push(item)
    })
  }
}

export const actions = {
  async getList({commit , context}, params) {
    return import('~/data/testimonials.json').then((data) => {
          commit('setList', data.result.reviews)
      }).catch(error => {
          console.log(error)
          console.log('error store testimonials getList')
      })
  }
}
