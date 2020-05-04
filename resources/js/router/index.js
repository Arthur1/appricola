import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '../store'

import Index from '../views/Index.vue'
import Login from '../views/Login.vue'
import Register from '../views/Register.vue'
import NotFound from '../views/NotFound.vue'

Vue.use(VueRouter)

const routes = [
    {
        path: '/',
        name: 'index',
        component: Index,
        meta: {
            title: 'TOP',
            needsAuth: false,
        }
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: {
            title: 'ログイン',
            needsAuth: false
        }
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
        meta: {
            title: '登録',
            needsAuth: false
        }
    },
    {
        path: '*',
        name: 'not_found',
        component: NotFound,
        meta: {
            title: 'Not Found',
            needsAuth: false
        }
    }
]

const router = new VueRouter({
    mode: 'history',
    routes,
    scrollBehavior: (to, from, savedPosition) => savedPosition || { x: 0, y: 0 }
})

router.beforeEach((to, from, next) => {
    document.title = to.meta.title + ' | AgriCompanion'
    next()
})

/* router.beforeEach((to, from, next) => {
	if (to.matched.some(record => record.meta.needsAuth) && ! store.getters.isLoggedIn) {
        next({ path: '/login', query: { redirect: to.fullPath }})
    } else {
        next()
    }
}) */

export default router
