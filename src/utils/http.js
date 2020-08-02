import axios from 'axios'

const Http = axios.create({
  withCredentials: true
})

const httpOnFulfilled = response => {
  if ([200, 201].includes(response.status)) {
    return Promise.resolve(response.data)
  } else if  ([401].includes(response.status)) {
    this.$store.auth.commit('setIsAuthenticated', false)
  }else {
    return Promise.reject(response.data)
  }
}

const httpOnReject = error => {
  if (error.response) {
    // Request made and server responded
    console.log(error.response.data);
    console.log(error.response.status);
  } else if (error.request) {
    console.log(error.request);
  } else {
    console.log('Error', error.message);
  }
  return Promise.reject(error)
}

Http.interceptors.response.use(httpOnFulfilled, httpOnReject) // domain written in config/index.js

export const createCancelToken = () => axios.CancelToken.source()

export const isCancel = error => axios.isCancel(error)

export default Http
