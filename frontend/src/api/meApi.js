import api from "@/api/index";

export const meApi = {
    basicInfo: () => api.get('/me/basic-info'),
}
