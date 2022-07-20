<template>
  <footer class="block py-4">
    <div class="container px-4 mx-auto">
      <hr class="mb-4 border-gray-200 border-b-1" />
      <div
        class="flex flex-wrap items-center justify-center md:justify-between"
      >
        <div class="w-full px-4 md:w-4/12">
          <div
            class="py-1 text-sm font-semibold text-center text-gray-500 md:text-left"
          >
            Copyright © {{ date }}
          </div>
        </div>

      </div>
    </div>
  </footer>
</template>
<script>
export default {
  data() {
    return {
      date: new Date().getFullYear(),
    };
  },
};
</script>
