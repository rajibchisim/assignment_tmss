<template>
  <div class="mt-10">
    <div class="flex flex-wrap">
      <div class="w-full px-4 mb-12 xl:w-8/12 xl:mb-0">
      </div>
      <div class="w-full px-4 xl:w-4/12">
        <CardDepartments/>
      </div>
    </div>
    <div class="flex flex-wrap mt-4">
      <div class="w-full px-4 mb-12 xl:w-8/12 xl:mb-0">
        <!-- <card-page-visits /> -->
      </div>
      <div class="w-full px-4 xl:w-4/12">
        <!-- <card-social-traffic /> -->
      </div>
    </div>
  </div>
</template>
<script>
import CardDepartments from "@/components/Cards/CardDepartments.vue";
// import CardSocialTraffic from "@/components/Cards/CardSocialTraffic.vue";
import CardTable from '@/components/Cards/CardTable.vue'
export default {
  name: "dashboard-page",
  components: {
    CardDepartments,
    // CardSocialTraffic,
    CardTable
  },
};
</script>
