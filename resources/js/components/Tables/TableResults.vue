<template>
  <div class="block w-full overflow-x-auto">
      <!-- Projects table -->
      <table class="items-center w-full bg-transparent border-collapse">
        <thead>
          <tr class="text-xs font-semibold text-left uppercase">
            <th class="w-20 px-6 py-3 border border-l-0 border-r-0 border-solid whitespace-nowrap">ID</th>
            <th class="px-6 py-3 border border-l-0 border-r-0 border-solid whitespace-nowrap hover:bg-gray-100" @click="$emit('sort', { result: 'gpa' })">GPA</th>
            <th class="px-6 py-3 border border-l-0 border-r-0 border-solid whitespace-nowrap hover:bg-gray-100" @click="$emit('sort', { result: 'date' })">Date</th>
          </tr>
        </thead>
        <tbody v-if="initRows">
          <tr v-for="(row, index) in initRows.data" :key="index" class="text-left hover:bg-gray-100 group">
            <td
              class="p-4 px-6 text-xs border-t-0 border-l-0 border-r-0"
            >
              {{ row.id }}
            </td>
            <td
              class="p-4 px-6 text-xs border-t-0 border-l-0 border-r-0 whitespace-nowrap"
            >
              {{ row.gpa }}
            </td>
            <td
              class="relative p-4 px-6 text-xs border-t-0 border-l-0 border-r-0 whitespace-nowrap"
            >
              <span>{{ row.date }}</span>
              <span class="absolute inline-flex p-2 text-gray-500 -translate-y-1/2 top-1/2 right-4">
                <button @click="$emit('edit', row)" class="hidden p-1 group-hover:block hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                </button>
                <router-link :to="{ name: 'student', params: { id: row.id } }" class="hidden p-1 group-hover:block hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </router-link>
              </span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
</template>

<script>
export default {
    props: ['initRows']
}
</script>

<style>

</style>
