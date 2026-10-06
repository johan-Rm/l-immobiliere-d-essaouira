export const state = () => ({
    minBudget: 0,
    maxBudget: 0,
    nature: '',
    duration: ''
})

export const mutations = {
    setMinBudget(state, data) {
      state.minBudget = (data > 0)? data: 0
    },
    setMaxBudget(state, data) {
      state.maxBudget = (data > 0)? data: 0
    },
    setNature(state, data) {
      state.nature = data
    },
    setDuration(state, data) {
      state.duration = data
  	}
}
