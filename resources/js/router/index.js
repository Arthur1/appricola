import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '../store'
import Index from '../views/Index.vue'
import Login from '../views/Login.vue'
import Register from '../views/Register.vue'
import Home from '../views/Home.vue'
import CreateGame from '../views/CreateGame.vue'
import PlayGame from '../views/PlayGame.vue'
import NotFound from '../views/NotFound.vue'

Vue.use(VueRouter)

const routes = [
    {
        path: '/',
        name: 'index',
        component: Index,
        meta: {
            title: 'TOP',
            requiresAuth: false,
        }
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: {
            title: 'ログイン',
            requiresAuth: false
        }
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
        meta: {
            title: '登録',
            requiresAuth: false
        }
    },
    {
        path: '/home',
        name: 'home',
        component: Home,
        meta: {
            title: 'ホーム',
            requiresAuth: true
        }
    },
    {
        path: '/create_game',
        name: 'create_game',
        component: CreateGame,
        meta: {
            title: 'ゲーム作成',
            requiresAuth: true
        }
    },
    {
        path: '/play_game/:id',
        name: 'play_game',
        component: PlayGame,
        meta: {
            title: 'ゲームプレイ',
            needsAuth: true
        }
    },
    {
        path: '*',
        name: 'not_found',
        component: NotFound,
        meta: {
            title: 'Not Found',
            requiresAuth: false
        }
    }
]

const router = new VueRouter({
    mode: 'history',
    routes,
    scrollBehavior: (to, from, savedPosition) => savedPosition || { x: 0, y: 0 }
})

const nextAuth = (to, from, next) => {
    if (store.getters.isLoggedIn) {
        next()
    } else {
        next({ path: '/login', query: { redirect: to.fullPath }})
    }
  }

router.beforeEach((to, from, next) => {
    document.title = to.meta.title + ' | AgriCompanion'
    next()
})

 router.beforeEach((to, from, next) => {
	if (to.matched.some(record => record.meta.requiresAuth)) {
        if (! store.state.isInitialized) {
            const unwatch = store.watch(state => state.isInitialized, () => {
                unwatch()
                nextAuth(to, from, next)
            })
        } else {
            nextAuth(to, from, next)
        }
    } else {
        next()
    }
})

export default router
