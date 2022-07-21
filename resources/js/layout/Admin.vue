<template>
  <div>
    <admin-navbar />
    <div class="container mx-auto">
        <div class="relative bg-blueGray-100">
          <div class="pt-12"></div>
          <div class="w-full px-4 mx-auto md:px-10">
            <router-view />
            <footer-admin />
          </div>
        </div>
    </div>
    <!-- <login-modal v-if=""/> -->
  </div>
</template>
<script>
import AdminNavbar from "@/components/Navbars/AdminNavbar.vue"
import FooterAdmin from "@/components/Footers/FooterAdmin.vue"
export default {
  name: "admin-layout",
  components: {
    AdminNavbar,
    FooterAdmin,
  },
};
</script>
