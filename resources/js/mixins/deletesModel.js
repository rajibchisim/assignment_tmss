import ConfirmDelete from '@/components/Modals/ConfirmDelete'
import ButtonDelete from '@/components/Modals/ButtonDelete'
export default {
    components: {
        ConfirmDelete,
        ButtonDelete
    },
    data() {
        return {
            show_delete_model_modal: false
        }
    },
    methods: {
        prompt_delete_modal() {
            console.log('show delete modal')
            this.show_delete_model_modal = true
        },
        cancel_delete_modal() {
            this.show_delete_model_modal = false
        },
        confirm_delete_model() {
            this.progress = true
            const url = 'result/delete'
            this.$store.dispatch(url, {
                id: this.modalData.model.id
            })
            .then(result => {
                this.$toast.open({ message: 'Success!', type: 'success'})
                this.progress = false
                this.$emit('deleteSync', this.modalData.model)
                this.$emit('close')
            })
            .catch(errors => {
                this.progress = false
                if(typeof errors == 'object' && errors != null) {
                    this.$setErrorsFromResponse(errors)
                } else {
                    this.errorMessage = errors
                }
            })
        }
    }
}
