/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap')

import Vue from 'vue'
import router from './router'
import App from './App.vue'
import store from './store'
import VueToast from 'vue-toast-notification'
import { ModalPlugin, OverlayPlugin } from 'bootstrap-vue'

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.use(VueToast)
Vue.use(ModalPlugin)
Vue.use(OverlayPlugin)

store.dispatch('setUser').catch()

new Vue({
    router,
    store,
    render: h => h(App),
}).$mount('#app')
