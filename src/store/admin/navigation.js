// eslint-disable promise/param-names
const state = {
  isHideMenu: localStorage.getItem('navigation_hide'),
}

const getters = {
  isHideMenu: state => state.isHideMenu,
}

const mutations = {
  'changeMenuState': (state) => {
    if (state.isHideMenu === 'true') {
      state.isHideMenu = 'false'
    } else {
      state.isHideMenu = 'true'
    }
    localStorage.setItem('navigation_hide', state.isHideMenu)
  },
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
}
