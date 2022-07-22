<template>
    <div class="mt-4" @click.stop="">
        <!-- <label class="block mb-1 text-sm font-medium text-gray-600 dark:text-gray-200">{{ modalData.labels.input }}</label> -->
        <div class="relative text-sm">
            <input
                v-if="batch == null"
                @input="searchInputHandler"
                @focus="searchShowToggle"
                v-model="search.terms"
                class="block w-full px-4 py-1 text-gray-600 bg-white border rounded-md focus:border-blue-400 focus:ring-opacity-40 focus:outline-none focus:ring focus:ring-blue-300"
                type="text"
            />
            <input
                v-if="batch"
                @input="searchInputHandler"
                @focus="searchShowToggle"
                :value="batch ? `${batch.id} | ${batch.name}` : ''"
                disabled
                class="block w-full px-4 py-1 text-gray-600 bg-white border rounded-md focus:border-blue-400 focus:ring-opacity-40 focus:outline-none focus:ring focus:ring-blue-300"
                type="text"
            />
            <button class="absolute text-gray-400 -translate-y-1/2 top-1/2 hover:text-red-400 right-2" v-if="clearButtonVisibility" @click.stop="clearBatch">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </button>
            <div class="absolute z-10 w-full">
                <div class="w-full p-4 text-sm bg-white border top-8"
                    @click.self="searchShowToggle(false)"
                    v-if="batch == null && search.result.length && search.show"
                >
                    <div v-for="result in search.result" :key="result.id">
                        <button @click.stop="selectBatch($event, result)" class="w-full px-4 py-1 hover:bg-gray-100">{{ result.id + ' | ' + result.name }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
/**
 * params [action] string, vuex action
 * params [queryData] object, eg { department_id, batch_id } .
 */
export default {
    props:['initValue', 'searchAction', 'queryData', 'disableEdit', 'eBus', 'inputName', 'inputValidations'],
    data() {
        return {
            batch: null,
            search: {
                terms: '',
                debounce: null,
                progress: false,
                result: [],
                show: true
            }
        }
    },
    watch: {
        initValue: {
            immediate: true,
            handler() {
                if(this.initValue) {
                    this.batch = this.initValue
                    // this.$emit('valueSelected', this.batch)
                }
            }
        }
    },
    computed: {
        clearButtonVisibility() {
            if(this.disableEdit) {
                return false
            }

            return this.batch || this.search.result.length
        }
    },
    methods: {
        selectBatch(event, batch) {
            console.log('batch selected:', batch)
            // this.formData.batch_id = batch.id
            this.search.show = false
            this.batch = batch
            this.$emit('valueSelected', this.batch)
        },
        clearBatch() {
            this.batch = null
            // this.formData.batch_id = null
            this.clearSearch()
            this.$emit('valueSelected')
        },
        clearDropdown() {
            console.log('clicky ')
            // this.search.show = false
        },
        searchShowToggle(visible=true) {
            this.search.show = visible
        },
        clearSearch() {
            this.search.terms = ''
            this.search.progress = false
            this.search.result = []
            clearTimeout(this.search.debounce)
        },
        searchRows(terms) {
            this.search.progress = true
            this.$store.dispatch(this.searchAction, {
                query: {
                    terms,
                    ...this.queryData
                }
            })
            .then((res)=>{
                this.search.progress = false
                this.search.result = res.data

            })
            .catch(()=>this.search.progress = false)
        },
        searchInputHandler(event) {
            clearTimeout(this.search.debounce)
            this.search.debounce = setTimeout(()=>{
                this.searchRows(event.target.value)
            }, 1000)
        }
    },
    created() {
        if(this.eBus) {
            this.eBus.$on('clear', ()=> {
                this.search.show = false
            })
        }
    }
}
</script>

<style>

</style>
