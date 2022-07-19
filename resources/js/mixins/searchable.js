export default {
    data() {
        return {
            search: {
                action: '',
                department_id: '',
                batch_id: '',
                terms: '',
                debounce: null,
                progress: false,
                result: [],
                show: true
            }
        }
    },
    methods: {
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
            this.$store.dispatch(this.search.action, { terms, department_id: this.search.department_id, batch_id: this.search.batch_id })
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
}
