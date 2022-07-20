import DepartmentAddEdit from '@/components/Modals/DepartmentAddEdit'
export default {
    components: {
        DepartmentAddEdit
    },
    data() {
        return {
            departmentAddEditModalData: {
                show: false,
                model: null,
                labels: {
                    heading: 'Edit Batch',
                    subheading: 'Update Batch information',
                    input: 'Department Name',
                    button: 'Update',

                }
            },
        }
    },
    methods: {
        openDepartmentAddEditModal(event, model = null) {
            if(model) {
                this.departmentAddEditModalData.labels.heading = 'Edit Department'
                this.departmentAddEditModalData.labels.subheading = 'Update Department information'
                this.departmentAddEditModalData.labels.button = 'Update'

            } else {
                this.departmentAddEditModalData.labels.heading = 'Create new Department'
                this.departmentAddEditModalData.labels.subheading = 'Provide Department information'
                this.departmentAddEditModalData.labels.button = 'Create'
            }

            this.departmentAddEditModalData.model = model
            this.departmentAddEditModalData.show = true
        },
        closedepartmentAddEditModal() {
            this.departmentAddEditModalData.show = false
        },
    }
}
