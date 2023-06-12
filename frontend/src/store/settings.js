import axios from 'axios'

export default {
  namespaced: true,

  state: () => ({
    email: 'test@example.com',
    password: 'password',
    user: {}
  }),

  mutations: {
    setUser(state, data) {
      state.user = data
    }
  },

  getters: {
    token(state) {
      return state.user.token
    }
  },

  actions: {
    async getSettings({ dispatch }) {
      //localStorage.removeItem('user')
      let userinfo = {}

      if (localStorage.getItem('user')) {
        userinfo = JSON.parse(localStorage.getItem('user'))
      } else {
        userinfo = await dispatch('getUser')
      }

      console.info(userinfo)
      await dispatch('fillUser', userinfo)
    },

    async fillUser({ commit }, params) {
      axios.defaults.headers.common['Authorization'] = `Bearer ${params.token}`
      commit('setUser', params)
    },

    async getUser({ state }) {
      const response = await axios.post('login', {
        email: state.email,
        password: state.password
      })

      localStorage.setItem('user', JSON.stringify(response.data.data))
      return response.data.data
    },

    async removeSettings() {
      localStorage.removeItem('user')
    }
  }
}
