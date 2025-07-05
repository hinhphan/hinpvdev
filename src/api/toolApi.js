import api from "@/api/index";

export const toolApi = {
    createPlaceholdImage: (data) => api.post('/placehold-images', data),
    getRandomPlaceholdImage: () => api.get('/placehold-images/random'),
}