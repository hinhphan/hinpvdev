import api from "@/api/index";

export const nodeApi = {
    listNode: () => api.get('/nodes'),
}