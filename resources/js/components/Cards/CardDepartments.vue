<template>
  <div
    class="relative flex flex-col w-full min-w-0 mb-6 break-words bg-white rounded shadow-lg"
  >
    <div class="px-4 py-3 mb-0 border-0 rounded-t">
      <div class="flex flex-wrap items-center">
        <div class="relative flex-1 flex-grow w-full max-w-full px-4">
          <h3 class="text-base font-semibold text-blueGray-700">
            Departments
          </h3>
        </div>
        <div class="relative">
            <input placeholder="Search"
                @input="searchInputHandler"
                @focus="searchShowToggle"
                v-model="search.terms"
                type="text"
                class="w-full px-4 py-1 border rounded-full"
            >
            <span class="absolute text-gray-500 -translate-y-1/2 top-1/2" style="right: 8px">
                <svg class="w-5 h-5 animate-spin" viewBox="0 0 24 24" v-if="search.progress">
                    <!-- <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle> -->
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </span>
            <button class="absolute text-gray-400 -translate-y-1/2 top-1/2 hover:text-red-400 right-2" v-if="search.terms != ''" @click="clearSearch">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </button>
        </div>

      </div>
    </div>
    <div class="relative">
        <div>
            <table-departments v-if="departments" :departments="departments.data" v-on="$listeners"/>
            <div
                class="flex-1 flex-grow w-full max-w-full px-4 my-4 text-right text-gray-500"
            >
                <button class="px-2 py-1 mb-1 mr-1 text-xs font-bold uppercase transition-all duration-150 ease-linear rounded outline-none focus:outline-none hover:bg-gray-100" type="button"
                    @click="loadMoreDepartments($event)"
                    v-if="departments && departments.prev_page_url"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button class="px-2 py-1 mb-1 mr-1 text-xs font-bold uppercase transition-all duration-150 ease-linear rounded outline-none focus:outline-none hover:bg-gray-100" type="button"
                    @click="loadMoreDepartments($event, 1)"
                    v-if="departments && departments.next_page_url"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
        <div class="absolute top-0 w-full h-full pb-8 pl-8 bg-black border bg-opacity-10"
            @click.self="searchShowToggle(false)"
            v-if="search.terms != '' && search.result.length && search.show"
        >
            <table-departments :departments="search.result" class="bg-white"/>
        </div>
    </div>

  </div>
</template>

<script>
import TableDepartments from '@/components/Tables/TableDepartments.vue'
import Searchable from '@/mixins/searchable'


export default {
    components:{
        TableDepartments
    },
    mixins: [Searchable],
    data() {
        return {
            departments: null,
            search: {
                action: 'department/search'
            }
        }
    },
    methods: {
        loadMoreDepartments(event, dir = 0) {
            console.log(dir)
            if(dir == 0 && this.departments.prev_page_url) {
                // previous
                console.log('backward')
                this.$store.dispatch('department/all', {
                    page: this.departments.current_page - 1
                })
                .then(res => {
                    this.departments = res
                })
            } else if(dir == 1 && this.departments.next_page_url){
                // forward
                console.log('forward')
                this.$store.dispatch('department/all', {
                    page: this.departments.current_page + 1
                })
                .then(res => {
                    this.departments = res
                })
            }
        },
        fetchInitData() {
            this.$store.dispatch('department/all')
            .then(res => {
                this.departments = res
            })
        }
    },
    created() {
        this.fetchInitData()
    }
}
</script>

<style>

</style>

