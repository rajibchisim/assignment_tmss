export default {
    actions: {
        login({commit}, data) {
            return new Promise((resolve, reject) => {
                axios.post('/api/auth/login', data)
                .then(res => {
                    console.log(res.data.status)
                    if(res.data.status == 204) {
                        commit('set_token', res.data.auth.token, { root: true })
                        commit('set_user', res.data.auth.user, { root: true })
                        resolve(res.data)
                    } else if(res.data.status == 401) {
                        reject('Credentials does not match. Try again.')
                    } else {
                        reject('Something went wrong! Try again.')
                    }
                })
                .catch(() => {
                    reject('Something went wrong! Try again. catch')
                })

            })
        },
        logout({commit}) {
            axios.post('/api/auth/logout')
            commit('set_token', null, { root: true })
        },
        register({commit}, data) {
            return new Promise((resolve, reject) => {
                axios.post('/api/auth/register', data)
                .then(res => {
                    console.log('response status', res.data.status)
                    console.log('good response', res)
                    if(res.data.status == 200) {
                        commit('set_token', res.data.auth.token, { root: true })
                        commit('set_user', res.data.auth.user, { root: true })
                        resolve(res.data)
                    }
                    else if(res.data.status == 422) {
                        reject(res.data.errors)
                    } else {
                        reject('Something went wrong! Try again.')
                    }
                })
                .catch(()=>{
                    reject('Something went wrong! Try again. CATCH')
                })

            })
        },
        async user({getters, state, commit}) {
            // Token not present in store. check in local storage
            if(!state.token) {
                const localToken = localStorage.getItem('token');
                commit('set_token', localToken, { root: true })
            }

            const response = await axios.get('/api/auth/user')
            console.warn('auth: response .... ',response)
            if(response.data.status == 401) {
                // TODO
            }

            if(response.data.status == 204) {
                commit('set_user', response.data.user, { root: true })
                return response.data.user
            }

            commit('set_token', null, { root: true })
            commit('set_user', null, { root: true })
            return null
        }
    }
}
