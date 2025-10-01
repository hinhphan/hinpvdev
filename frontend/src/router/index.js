import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import authRoutes from "./auth"
import toolRoutes from "./tool"

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import('../views/HomeView.vue'),
      meta: {
        layout: 'MainLayout',
        requiredAuth: true,
      },
    },
    ...authRoutes,
    ...toolRoutes,
    {
      path: '/node',
      name: 'node',
      component: () => import('../views/nodes/NodeView.vue'),
      meta: {
        layout: 'EmptyLayout',
      },
    },
    {
      path: '/:pathMatch(.*)*',
      name: 'NotFound',
      component: () => import('../views/errors/NotFoundView.vue'),
    },
  ],
})

router.beforeEach(async (to) => {
  const { checkLoginStatus } = useAuthStore();
  const requiredAuth = to.meta.requiredAuth || false;

  if (to.name !== "Login" && requiredAuth) {
    const isAuthenticated = await checkLoginStatus();

    if (!isAuthenticated) {
      return { name: "Login", query: { redirect: to.fullPath } };
    }
  }
})

export default router
