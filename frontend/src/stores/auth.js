import { ref } from 'vue'
import { defineStore } from 'pinia'
import { meApi } from '@/api/meApi'
import { authApi } from '@/api/authApi'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  const accessToken = ref(null)
  const refreshToken = ref(null)

  function getAccessToken() {
    return accessToken.value
  }

  function setAccessToken(token) {
    accessToken.value = token
  }

  function setUser(userData) {
    user.value = userData
  }

  function getUser() {
    return user.value
  }

  function setRefreshToken(token) {
    refreshToken.value = token
  }

  function setAuth(data) {
    try {
      setUser(data.user || null)
      setAccessToken(data.access_token || null)
      setRefreshToken(data.refresh_token || null)

    } catch (error) {
      console.error('Error setting auth data:', error)
    }
  }

  function clearAuth() {
    setUser(null)
    setAccessToken(null)
    setRefreshToken(null)
  }

  async function checkLoginStatus() {
    try {
      const res = await meApi.basicInfo()
      const data = res.data.data

      setUser(data.user || null)

      return true;

    } catch (error) {
      console.error('Error checking login status:', error)
      return false
    }
  }

  async function login(credentials) {
    try {
      const res = await authApi.login(credentials)
      const data = res.data.data;

      setAuth(data)

    } catch (error) {
      console.error('Login error:', error)
      throw error
    }
  }

  async function logout(isRedirect = true) {
    try {
      await authApi.logout()
      clearAuth()

      if (isRedirect)
        window.location.href = '/login'

    } catch (error) {
      console.error('Logout error:', error)
      throw error
    }
  }

  async function refresh() {
    try {
      const res = await authApi.refresh()
      const data = res.data.data;

      setAuth(data)

    } catch (error) {
      console.error('Refresh token error:', error)
      throw error
    }
  }

  return {
    login,
    logout,
    refresh,
    setAuth,
    setUser,
    getUser,
    getAccessToken,
    setAccessToken,
    setRefreshToken,
    checkLoginStatus,
  }
})
