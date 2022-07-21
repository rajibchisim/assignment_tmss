export default {
    data() {
        return {
            studentAddEditModalData: {
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
        setupStudentAddEdit(parentData = { department: {}, batch: {} }) {
            this.studentAddEditModalData.parentModel = { ...parentData }
        },
        updateStudentAddEdit(parentData = { department: {}, batch: {} }) {
            Object.keys(parentData).forEach(key => {
                this.studentAddEditModalData.parentModel[key] = parentData[key]
            })
        },
        openStudentAddEditModal(model = null) {
            console.log('openStudentAddEditModal')
            if(model) {
                this.studentAddEditModalData.labels.heading = 'Edit Student'
                this.studentAddEditModalData.labels.subheading = 'Update Student information'
                this.studentAddEditModalData.labels.button = 'Update'

            } else {
                this.studentAddEditModalData.labels.heading = 'Create new Student'
                this.studentAddEditModalData.labels.subheading = 'Provide Student information'
                this.studentAddEditModalData.labels.button = 'Create'
            }

            this.studentAddEditModalData.model = model
            this.studentAddEditModalData.show = true

        },
        closeStudentAddEditModal() {
            this.studentAddEditModalData.show = false
        },
    }
}
