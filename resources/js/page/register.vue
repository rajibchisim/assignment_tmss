<template>
  <div class="container flex items-center h-screen mx-auto">
      <div class="flex flex-grow max-w-sm mx-auto overflow-hidden bg-white rounded-lg shadow-lg dark:bg-gray-800 lg:max-w-4xl">
        <div class="bg-cover lg:block lg:w-1/2" style="background-image:url('/images/hope-house-press-leather-diary-studio-IOzk8YKDhYg-unsplash.jpg')"></div>
        <div class="w-full px-6 py-8 md:px-8 lg:w-1/2">
            <h2 class="text-2xl font-semibold text-center text-gray-700 dark:text-white">TMSS Student portal</h2>
            <p class="text-xl text-center text-gray-600 dark:text-gray-200"></p>
            <div class="flex items-center justify-between mt-4">
                <span class="w-1/5 border-b dark:border-gray-600 lg:w-1/4"></span>
                <p class="text-xs text-center text-gray-500 uppercase dark:text-gray-400 hover:underline">Register new account</p>
                <span class="w-1/5 border-b dark:border-gray-400 lg:w-1/4"></span>
            </div>
            <form @submit.prevent="validateForm" class="mt-6">
                <div class="relative mt-4">
                    <label class="block mb-1 text-sm font-medium text-gray-600 dark:text-gray-200" for="name">Full Name</label>
                    <input id="name" name="name" v-validate="'required'" v-model="formData.name" class="block w-full px-4 py-1 text-gray-700 bg-white border rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring focus:ring-blue-300" type="text">
                    <span class="absolute right-0 text-xs text-red-500 -bottom-4">{{ errors.first('name') }}</span>
                </div>
                <div class="relative mt-4">
                    <label class="block mb-1 text-sm font-medium text-gray-600 dark:text-gray-200" for="email">Email Address</label>
                    <input id="email" name="email" v-validate="'required|email'" v-model="formData.email" class="block w-full px-4 py-1 text-gray-700 bg-white border rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring focus:ring-blue-300" type="email">
                    <span class="absolute right-0 text-xs text-red-500 -bottom-4">{{ errors.first('email') }}</span>
                </div>
                <div class="relative mt-4">
                    <label class="block mb-1 text-sm font-medium text-gray-600 dark:text-gray-200" for="password">Password</label>
                    <input id="password" name="password" v-validate="'required|min:6'" ref="password" v-model="formData.password" class="block w-full px-4 py-1 text-gray-700 bg-white border rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring focus:ring-blue-300" type="password">
                    <span class="absolute right-0 text-xs text-red-500 -bottom-4">{{ errors.first('password') }}</span>
                </div>
                <div class="relative mt-4">
                    <label class="block mb-1 text-sm font-medium text-gray-600 dark:text-gray-200" for="password_confirmation">Confirm Password</label>
                    <input id="password_confirmation" v-validate="'required|confirmed:password'" v-model="formData.password_confirmation" name="password_confirmation" class="block w-full px-4 py-1 text-gray-700 bg-white border rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring focus:ring-blue-300" type="password">
                    <span class="absolute right-0 text-xs text-red-500 -bottom-4">{{ errors.first('password_confirmation') }}</span>
                </div>
                <div class="flex items-center justify-center h-4 mt-2">
                    <p v-if="errorMessage"  class="text-xs text-center text-red-500">{{ errorMessage }}</p>
                </div>
                <div class="mt-4">
                    <button type="submit" class="flex items-center justify-center w-full px-4 py-2 tracking-wide text-white transition-colors duration-200 transform bg-gray-700 rounded hover:bg-gray-600 focus:outline-none focus:bg-gray-600">
                        <span class="relative">
                            <span class="absolute -translate-y-1/2 -left-8 top-1/2">
                                <svg class="w-5 h-5 animate-spin" viewBox="0 0 24 24" v-if="progress">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                            Register
                        </span>
                    </button>
                </div>
            </form>
            <div class="flex items-center justify-between mt-6">
                <span class="w-1/5 border-b dark:border-gray-600 md:w-1/4"></span>
                <router-link :to="{ name: 'login' }" class="text-xs text-gray-500 uppercase dark:text-gray-400 hover:underline">Already registered?</router-link>
                <span class="w-1/5 border-b dark:border-gray-600 md:w-1/4"></span>
            </div>
        </div>
      </div>
  </div>
</template>

<script>
import { Validator } from 'vee-validate';


const dict = {
    attributes: {
        password_confirmation: 'password'
    },
};

Validator.localize('en', dict);


export default {
    data() {
        return {
            progress: false,
            errorMessage: null,
            formData: {
                name: '',
                email: '',
                password: '',
                password_confirmation: ''
            }
        }
    },
    methods: {
        validateForm() {
            this.$validator.validate().then(valid => {
                if(!valid) {
                    console.log('Form invalid')
                } else {
                    console.log('Form ok')
                    this.register()
                }
            })


        },
        register() {
            this.progress = true
            this.$store.dispatch('auth/register', this.formData)
            .then(res => {
                this.progress = false
                this.$router.push({ name: 'home' })
            })
            .catch(errors => {
                this.progress = false
                if(typeof errors == 'object' && errors != null) {
                    this.$setErrorsFromResponse(errors)
                } else {
                    this.errorMessage = errors
                }
            })
        }
    },
}
</script>

<style>

</style>
