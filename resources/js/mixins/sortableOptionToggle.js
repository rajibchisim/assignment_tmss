export default {
    data() {
        return {
            /**
             * # Signature
             * sortOptions: {
             *      'groupName': {
             *          cb: callBack,
             *          columns: [
             *              {
             *                  column: 'columnName',
             *                  value: 'columnValue'
             *              }
             *          ]
             *      }
             * }
             */
            sortOptions: {
                callbacks: []
            }
        }
    },
    methods: {
        // Group is usefull when page has several groups or endpoints and wants to sort their columns.
        // That way this mixin can store different groups data and use when neccessary
        sortableOptionToggle(data = {
                group: '',
                column: ''
        }) {
            const groupName = data.group
            const columnName = data.column

            // create group if not exists
            let sortGroup = this.sortOptions[groupName]

            if(sortGroup == undefined) return
            /* {
                this.$set(this.sortOptions, groupName, { columns: []})
                sortGroup = this.sortOptions[groupName]
            } */

            const columnIndex = sortGroup.columns.findIndex(item => item.column == columnName)

            if(columnIndex != -1){
                const columnItem = sortGroup.columns.splice(columnIndex, 1)[0]
                columnItem.value = !columnItem.value
                sortGroup.columns.unshift(columnItem)
                console.log(sortGroup)
            } else {
                sortGroup.columns.unshift({ column: columnName, value: true })

            }

            this.sortOptions[groupName].cb()

        },

        // Add callback for group
        sortableOptionAddGroupCallback(option = { group: '', cb: null}) {
            const groupName = option.group
            const callback = option.cb
            const sortGroup = this.sortOptions[groupName]
            if(sortGroup == undefined) {
                this.$set(this.sortOptions, groupName, { cb: callback, columns: []})
            } else {
                this.$set(sortGroup, 'cb', callback)

            }

        },
        sortableOptionGetGroupQueryArray(groupName) {
            if(this.sortOptions[groupName] == undefined) return null
            const queryArray = []
            this.sortOptions[groupName]['columns'].forEach(item => {
                queryArray.push({
                    column: item.column,
                    value: item.value ? 'desc' : 'asc'
                })
            })

            return queryArray

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
