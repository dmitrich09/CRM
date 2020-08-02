
 
const state = {
  datalist:[],
  page: null,
  total: null,
  limit: null,
  form: {}  
}

const getters = {
  datalist: state => state.datalist,
  page: state => state.page,
  pagination: state => {
    return {total: state.total, limit: state.limit, page: state.page}
  },
  form: state => state.form,

}

const actions = {
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
    setForm:  (state, form) => {
      state.form = form 
    },
}

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions,
}
