<template>
  <div>
    <a
      class="block text-blueGray-500"
      href="#pablo"
      ref="btnDropdownRef"
      v-on:click="toggleDropdown($event)"
    >
      <div class="flex items-center">
        <span
          class="inline-flex items-center justify-center w-12 h-12 text-sm text-white rounded-full bg-blueGray-200"
        >
          <img
            alt="..."
            class="w-full align-middle border-none rounded-full shadow-lg"
          />
        </span>
      </div>
    </a>
    <div
      ref="popoverDropdownRef"
      class="z-50 float-left py-2 text-base text-left list-none bg-white rounded shadow-lg min-w-48"
      v-bind:class="{
        hidden: !dropdownPopoverShow,
        block: dropdownPopoverShow,
      }"
    >
      <a
        href="javascript:void(0);"
        class="block w-full px-4 py-2 text-sm font-normal bg-transparent whitespace-nowrap text-blueGray-700"
      >
        Action
      </a>
      <a
        href="javascript:void(0);"
        class="block w-full px-4 py-2 text-sm font-normal bg-transparent whitespace-nowrap text-blueGray-700"
      >
        Another action
      </a>
      <a
        href="javascript:void(0);"
        class="block w-full px-4 py-2 text-sm font-normal bg-transparent whitespace-nowrap text-blueGray-700"
      >
        Something else here
      </a>
      <div class="h-0 my-2 border border-solid border-blueGray-100" />
      <a
        href="javascript:void(0);"
        class="block w-full px-4 py-2 text-sm font-normal bg-transparent whitespace-nowrap text-blueGray-700"
      >
        Seprated link
      </a>
    </div>
  </div>
</template>

<script>
// import { createPopper } from "@popperjs/core";

// import image from "@/assets/img/team-1-800x800.jpg";

export default {
  data() {
    return {
      dropdownPopoverShow: false,
    //   image: image,
    };
  },
  methods: {
    toggleDropdown: function (event) {
    //   event.preventDefault();
    //   if (this.dropdownPopoverShow) {
    //     this.dropdownPopoverShow = false;
    //   } else {
    //     this.dropdownPopoverShow = true;
    //     createPopper(this.$refs.btnDropdownRef, this.$refs.popoverDropdownRef, {
    //       placement: "bottom-start",
    //     });
    //   }
    },
  },
};
</script>
