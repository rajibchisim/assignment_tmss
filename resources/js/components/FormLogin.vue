<template>

<div class="flex max-w-sm mx-auto overflow-hidden bg-white rounded-lg shadow-lg dark:bg-gray-800 lg:max-w-4xl">
    <div class="hidden bg-cover lg:block lg:w-1/2" style="background-image:url('https://images.unsplash.com/photo-1606660265514-358ebbadc80d?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=1575&q=80')"></div>

    <div class="w-full px-6 py-8 md:px-8 lg:w-1/2">
        <h2 class="text-2xl font-semibold text-center text-gray-700 dark:text-white">Brand</h2>

        <p class="text-xl text-center text-gray-600 dark:text-gray-200">Welcome back!</p>

        <div class="flex items-center justify-between mt-4">
            <span class="w-1/5 border-b dark:border-gray-600 lg:w-1/4"></span>

            <a class="text-xs text-center text-gray-500 uppercase dark:text-gray-400 hover:underline">Enter your credincials</a>

            <span class="w-1/5 border-b dark:border-gray-400 lg:w-1/4"></span>
        </div>

        <div>
            <div class="mt-4">
                <label class="block mb-2 text-sm font-medium text-gray-600 dark:text-gray-200" for="LoggingEmailAddress">Email Address</label>
                <input name="email" id="LoggingEmailAddress" class="block w-full px-4 py-2 text-gray-700 bg-white border rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring focus:ring-blue-300" type="email"
                v-model="formData.email">
                <!-- @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror -->
            </div>
            <div class="mt-4">
                <div class="flex justify-between">
                    <label class="block mb-2 text-sm font-medium text-gray-600 dark:text-gray-200" for="loggingPassword">Password</label>
                    <!-- @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs text-gray-500 dark:text-gray-300 hover:underline">Forget Password?</a>
                    @endif -->
                </div>
                <input id="loggingPassword" name="password" class="block w-full px-4 py-2 text-gray-700 bg-white border rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring focus:ring-blue-300" type="password"
                v-model="formData.password">
                <!-- @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror -->
            </div>
            <div class="mt-8">
                <button @click="login" type="submit" class="w-full px-4 py-2 tracking-wide text-white transition-colors duration-200 transform bg-gray-700 rounded hover:bg-gray-600 focus:outline-none focus:bg-gray-600">
                    Login
                </button>
            </div>
        </div>

        <div class="flex items-center justify-between mt-4">
            <span class="w-1/5 border-b dark:border-gray-600 md:w-1/4"></span>

            <a href="" class="text-xs text-gray-500 uppercase dark:text-gray-400 hover:underline">or sign up</a>

            <span class="w-1/5 border-b dark:border-gray-600 md:w-1/4"></span>
        </div>
    </div>

    <section class="max-w-4xl p-6 mx-auto bg-white rounded-md shadow-md dark:bg-gray-800">
        <h2 class="text-lg font-semibold text-gray-700 capitalize dark:text-white">Account settings</h2>

        <form>
            <div class="grid grid-cols-1 gap-6 mt-4 sm:grid-cols-2">
                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="username">Department Name</label>
                    <input v-model="department.name" id="username" type="text" class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-200 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-blue-300 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring">
                </div>

                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="emailAddress">Slug</label>
                    <input v-model="department.slug" id="emailAddress" type="email" class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-200 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-blue-300 focus:ring-opacity-40 dark:focus:border-blue-300 focus:outline-none focus:ring">
                </div>


            </div>

            <div class="flex justify-end mt-6">
                <button @click="save" class="px-6 py-2 leading-5 text-white transition-colors duration-200 transform bg-gray-700 rounded-md hover:bg-gray-600 focus:outline-none focus:bg-gray-600">Save</button>
            </div>
        </form>
    </section>
</div>

</template>

<script>
export default {
    data() {
        return {
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
        login() {
            axios.get('/sanctum/csrf-cookie').then(response => {
                if(response.status == 204) {
                    return;
                } else {
                    throw('something went wrong')
                }

            }).then(()=> {
                axios.post('/login', this.formData)
                    .then(res => {
                        console.log(res)
                    })
            })
        }, 
        save() {
            axios.get('/sanctum/csrf-cookie').then(response => {
                if(response.status == 204) {
                    return;
                } else {
                    throw('something went wrong')
                }

            }).then(()=> {
                axios.post('/departments', this.department)
                    .then(res => {
                        console.log(res)
                    })
            })
        }
    }
}
</script>

<style>

</style>
