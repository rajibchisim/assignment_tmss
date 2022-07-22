export default {
    data() {
        return {
            search: {
                action: '',
                scopes: {},
                terms: '',
                debounce: null,
                progress: false,
                result: [],
                show: true
            }
        }
    },
    methods: {
        /**
         *
         * @param  { { scopekey: scopevalue... } } scopes
         */
        searchAddScopes(scopes) {
            Object.keys(scopes).forEach(key => {
                this.$set(this.search.scopes, key, scopes[key])
            })
        },
        searchClearScopes() {
            this.search.scopes.splice()
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
            this.$store.dispatch(this.search.action, {
                query: {
                    terms,
                    ...this.search.scopes
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
}
