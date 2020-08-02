import Http from '../../utils/http'
import Config from '../../config'

const state = {
  apiUrl: Config.apiUrl,
  users: [],
  roles: [],
  permissions: [],
  userStatuses: [],
  currentPageHeader: '',
  articleType:[],
  articleDetailIn:[],
  articleDetailOut:[],
  message: null,
  error: null,
  printBill: null,
  printContract: null,
  search: null
}
const getters = {
  apiUrl: state => state.apiUrl,
  users: state => state.users,
  roles: state => state.roles,
  permissions: state => state.permissions,
  articleType: state => state.articleType,
  articleDetailIn: state => state.articleDetailIn,
  articleDetailOut: state => state.articleDetailOut,
  currentPageHeader: state => state.currentPageHeader,
  userStatuses: state => state.userStatuses,
  message: state => state.message,
  error: state => state.error,
  printBill: state => state.printBill,
  printContract: state => state.printContract,
  search: state => state.search,
}
const mutations = {
  setMessage: (state, message) => {
    state.message = message
  },
  setError: (state, error) => {
    state.error = error
  },
  setCurrentPageHeader: (state, header) => {
    state.currentPageHeader = header
  },
  setUsers: (state, users) => {
    state.users = users
  },
  setRoles: (state, roles) => {
    state.roles = roles
  },
  setPermissions: (state, permissions) => {
    state.permissions = permissions
  },
  setPermission: (state, permission, name) => {
    for (let i = 0 ; i < state.permissions.length; i++) {
      if (state.permissions[i].name == name) {
        state.permissions[i] = permission
      }
    }
  },
  setRole: (state, role, name) => {
    for (let i = 0 ; i < state.roles.length; i++) {
      if (state.roles[i].name == name) {
        state.roles[i] = role
      }
    }
  },
  addPermission: (state, permissions) => {
    state.permissions = permissions
  },
  setUserStatus: (state, statuses) => {
    state.userStatuses = statuses
  },
  setArticleType: (state, types) => {
    state.articleType = types
  },
  setArticleDetailIn: (state, detailsIn) => {  
    state.articleDetailIn = detailsIn
  },
  setArticleDetailOut: (state, detailsOut) => {  
    state.articleDetailOut = detailsOut
  },
  setPrintBill: (state, printBill) => {     
    state.printBill = printBill
  },
  setPrintContract: (state, printContract) => {     
    state.printContract = printContract
  },
  setSearch: (state, search) => {     
    state.search = search
  },
}

const actions = {
  getUsers: ({commit}, query) => {
    return new Promise((resolve) => {
      Http.get(state.apiUrl + '/user?query=' + (query == null ? '' : query) )
        .then(resp => {
          commit('setUsers', resp.data)
          resolve(resp)
        })
    })
  },
  deleteUser:({state}, id) => {
    return new Promise((resolve) => {
      Http.get(state.apiUrl + 'user/delete?id=' + id)
        .then(resp => {
          resolve(resp)
        })
    })
  },
  getRoles: ({commit}) => {
    return new Promise((resolve) => {
      Http.get(state.apiUrl + 'role')
        .then(resp => {
          commit('setRoles', resp.data)
          resolve(resp)
        })
    })
  },
  createRole: ({dispatch}, roleName) => {
    return new Promise((resolve) => {
      Http.post(state.apiUrl + 'role/create', {name: roleName, description: roleName})
        .then(resp => {
          dispatch('getRoles')
          resolve(resp)
        })
    })
  },
  updateRole: ({commit}, role) => {
    let name = role.oldName
    return new Promise((resolve) => {
      Http.post(state.apiUrl + 'role/update', role)
        .then(resp => {
          commit('setRole', resp.data, name)
          resolve(resp)
        })
    })
  },
  deleteRole: ({dispatch}, name) => {
    return new Promise((resolve) => {
      Http.post(state.apiUrl + 'role/delete', {name: name})
        .then(resp => {
          dispatch('getRoles')
          resolve(resp)
        })
    })
  },
  getPermissions: ({commit}) => {
    return new Promise((resolve) => {
      Http.get(state.apiUrl + 'role/permissions')
        .then(resp => {
          commit('setPermissions', resp.data)
          resolve(resp)
        })
    })
  },
  createPermissions: ({dispatch}, permissionName) => {
    return new Promise((resolve) => {
      Http.post(state.apiUrl + 'role/createpermission', {name: permissionName, description: permissionName})
        .then(resp => {
          dispatch('getPermissions')
          resolve(resp)
        })
    })
  },
  updatePermission: ({commit}, permission) => {
    let name = permission.oldName
    return new Promise((resolve) => {
      Http.post(state.apiUrl + 'role/update', permission)
        .then(resp => {
          commit('setPermission', resp.data, name)
          resolve(resp)
        })
    })
  },
  deletePermission: ({dispatch}, name) => {
    return new Promise((resolve) => {
      Http.post(state.apiUrl + 'role/delete', {name: name})
        .then(resp => {
          dispatch('getPermissions')
          resolve(resp)
        })
    })
  },
  setDictionary: ({commit}) => {
    return new Promise((resolve) => {
      Http.get(state.apiUrl + '/auth/dictionary')
        .then(resp => {
          commit('setUserStatus', resp.data.userState)
          commit('setArticleType', resp.data.articleType)
          commit('setArticleDetailIn', resp.data.articleDetailIn)
          commit('setArticleDetailOut', resp.data.articleDetailOut)
          resolve(resp)
        })
    })
  },
}
export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
