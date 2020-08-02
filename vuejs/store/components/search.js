import Http from '../../utils/http'
import Config from '../../config'
//import errors from "../../utils/errors"
const state = {
  byName:[],
  byPhone:[],
  byInn:[],
  byAddress:[], 
  byContName:[],
  byEmail:[],
  page: null,
  total: null,
  limit: null,
  query:null,
}

const getters = {
  byName: state => state.byName,
  byPhone: state => state.byPhone,
  byInn: state => state.byInn,
  byAddress: state => state.byAddress,
  byContName: state => state.byContName,
  byEmail: state => state.byEmail,
  query: state => state.query,
  page: state => state.page,
  pagination: state => {
    return {total: state.total, limit: state.limit, page: state.page}
  },
 
}  

const actions = {
  findCients:  ({commit, getters}) => {
    return new Promise((resolve, reject) => {
      Http.post(Config.apiUrl + 'clients/search', {search:getters.query } )
        .then(resp => {
          commit('setByName', resp.data.byName)
          commit('setByInn', resp.data.byInn)
          commit('setByAddress', resp.data.byAddress)
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
  findClientsContact:  ({commit, getters}) => {
    return new Promise((resolve, reject) => {
      Http.post(Config.apiUrl + 'clients/searchcontacts', {search: getters.query } )
        .then(resp => {
          commit('setPhone', resp.data.byPhone)
          commit('setEmail', resp.data.byEmail)
          commit('setContName', resp.data.byStaff)
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
    setByName: (state, datalist) => {
        state.byName = datalist
    },
    setByInn: (state, datalist) => {   
      state.byInn = datalist
    },
    setByAddress: (state, datalist) => {
      state.byAddress = datalist
    },
    setContName: (state, datalist) => {
      state.byContName = datalist
    },
    setPhone: (state, datalist) => {
      state.byPhone = datalist
    },
    setEmail: (state, datalist) => {
      state.byEmail = datalist
    },
   
    pagination:(state, pagination) => {
      state.page = pagination.page ? pagination.page : null
      state.total = pagination.total ? pagination.total : null
      state.limit = pagination.limit ? pagination.limit : null
    },
    setForm: (state, form) => {
      state.form = form
    },
    setQuery: (state, query) => {
      state.query = query
    }
}

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions,
}
