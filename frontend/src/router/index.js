import { createRouter, createWebHistory } from 'vue-router'
import Login from '../pages/Login.vue'
import Orders from '../pages/Orders.vue'
import DefaultLayout from '../pages/DefaultLayout.vue'
import { useAuthStore } from '../stores/auth'

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            redirect: '/results'
        },
        {
            path: '/login',
            name: 'Login',
            component: Login,
            meta: { requiresAuth: false }
        },
        {
            path: '/results',
            component: DefaultLayout,
            meta: { requiresAuth: true },
            children: [
                {
                    path: '',
                    name: 'Orders',
                    component: Orders
                }
            ]
        }
    ]
})

router.beforeEach((to, from, next) => {
    const auth = useAuthStore()
    if (to.meta.requiresAuth && !auth.isAuthenticated) {
        next('/login')
    } else if (to.path === '/login' && auth.isAuthenticated) {
        next('/results')
    } else {
        next()
    }
})

export default router
