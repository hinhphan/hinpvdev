import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import { useAuthStore } from '@/stores/auth'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
      meta: {
        layout: 'MainLayout',
        // requiredAuth: true,
      },
    },
    {
      path: '/login',
      name: 'Login',
      component: () => import('../views/auths/LoginView.vue'),
    },
    {
      path: '/tools',
      children: [
        {
          path: '',
          name: 'ListTool',
          component: () => import('../views/tools/ListToolView.vue'),
        },
        {
          path: 'placehold-images',
          name: 'PlaceholdImage',
          component: () => import('../views/tools/PlaceholdImageView.vue'),
        }
      ]
    },
    {
      path: '/:pathMatch(.*)*',
      name: 'NotFound',
      component: () => import('../views/errors/NotFoundView.vue'),
    },
  ],
})

router.beforeEach((to) => {
  const { getAccessToken } = useAuthStore()

  const requiredAuth = to.meta.requiredAuth || false
  const isAuthenticated = getAccessToken() !== null

  if (requiredAuth && !isAuthenticated && to.name !== 'Login') {
    return { name: 'Login' }
  }
})

export default router
