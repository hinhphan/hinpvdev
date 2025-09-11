import { ref } from 'vue'
import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', () => {
  const accessToken = ref(null)
  const refreshToken = ref(null)

  function getAccessToken() {
    return accessToken.value
  }

  function setAccessToken(token) {
    accessToken.value = token
  }

  return {
    accessToken,
    refreshToken,
    getAccessToken,
    setAccessToken,
  }
})
