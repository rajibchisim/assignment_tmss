<template>
  <div
    class="relative flex flex-col w-full min-w-0 mb-6 break-words bg-white rounded shadow-lg"
  >
    <div class="px-4 py-3 mb-0 border-0 rounded-t">
      <div class="flex flex-wrap items-center">
        <div class="relative flex-1 flex-grow w-full max-w-full px-4">
          <h3 class="text-base font-semibold text-left text-gray-700">
            Students
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
            <table-students :rows="initRows" v-on="$listeners"/>
        </div>
        <div class="absolute top-0 w-full h-full pb-8 pl-8 bg-black border bg-opacity-10"
            @click.self="searchShowToggle(false)"
            v-if="search.terms != '' && search.result.length && search.show"
        >
            <table-students :rows="search.result" class="bg-white" @edit="clearSearchAndForwardEditEvent($event)"/>
        </div>
    </div>

  </div>
</template>

<script>
import TableStudents from '@/components/Tables/TableStudents.vue'
import Searchable from '@/mixins/searchable'

export default {
    components:{
        TableStudents
    },
    mixins: [Searchable],
    props: ['parentScope', 'initRows'],
    data() {
        return {
            rows: [],
            search: {
                action: 'student/search',
            }
        }
    },
    watch: {
        parentScope: {
            immediate: true,
            handler() {
                console.log(this.parentScope)
                if(this.parentScope) {
                    if(this.parentScope.department) {
                        this.search.department_id = this.parentScope.department
                    }
                    if(this.parentScope.batch) {
                        this.search.batch_id = this.parentScope.batch
                    }
                }
            }
        },
    },
    methods: {
        clearSearchAndForwardEditEvent(data) {
            this.$emit('edit', data)
            this.clearSearch()
        }
    }
}
</script>

<style>

</style>

