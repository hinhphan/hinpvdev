import api from "@/api/index";

export const authApi = {
    login: (data) => api.post('/login', data),
    logout: () => api.post('/logout'),
    refresh: () => api.post('/refresh-token'),
}
