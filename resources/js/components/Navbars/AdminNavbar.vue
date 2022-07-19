<template>
  <!-- Navbar -->
  <nav
    class="absolute top-0 left-0 z-10 flex items-center w-full p-4 bg-transparent shadow-lg md:flex-row md:flex-nowrap md:justify-start"
  >
    <div
      class="container flex flex-wrap items-center justify-between w-full px-4 mx-auto mx-autp md:flex-nowrap md:px-10"
    >
      <!-- Brand -->
      <a
        class="text-sm font-semibold lg:inline-block"
        href="javascript:void(0)"
      >
        Welcome, <span class="capitalize">{{ $store.state.user ? $store.state.user.name : '' }}</span>
      </a>
      <!-- User -->
      <ul class="flex-col items-center list-none md:flex-row md:flex">
        <div v-if="$store.state.user">
            <button @click="logout">Logout</button>
        </div>
      </ul>
    </div>
  </nav>
  <!-- End Navbar -->
</template>

<script>

export default {
  methods: {
    logout() {
        this.$store.dispatch('auth/logout')
        this.$router.push('/login')
    }
}
};
</script>
