import { useAuthStore } from '@/stores/auth'
import axios from 'axios'

let isRefreshing = false
let failedQueue = []

function processQueue(error, token = null) {
  failedQueue.forEach(prom => {
    if (error) prom.reject(error)
    else prom.resolve(token)
  })
  failedQueue = []
}

const api = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL || 'http://127.0.0.1:8000/api/v1',
  timeout: import.meta.env.VITE_API_TIMEOUT || 30000,
  withCredentials: true,
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
    'X-Localization': import.meta.env.VITE_APP_LOCALE || 'vi',
  }
})

// Add a request interceptor
api.interceptors.request.use(
  function (config) {
    // Access token handling
    const { getAccessToken } = useAuthStore()
    const accessToken = getAccessToken()

    if (accessToken) {
      config.headers.Authorization = `Bearer ${accessToken}`
    }

    // Handle FormData - remove Content-Type to let axios set it automatically
    if (config.data instanceof FormData) {
      delete config.headers['Content-Type']
    }

    return config
  },
  function (error) {
    return Promise.reject(error)
  },
)

// Add a response interceptor
api.interceptors.response.use(
  function (response) {
    return response
  },
  async function (error) {
    const originalRequest = error.config
    const { refresh, logout } = useAuthStore()

    // Refresh token handling
    if (error.response?.status === 401 && !originalRequest._retry) {
      if (isRefreshing) {
        return new Promise((resolve, reject) => {
          failedQueue.push({ resolve, reject })
        }).then(() => api(originalRequest))
      }

      originalRequest._retry = true
      isRefreshing = true

      try {
        await refresh()
        processQueue(null)
        return api(originalRequest)
        
      } catch (refreshError) {
        processQueue(refreshError, null)
        await logout(false)
        return Promise.reject(refreshError)

      } finally {
        isRefreshing = false
      }
    }

    return Promise.reject(error)
  },
)

export default api
