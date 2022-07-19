export default {
    data() {
        return {
            sortOptions: {
                callbacks: []
            }
        }
    },
    methods: {
        sortOptionsToggle(data) {
            Object.keys(data).forEach((key) => {
                const keyValue = data[key]
                if(this.sortOptions[key]) {
                    if(this.sortOptions[key][keyValue]) {
                        this.sortOptions[key][keyValue] = !this.sortOptions[key][keyValue]
                    } else {
                        this.sortOptions[key][keyValue] = true
                    }
                } else {
                    this.sortOptions[key] = {}
                    this.sortOptions[key][keyValue] = true
                }

                const groupIndex = this.sortOptions.callbacks.findIndex(item => item.group == key)
                if( groupIndex != -1) {
                    this.sortOptions.callbacks[groupIndex].cb()
                }
            })

        },
        sortOptionsToKeyStingValue(sortGroup) {
            if(!sortGroup) return {}

            const orderByString = {}
            Object.keys(sortGroup).forEach(key => {
                orderByString[key] = sortGroup[key] ? 'desc' : 'asc'
            })

            return orderByString
        }
    }
}
