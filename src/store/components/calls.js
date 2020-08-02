import Http from '../../utils/http'
import Config from '../../config'
import errors from "../../utils/errors"
const state = {
  datalist:[],  
  report:[],
  zadarmacalls:[],
  page: null,
  total: null,
  limit: null,
}

const getters = {
  datalist: state => state.datalist,
  report: state => state.report,
  zadarmacalls: state => state.zadarmacalls,
  page: state => state.page,
  pagination: state => {
    return {total: state.total, limit: state.limit, page: state.page}
  },
 
}

const actions = {
  find:  ({commit}, payload) => {
    return new Promise((resolve, reject) => {
      Http.post(Config.apiUrl + 'calls', payload)
        .then(resp => {
          commit('set', resp.data)
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
  getCallreport:  ({commit}, payload) => {
    return new Promise((resolve, reject) => {
      Http.post(Config.apiUrl + 'calls/callreport', payload)
        .then(resp => {
          commit('setCallreport', resp.data)
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
  getZadarmacalls:  ({commit}, payload) => {
    return new Promise((resolve, reject) => {
      Http.post(Config.apiUrl + 'calls/zadarmacalls', payload)
        .then(resp => {
          commit('setZadarmacalls', resp.data)
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
      Http.get(Config.apiUrl + 'calls/delete?id='+ id)
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
        Http.post(Config.apiUrl + 'calls/update', model)
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
        Http.post(Config.apiUrl + 'calls/create', model)
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

  findAll:  ({commit} ) => {
    return new Promise((resolve, reject) => {
      Http.get(Config.apiUrl + 'calls/getall?')
        .then(resp => {
          commit('set', resp.data)
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
    setForm: (state, form) => {   
      state.form = form
    },
    setCallreport: (state, report) => {
      state.report = report
    },
    setZadarmacalls: (state, zadarmacalls) => {
      state.zadarmacalls = zadarmacalls
    },
}

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions,
}
