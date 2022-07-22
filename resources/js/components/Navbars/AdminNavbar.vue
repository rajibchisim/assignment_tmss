<template>
  <!-- Navbar -->
    <nav class="absolute top-0 left-0 z-10 flex items-center w-full p-4 bg-transparent shadow-lg md:flex-row md:flex-nowrap md:justify-start">
        <div class="container flex flex-wrap items-center justify-between w-full px-4 mx-auto mx-autp md:flex-nowrap md:px-10">
            <div>

                <router-link class="inline-flex items-center text-gray-600 gap-x-2 hover:text-gray-700"
                    :to="{ name: 'home' }"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                    </svg>
                    <span>Dashboard</span>
                </router-link>
            </div>
            <div class="flex items-center gap-x-6">
                <p class="text-sm font-semibold text-gray-700 lg:inline-block">
                    Welcome, <span class="capitalize">{{ $store.state.user ? $store.state.user.name : '' }}</span>
                </p>
                <!-- User -->
                <div v-if="$store.state.user">
                    <button @click="logout" class="inline-flex text-gray-500 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout
                    </button>
                </div>
            </div>
        </div>
    </nav>
  <!-- End Navbar -->
</template>

<script>

export default {
  methods: {
    async logout() {
        await this.$store.dispatch('auth/logout')
        this.$router.replace('/login')
    }
}
};
</script>
