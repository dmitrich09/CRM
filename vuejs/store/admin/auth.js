import Http from '../../utils/http'
import Config from '../../config'

const state = {
  isAuthenticated: null,
  user: {},
}

const getters = {
  isAuthenticated: state => state.isAuthenticated,
  user: state => state.user
}

const actions = {
  auth: ({commit, dispatch}, LoginForm) => {
    return new Promise((resolve) => {
      commit('app/setError', null, {root: true})
      Http.post(Config.apiUrl + 'auth', LoginForm)
        .then(resp => {
          commit('app/setError', null, {root: true})
          commit('setIsAuthenticated', true)
          dispatch('check')
          resolve(resp)
        })
        .catch(() => {
          commit('app/setError', "Неверный логин и пароль", {root: true})
          commit('setIsAuthenticated', false)
        })
    })
  },
  changePassword: ({commit}, passForm) => {
    return new Promise((resolve) => {
      commit('app/setError', null, {root: true})
      Http.post(Config.apiUrl + 'auth/changepassword', passForm)
        .then(resp => {
          commit('app/setMessage', 'Пароль успешно изменен', {root: true})
          resolve(resp)
        })
        .catch(() => {
          commit('app/setError', "Неверные пароли", {root: true})
        })
    })
  },
  registry: ({commit}, email) => {
    return new Promise((resolve) => {
      Http.post(Config.apiUrl + '/auth/registry', email)
        .then((resp) => {
          commit('app/setMessage', 'Проверьте вашу почту')
          resolve(resp)
        }).catch((err) => {
        commit('app/setError', err, {root: true})
      })
    })
  },
  requestpassword: ({commit}, email) => {
    return new Promise((resolve) => {
      Http.post(Config.apiUrl + '/auth/requestpassword', email)
        .then((resp) => {
          commit('app/setMessage', 'Проверьте вашу почту')
          resolve(resp)
        })
        .catch((err) => {
          commit('app/setError', err.response.data.message, {root: true})
        })
    })
  },
  check: ({commit}) => {
    return new Promise((resolve, reject) => {
      Http.get(Config.apiUrl + 'auth/check')
        .then(resp => {
          commit('setIsAuthenticated', true)
          commit('setUser', resp.data)
          resolve(resp)
        })
        .catch(err => {
          if (err.response) {
            commit('setIsAuthenticated', false)
            reject('error')
          }
        })
    })
  },
  logout: ({commit, dispatch}) => {
    return new Promise((resolve) => {
      Http.get(Config.apiUrl + 'logout', {withCredentials: true})
        .then(resp => {
          commit('setIsAuthenticated', false)
          dispatch('check')
          resolve(resp)
        })
    })
  },
}


const mutations = {
  setIsAuthenticated: (state, isAuthenticated) => {
    state.isAuthenticated = isAuthenticated
  },
  setUser: (state, user) => {
    state.user = user
  },
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
}
