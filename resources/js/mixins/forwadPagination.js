export default {
    methods: {
        loadmore(model, action = {name: '', params: {}, query: {} }, responseKey = '' ) {
            if(model.next_page_url) {
                this.$store.dispatch(action.name, {
                    ...action.params,
                    queryObject: {
                        ...action.query,
                        page: model.current_page + 1
                    }
                })
                .then(res => {
                    if(res[responseKey]) {
                        model.data.push(...res[responseKey].data)
                        model.next_page_url = res[responseKey].next_page_url
                        model.current_page = res[responseKey].current_page

                    } else {
                        throw new Error('[Forwardpagination] Response format does not match!')
                    }
                })

            }
        },
    }
}
