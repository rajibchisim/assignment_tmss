<template>
  <div class="block w-full overflow-x-auto">
      <!-- Projects table -->
      <table class="items-center w-full bg-transparent border-collapse">
        <thead>
          <tr>
            <th
              class="px-6 py-3 text-xs font-semibold text-left uppercase align-middle border border-l-0 border-r-0 border-solid bg-blueGray-50 text-blueGray-500 border-blueGray-100 whitespace-nowrap"
            >
              ID
            </th>
            <th
              class="px-6 py-3 text-xs font-semibold text-left uppercase align-middle border border-l-0 border-r-0 border-solid bg-blueGray-50 text-blueGray-500 border-blueGray-100 whitespace-nowrap"
            >
              Name
            </th>
            <!-- <th
              class="px-6 py-3 text-xs font-semibold text-left uppercase align-middle border border-l-0 border-r-0 border-solid bg-blueGray-50 text-blueGray-500 border-blueGray-100 whitespace-nowrap"
            >
              Unique users
            </th> -->
          </tr>
        </thead>
        <tbody>
          <tr v-for="department in departments" :key="department.id" class="hover:bg-gray-100 group">
            <th
              class="p-4 px-6 text-xs text-left align-middle border-t-0 border-l-0 border-r-0 whitespace-nowrap"
            >
              {{ department.id }}
            </th>
            <td
              class="relative p-4 px-6 text-xs align-middle border-t-0 border-l-0 border-r-0 whitespace-nowrap"
            >
              <span>{{ department.name }}</span>
              <router-link :to="{ name: 'department', params: { id: department.id } }" class="absolute hidden p-2 text-gray-500 -translate-y-1/2 top-1/2 right-4 group-hover:block hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
              </router-link>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
</template>

<script>
export default {
    props: ['departments']
}
</script>

<style>

</style>
