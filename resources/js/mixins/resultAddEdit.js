import ResultAddEdit from '@/components/Modals/ResultAddEdit'

export default {
    components: {
        ResultAddEdit
    },
    data() {
        return {
            resultAddEditModalData: {
                show: false,
                model: null,
                parentModel: null,
                labels: {
                    heading: '',
                    subheading: '',
                    input: 'Batch Name',
                    button: '',

                }
            }
        }
    },
    methods: {
        setupResultAddEdit(parentData = { department: {}, batch: {} }) {
            this.resultAddEditModalData.parentModel = { ...parentData }
        },
        openResultAddEditModal(event, model = null) {
            console.log('openResultAddEditModal')
            if(model) {
                this.resultAddEditModalData.labels.heading = 'Edit Result'
                this.resultAddEditModalData.labels.subheading = 'Update Result information'
                this.resultAddEditModalData.labels.button = 'Update'

            } else {
                this.resultAddEditModalData.labels.heading = 'Create new Result'
                this.resultAddEditModalData.labels.subheading = 'Provide Result information'
                this.resultAddEditModalData.labels.button = 'Create'
            }

            this.resultAddEditModalData.model = model
            this.resultAddEditModalData.show = true

        },
        closeResultAddEditModal() {
            this.resultAddEditModalData.show = false
        },
    }
}
