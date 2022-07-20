<template>
  <div class="fixed inset-0 bg-black bg-opacity-25">
      <div class="container flex items-center h-full mx-auto">
          <div class="relative w-full max-w-sm mx-auto overflow-hidden bg-white rounded-lg shadow-lg md:p-10 lg:max-w-4xl">
            <!-- DELETE PROMPT -->
            <confirm-delete @confirm="confirm_delete_model" @cancel="cancel_delete_modal" v-if="show_delete_model_modal" class="absolute inset-0 z-20"/>
            <ButtonDelete @prompt="prompt_delete_modal" class="absolute"/>
            <!-- DELETE PROMPT -->
            <button-close v-on="$listeners"/>
            <h2 class="text-2xl font-semibold text-center text-gray-700 dark:text-white">{{ modalData.labels.heading }}</h2>

            <div class="w-full px-6 py-8 md:px-8">
                <div class="flex items-center justify-between mt-4">
                    <span class="w-1/5 border-b dark:border-gray-600 lg:w-1/4"></span>
                    <p class="text-xs text-center text-gray-500 uppercase dark:text-gray-400">{{ modalData.labels.subheading }}</p>
                    <span class="w-1/5 border-b dark:border-gray-400 lg:w-1/4"></span>
                </div>
                <form @submit.prevent="validateForm" class="mt-6">
                    <fieldset :disabled="progress ? true : false">
                        <div class="relative mt-4">
                            <label class="block mb-1 text-sm font-medium text-gray-600 dark:text-gray-200">Department</label>
                            <input class="block w-full px-4 py-1 text-gray-600 bg-white border rounded-md focus:border-blue-400 focus:ring-opacity-40 focus:outline-none focus:ring focus:ring-blue-300" type="text"
                                :value="departmentName"
                                disabled
                            >
                        </div>
                        <div class="relative mt-4">
                            <label class="block mb-1 text-sm font-medium text-gray-600 dark:text-gray-200" for="name">{{ modalData.labels.input }}</label>
                            <input id="name" name="name"  v-model="formData.name" class="block w-full px-4 py-1 text-gray-700 bg-white border rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-400 focus:ring-opacity-40 focus:outline-none focus:ring focus:ring-blue-300" type="text">
                            <span class="absolute right-0 text-xs text-red-500 -bottom-4">{{ errors.first('name') }}</span>
                        </div>
                        <!-- v-validate="'required'" -->
                        <div class="flex items-center justify-center h-4 mt-2">
                            <p v-if="errorMessage"  class="text-xs text-center text-red-500">{{ errorMessage }}</p>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="flex items-center justify-center w-full px-4 py-2 tracking-wide text-white transition-colors duration-200 transform bg-gray-700 rounded hover:bg-gray-600 focus:outline-none focus:bg-gray-600">
                                <span class="relative">
                                    <span class="absolute -translate-y-1/2 -left-8 top-1/2">
                                        <svg class="w-5 h-5 animate-spin" viewBox="0 0 24 24" v-if="progress">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </span>
                                    {{ modalData.labels.button }}
                                </span>
                            </button>
                        </div>
                    </fieldset>
                </form>
            </div>
          </div>
      </div>
  </div>
</template>

<script>
import ButtonClose from '@/components/Modals/buttonClose'
import DeletesModel from '@/mixins/deletesModel'

export default {
    components: {
        ButtonClose
    },
    mixins: [
        DeletesModel
    ],
    props: ['modalData'],
    data() {
        return {
            delete_model_action: 'batch/delete',
            progress: false,
            errorMessage: null,
            formData: {
                name: '',
            }
        }
    },
    watch: {
        modalData: {
            immediate: true,
            handler() {
                if(this.modalData.model) {
                    this.formData.name = this.modalData.model.name
                    this.formData.department_id = this.modalData.parentModel.id
                } else {
                    this.formData.name = ''
                    this.formData.department_id = this.modalData.parentModel.id
                }
            }
        }
    },
    computed: {
        departmentName() {
            try {
                return this.modalData.parentModel.name
            } catch (error) {
                return ''
            }
        }
    },
    methods: {
        validateForm() {
            this.$validator.validate().then(valid => {
                if(!valid) {
                    console.log('Form invalid')

                } else {
                    this.modalData.model ? this.updateModel() : this.createModel()
                }
            })


        },
        createModel() {
            this.progress = true
            this.$store.dispatch('batch/create', {
                ...this.formData
            })
            .then(batch => {
                this.$toast.open({ message: 'Success!', type: 'success'})
                this.progress = false
                this.$emit('saveSync', batch)
            })
            .catch(errors => {
                this.$toast.open({ message: 'Unssucssful!', type: 'error'})
                this.progress = false
                if(typeof errors == 'object' && errors != null) {
                    this.$setErrorsFromResponse(errors)
                } else {
                    this.errorMessage = errors
                }
            })
        },
        updateModel() {
            this.progress = true
            this.$store.dispatch('batch/update', {
                id: this.modalData.model.id,
                payload: this.formData
            })
            .then(batch => {
                this.$toast.open({ message: 'Success!', type: 'success'})
                this.progress = false
                this.$emit('saveSync', batch)
            })
            .catch(errors => {
                this.$toast.open({ message: 'Unssucssful!', type: 'error'})
                this.progress = false
                if(typeof errors == 'object' && errors != null) {
                    this.$setErrorsFromResponse(errors)
                } else {
                    this.errorMessage = errors
                }
            })
        }
    },
}
</script>

<style>

</style>
