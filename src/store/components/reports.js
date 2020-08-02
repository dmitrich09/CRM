import Http from '../../utils/http'
import Config from '../../config'
import errors from "../../utils/errors";
const state = {
  datalist:[],
  datalistConverce:[],
  datalistPayrep:[],
  formFromCallreport:[],
  page: null,
  total: null,
  limit: null,
}

const getters = {
  datalist: state => state.datalist,
  datalistConverce: state => state.datalistConverce,
  datalistPayrep: state => state.datalistPayrep,
  formFromCallreport: state => state.formFromCallreport,
  page: state => state.page,
  pagination: state => {
    return {total: state.total, limit: state.limit, page: state.page}  
  }
}

const actions = {
  findConverce:  ({commit}, payload) => {
    return new Promise((resolve, reject) => {
      Http.post(Config.apiUrl + 'funnel/converce', payload)
        .then(resp => {
         commit('setConverce', resp.data)
          commit('pagination', resp.pagination)
          resolve(resp)
        })
        .catch(err => {
          if (err.response) {
            commit('app/setError', err.response.data.message, {root: true})
            reject(err.response.data)
          }
        })
    })
  },
  findPayrep:  ({commit}, payload) => {
    return new Promise((resolve, reject) => {
      Http.post(Config.apiUrl + 'funnel/payrep', payload)
        .then(resp => {
         commit('setPayrep', resp.data)
          commit('pagination', resp.pagination)
          resolve(resp)
        })
        .catch(err => {
          if (err.response) {
            commit('app/setError', err.response.data.message, {root: true})
            reject(err.response.data)
          }
        })
    })
  },
  findGetdok:  ({commit}, payload) => {
    return new Promise((resolve, reject) => {
      Http.post(Config.apiUrl + 'funnel/payrep', payload)
        .then(resp => {
         commit('setPayrep', resp.data)
          commit('pagination', resp.pagination)
          resolve(resp)
        })
        .catch(err => {
          if (err.response) {
            commit('app/setError', err.response.data.message, {root: true})
            reject(err.response.data)
          }
        })
    })
  },
  delete: ({commit, state}, id) => {
    return new Promise((resolve, reject) => {
      Http.get(Config.apiUrl + 'planned/delete?id='+ id) 
        .then((r) => {
          var arr = []
          state.datalist.map((el) => {
            if(el.id != id){
              arr.push(el)
            }
          })
          commit('set', arr)
          resolve(r)
        }).catch(err => {
          if (err.response) {
            commit('app/setError', err.response.data.message, {root: true})
            reject(err)
          }
      })
    })
  },
  save:  ({commit}, model )=> {
    return new Promise((resolve, reject) => {
      if (model.id != null && model.id != 0) {
        Http.post(Config.apiUrl + 'planned/update', model)
          .then(resp => {
            resolve(resp)
          })
          .catch(err => {
            if (err.response) {
              commit('app/setError', errors.methods.toString(err.response.data.message), {root: true})
              reject(err.response.data)
            }
          })
      } else {
        Http.post(Config.apiUrl + 'planned/create', model)
          .then(resp => {
            resolve(resp)
          })
          .catch(err => {
            if (err.response) {
              commit('app/setError', errors.methods.toString(err.response.data.message), {root: true})
              reject(err.response.data)
            }
          })
      }
    })
  },
}

const mutations = {
    set: (state, datalist) => {
        state.datalist = datalist
    },
    pagination:(state, pagination) => {
      state.page = pagination.page ? pagination.page : null
      state.total = pagination.total ? pagination.total : null
      state.limit = pagination.limit ? pagination.limit : null       
    },
    setConverce: (state, converce) => {
      state.datalistConverce = converce
    },
    setPayrep: (state, payrep) => {
      state.datalistPayrep = payrep
    },
    formFromCallreport: (state, payrep) => {
      state.formFromCallreport = payrep
    },
}

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions,
}
