import Vue from 'vue'
import VueRouter from 'vue-router'

import store from './store'

import LoginPage from './page/login.vue'
import RegisterPage from '@/page/register.vue'
import DashboardPage from '@/page/admin/Dashboard.vue'
import AdminLayout from '@/layout/Admin.vue'
import DepartmentPage from '@/page/admin/department.vue'
import BatchPage from '@/page/admin/batch.vue'
import StudentPage from '@/page/admin/student.vue'
import NotFound from '@/page/admin/notFound.vue'

const routes = [
    {   path: '/login',
        name: 'login',
        component: LoginPage,
        meta: {
            guestOnly: true
        }
    },
    {   path: '/register',
        name: 'register',
        component: RegisterPage,
        meta: {
            guestOnly: true
        }
    },
    {   path: '/',
        component: AdminLayout,
        children: [
            {
                path: '',
                name: 'home',
                component: DashboardPage,
            },
            {
                path: 'departments/:id',
                component: DepartmentPage,
                name: 'department'
            },
            {
                path: 'batches/:id',
                component: BatchPage,
                name: 'batch'
            },
            {
                path: 'students/:id',
                component: StudentPage,
                name: 'student'
            },
            {
                path: '*',
                component: NotFound,
            }
        ],
        meta: {
            requiresAuth: true
        }
    },

]





Vue.use( VueRouter )
const router = new VueRouter({
    mode: 'history',
    routes
})


router.beforeEach(async (to, from, next) => {
    // prevent signed in user to visit login
    const user = await store.dispatch('auth/user')

    if(to.matched.some(record => record.meta.guestOnly)){
        if(user) next({ name: 'home' }) // redirect to home
        else next() // allow visit guestonly pages
    }

    // prevent unauthorized user to visit
    if(to.matched.some(record => record.meta.requiresAuth)){

        // Authenticated. allow access
        if(user){
            next()

        }


        // Unauthenticated.
        else {
            console.warn('login intent:', to.fullPath)
            store.commit('auth/loginintent', to)
            next({ name: 'login' })

        }

    }

})


export default router
