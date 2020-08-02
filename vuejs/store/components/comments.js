import Http from '../../utils/http'
import Config from '../../config'
import errors from "../../utils/errors";
const state = {
  datalist:[],
  valComent:{object_id:'',type:'', component:'', val:false},
  page: null,
  total: null,
  limit: null,
}

const getters = {
  datalist: state => state.datalist,
  valComent: state => state.valComent,
  page: state => state.page,
  pagination: state => {
    return {total: state.total, limit: state.limit, page: state.page} 
  }
}

const actions = {
  find:  ({commit}, payload) => {
    return new Promise((resolve, reject) => {
      Http.post(Config.apiUrl + 'comments', payload )
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
  delete: ({commit, state}, id) => {
    return new Promise((resolve, reject) => {
      Http.get(Config.apiUrl + 'comments/delete?id='+ id) 
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
  addComment:  ({commit}, model )=> {
    return new Promise((resolve, reject) => {
      Http.post(Config.apiUrl + 'comments/create', model)
          .then(resp => {
            resolve(resp)
          })
          .catch(err => {
            if (err.response) {
              commit('app/setError', errors.methods.toString(err.response.data.message), {root: true})
              reject(err.response.data)
            }
          })
    })
  },
  updateComment:  ({commit}, model )=> {
    return new Promise((resolve, reject) => {
      Http.post(Config.apiUrl + 'comments/update', model)
          .then(resp => {
            resolve(resp)
          })
          .catch(err => {
            if (err.response) {
              commit('app/setError', errors.methods.toString(err.response.data.message), {root: true})
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
    setValComment: (state, valComent) => {
      state.valComent.object_id = valComent.object_id
      state.valComent.type = valComent.type
      state.valComent.component = valComent.component
      state.valComent.val = valComent.val
  },
}

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions,
}
