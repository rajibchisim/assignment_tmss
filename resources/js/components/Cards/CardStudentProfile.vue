<template>
  <div
    class="relative flex flex-col w-full min-w-0 mt-8 mb-6 text-gray-600 break-words bg-white rounded-lg shadow-md"
  >
    <div class="px-6 pb-6">
      <div class="flex flex-wrap justify-center">
      </div>
      <div class="mt-12 text-center">
        <h3
          class="mb-2 text-xl font-semibold leading-normal capitalize"
        >
          {{ profileData.name }}
        </h3>

        <div class="mt-10 mb-2 text-base">
          <p>Department: {{ profileData.department }}</p>
          <p>Batch: {{ profileData.batch }}</p>
        </div>

      </div>

    </div>
  </div>
</template>
<script>

export default {
    props: ['profileData'],
    data() {
        return {
        };
    },
};
</script>
