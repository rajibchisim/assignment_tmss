<template>

<div class="container flex items-center h-screen mx-auto">
    <div class="flex flex-grow max-w-sm mx-auto overflow-hidden bg-white rounded-lg shadow-lg lg:max-w-4xl">
        <div class="bg-cover lg:block lg:w-1/2" style="background-image:url('/images/hope-house-press-leather-diary-studio-IOzk8YKDhYg-unsplash.jpg')"></div>

        <div class="w-full px-6 py-8 md:px-8 lg:w-1/2">
            <h2 class="text-2xl font-semibold text-center text-gray-700">Student Portal</h2>

            <p class="text-xl text-center text-gray-600">Welcome back!</p>

            <div class="flex items-center justify-between mt-4">
                <span class="w-1/5 border-b lg:w-1/4"></span>

                <p class="text-xs text-center text-gray-500 uppercase hover:underline">Enter your credincials</p>

                <span class="w-1/5 border-b lg:w-1/4"></span>
            </div>

            <form @submit.prevent="login" class="mt-10">
                <div class="mt-4">
                    <label class="block mb-2 text-sm font-medium text-gray-600" for="LoggingEmailAddress">Email Address</label>
                    <input name="email" id="LoggingEmailAddress" class="block w-full px-4 py-2 text-gray-700 bg-white border rounded-md focus:border-blue-400 focus:ring-opacity-40 focus:outline-none focus:ring focus:ring-blue-300" type="email"
                    v-model="formData.email" @input="clearErrorMessage">
                </div>
                <div class="mt-4">
                    <div class="flex justify-between">
                        <label class="block mb-2 text-sm font-medium text-gray-600" for="loggingPassword">Password</label>
                    </div>
                    <input id="loggingPassword" name="password" class="block w-full px-4 py-2 text-gray-700 bg-white border rounded-md focus:border-blue-400 focus:ring-opacity-40 focus:outline-none focus:ring focus:ring-blue-300" type="password"
                    v-model="formData.password" @input="clearErrorMessage">
                </div>
                <div class="flex items-center justify-center h-4 mt-2">
                    <p v-if="errorMessage"  class="text-xs text-center text-red-500">{{ errorMessage }}</p>
                </div>
                <div class="mt-6">
                    <button type="submit" class="flex items-center justify-center w-full px-4 py-2 tracking-wide text-white transition-colors duration-200 transform bg-gray-700 rounded hover:bg-gray-600 focus:outline-none focus:bg-gray-600">
                        <span class="relative">
                            <span class="absolute -translate-y-1/2 -left-8 top-1/2">
                                <svg class="w-5 h-5 animate-spin" viewBox="0 0 24 24" v-if="progress">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                            Login
                        </span>
                    </button>
                </div>
            </form>

            <div class="flex items-center justify-between mt-8">
                <span class="w-1/5 border-b md:w-1/4"></span>

                <router-link :to="{ name: 'register' }" class="text-xs text-gray-500 uppercase hover:underline">or sign up</router-link>

                <span class="w-1/5 border-b md:w-1/4"></span>
            </div>
        </div>
    </div>
</div>

</template>

<script>
export default {
    data() {
        return {
            progress: false,
            errorMessage: null,
            formData: {
                email: '',
                password: ''
            },
            department: {
                name: '',
                slug: ''
            }
        }
    },
    methods: {
        clearErrorMessage() {
            this.errorMessage = null
        },
        login() {
            this.progress = true
            this.$store.dispatch('auth/login', this.formData)
                .then(res => {
                    this.progress = false
                    this.$store.commit('auth/timeout', false)
                    this.$router.push(this.$store.getters['auth/loginintent'])
                })
                .catch(errorMessage => {
                    this.errorMessage = errorMessage
                    this.progress = false
                })
        },
    }
}
</script>

<style>

</style>
