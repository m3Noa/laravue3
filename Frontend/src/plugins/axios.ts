import axios, { AxiosInstance, HttpStatusCode } from 'axios'

// export * from '@/plugins/axios/type'

const axiosInstance = buildAxiosInstance()

function registerRequestMiddleware(instance: AxiosInstance) {
  instance.interceptors.request.use((request) => {
    request.headers['Content-Type'] = request.headers['Content-Type'] || 'application/json'
    request.params = { ...request.params, v: Date.now() }
    return request
  })
}

function registerResponseMiddleware(instance: AxiosInstance) {
  instance.interceptors.response.use(
    (response) => {
      response.data.success = true
      return response?.data // simply return the response's data
    },
    async (error) => {
      if (error.response) {
        if (error.response?.status === HttpStatusCode.Unauthorized) {
          // logout()
        }
        error.response.data = {
          ...(error.response.data || {}),
          success: false,
        }
        return error.response?.data
      } else if (error.request) {
        error.request.data = {
          ...(error?.request?.data || {}),
          success: false,
          message: '',
        }
        return error.request?.data
      }
    },
  )
}

export function buildAxiosInstance() {
  const options = {
    baseURL: process.env.VUE_APP_API_BASE_URL || 'https://dummyjson.com',
    headers: {
      'Content-type': 'application/json',
      'Access-Control-Allow-Origin': '*',
    },
  }
  const instance = axios.create(options)
  registerRequestMiddleware(instance)
  registerResponseMiddleware(instance)
  return instance
}

export default axiosInstance
