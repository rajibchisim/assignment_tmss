import Vue from 'vue'
import Vuex from 'vuex'

import Auth from './auth'
import Department from './department'
import Batch from './batch'
import Student from './student'
import Result from './result'


Vue.use(Vuex)




const store = new Vuex.Store({
    state: {
        token: null,
        user: null,
        authTimeout: false,
    },
    mutations: {
        set_token(state, token) {
            state.token = token
            if(token) {
                localStorage.setItem('token', token)
                window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
            } else {
                localStorage.removeItem('token')
                window.axios.defaults.headers.common['Authorization'] = null
                state.user = null
            }
        },
        set_user(state, user) {
            state.user = user
        },
    },
    getters: {
        authenticated(state) {
            return 'hello' + (state.token != null && state.user != null)
        },
    },
    modules: {
        auth: {
            namespaced: true,
            ...Auth
        },
        department: {
            namespaced: true,
            ...Department
        },
        batch: {
            namespaced: true,
            ...Batch
        },
        student: {
            namespaced: true,
            ...Student
        },
        result: {
            namespaced: true,
            ...Result
        }
    },
})

export default store
