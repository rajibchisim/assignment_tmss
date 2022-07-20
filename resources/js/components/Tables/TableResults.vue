<template>
  <div class="block w-full overflow-x-auto">
      <!-- Projects table -->
      <table class="items-center w-full bg-transparent border-collapse">
        <thead>
          <tr class="text-xs font-semibold text-left uppercase">
            <th class="w-20 px-6 py-3 border border-l-0 border-r-0 border-solid whitespace-nowrap">ID</th>
            <th class="px-6 border border-l-0 border-r-0 border-solid whitespace-nowrap">
                <button class="inline-flex px-4 py-3 gap-x-2 hover:bg-gray-100" @click="$emit('sort', { result: 'gpa' })">
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                        </svg>
                    </span>
                    <span>GPA</span>
                </button>
            </th>
            <th class="px-6 border border-l-0 border-r-0 border-solid whitespace-nowrap" >
                <p class="flex justify-between">
                    <button class="inline-flex px-4 py-3 gap-x-2 hover:bg-gray-100" @click="$emit('sort', { result: 'date' })">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                            </svg>
                        </span>
                        <span>Date</span>
                    </button>
                    <span class="flex items-center font-thin gap-x-2" v-if="!hideDateFilter">
                        <input type="checkbox" @change="$emit('toggleRangeFilter', $event)">
                        <span>
                            <input type="date" class="inline-block px-2 py-1 border" v-model="range.from" @change="$emit('dateRange', { from: $event.target.value })">
                        </span>
                        <span>:</span>
                        <span>
                            <input type="date" class="inline-block px-2 py-1 border" v-model="range.to" @change="$emit('dateRange', { to: $event.target.value })">
                        </span>
                        <button class="" @click.stop="clearRange">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </span>
                </p>
            </th>
          </tr>
        </thead>
        <tbody v-if="initRows">
            <tr v-if="initRows.length == 0">
                <td colspan="3" class="py-4 text-center text-gray-600 align-middle">No results found.</td>
            </tr>
            <tr v-for="(row, index) in initRows" :key="index" class="text-left hover:bg-gray-100 group">
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
    props: ['initRows', 'hideDateFilter'],
    data() {
        return {
            range: {
                active: false,
                to: '',
                from: ''
            }
        }
    },
    methods: {
        clearRange() {
            this.range.to = ''
            this.range.from = ''
            this.$emit('dateRange', 'clear')
        }
    }
}
</script>

<style>

</style>
