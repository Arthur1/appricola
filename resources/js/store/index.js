import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        userID: null,
        name: null,
    },
    mutations: {
        login(state, data) {
            state.userID = data.id
            state.name = data.name
        },
        logout(state) {
            state.userID = null
            state.name = null
        }
    },
    actions: {
        setUser: function({ commit }) {
            return new Promise((resolve, reject) => {
                axios.get('/api/user').then(res => {
                    commit('login', res.data)
                    resolve(res)
                }).catch(err => {
                    commit('logout')
                    resolve(err)
                })
            })
        }
    },
    getters: {
        isLoggedIn: state => Boolean(state.userID && state.name)
    }
})
