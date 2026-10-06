export const state = () => ({ token: null, maintenance: false })

export const mutations = {
  setToken(state, token) { state.token = token },
  setMaintenance(state, maintenance) { state.maintenance = maintenance }
}

export const actions = {
  async getToken({ commit }, credentials) {
    if (!credentials || !credentials.username || !credentials.password) {
      throw new Error('Explicit login credentials are required')
    }
    if (process.env.DEMO_MODE === 'true') {
      throw new Error('Authentication is disabled in the demonstration')
    }
    const response = await this.$axios.post('/login_check', credentials)
    commit('setToken', response.data.token)
    this.$axios.setToken(response.data.token, 'Bearer')
  }
}
