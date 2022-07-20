<template>
  <div class="mt-10">
    <div class="flex flex-wrap">
      <div class="w-full px-4 mb-12 xl:w-8/12 xl:mb-0">
        <div class="relative flex flex-col w-full min-w-0 mb-6 break-words bg-white border rounded" style="min-height: 80vh;">
            <div v-if="department" class="p-6">
                <div class="px-4 mb-4">
                    <span class="text-sm">Department quick view</span>
                    <span>{{ department.id + ' | ' +department.name }}</span>
                </div>
                <div class="px-4">
                    <button class="inline-flex items-center text-gray-600 hover:text-gray-700" @click="openBatchAddEditModal(null)">
                        <span>Create Batch</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </button>
                </div>
                <CardBatches v-if="department && department.batches" :departmentId="department" :initRows="department.batches.data" @edit="openBatchAddEditModal"/>


                <div
                    class="flex-1 flex-grow w-full max-w-full px-4 my-4 text-right text-gray-500"
                >
                    <button class="px-2 py-1 mb-1 mr-1 text-xs font-bold uppercase transition-all duration-150 ease-linear rounded outline-none focus:outline-none hover:bg-gray-100" type="button"
                        @click="loadMoreDepartments($event)"
                        v-if="department.batches && department.batches.prev_page_url"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button class="px-2 py-1 mb-1 mr-1 text-xs font-bold uppercase transition-all duration-150 ease-linear rounded outline-none focus:outline-none hover:bg-gray-100" type="button"
                        @click="loadMoreDepartments($event, 1)"
                        v-if="department.batches && department.batches.next_page_url"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>



            </div>
            <div v-else class="absolute inset-0 flex items-center justify-center">
                <p>Select Department from list.</p>
            </div>
        </div>
      </div>
      <div class="w-full px-4 xl:w-4/12">
        <div class="px-4 py-2">
            <button class="inline-flex items-center text-gray-600 hover:text-gray-700" @click="openDepartmentAddEditModal">
                <span>Create Department</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
            </button>
        </div>
        <CardDepartments ref="cDepartment" @open="openDepartment"/>
      </div>
    </div>
    <div class="flex flex-wrap mt-4">
      <div class="w-full px-4 mb-12 xl:w-8/12 xl:mb-0">
        <!-- <card-page-visits /> -->
      </div>
      <div class="w-full px-4 xl:w-4/12">
        <!-- <card-social-traffic /> -->
      </div>
    </div>

    <batch-add-edit-modal :modalData="batchAddEditModalData" v-if="batchAddEditModalData.show" @close="closeBatchAddEditModal" @saveSync="syncBatch"/>
    <DepartmentAddEdit :modalData="departmentAddEditModalData" v-if="departmentAddEditModalData.show" @close="closedepartmentAddEditModal" @saveSync="syncDepartment"/>
  </div>
</template>
<script>
import CardDepartments from "@/components/Cards/CardDepartments.vue";
import CardBatches from '@/components/Cards/CardBatches.vue'
import DepartmentAddEdit from '@/components/Modals/DepartmentAddEdit'
import BatchAddEditModal from "@/components/Modals/BatchAddEdit_v2.vue"
export default {
  name: "dashboard-page",
  components: {
    CardDepartments,
    DepartmentAddEdit,
    CardBatches,
    BatchAddEditModal
  },
  data() {
    return {
        department: null,
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
        batchAddEditModalData: {
                show: false,
                model: null,
                parentModel: null,
                labels: {
                    heading: '',
                    subheading: '',
                    input: 'Batch Name',
                    button: '',

                }
            },
    }
  },
  watch: {
        department: {
            immediate: true,
            handler() {
                if(this.department) {

                }
            }
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
        syncDepartment(model) {
            this.department = model
            this.$refs.cDepartment.fetchInitData()
            this.closedepartmentAddEditModal()
        },
        openDepartment(department) {
            // this.department = department
            this.fetchDepartment(department.id)
        },
        fetchDepartment(id) {
            this.$store.dispatch('department/get', {
                id,
                queryObject: {
                    batches: true,
                }
            })
            .then(res => {
                console.log('department single: ', res)
                this.department = res

            })
        },
        //---------------------
        openBatchAddEditModal(model=null) {
            if(model) {
                this.batchAddEditModalData.labels.heading = 'Edit Batch'
                this.batchAddEditModalData.labels.subheading = 'Update Batch information'
                this.batchAddEditModalData.labels.button = 'Update'

            } else {
                this.batchAddEditModalData.labels.heading = 'Create new Batch'
                this.batchAddEditModalData.labels.subheading = 'Provide Batch information'
                this.batchAddEditModalData.labels.button = 'Create'
            }

            this.batchAddEditModalData.model = model
            this.batchAddEditModalData.parentModel = this.department
            this.batchAddEditModalData.show = true


        },
        closeBatchAddEditModal() {
            this.batchAddEditModalData.show = false
        },

        syncBatch(batch) {
            this.closeBatchAddEditModal()
            this.fetchDepartment(this.department.id)
        },
        //-------------------------


        //-----------------------------
        loadMoreDepartments(event, dir = 0) {
            console.log(dir)
            if(dir == 0 && this.department.batches.prev_page_url) {
                // previous
                console.log('backward')
                /* this.$store.dispatch('batch/all', {
                    page: this.department.batches.current_page - 1
                })
                .then(res => {
                    this.department.batches = res
                }) */

                this.$store.dispatch('department/get', {
                    id: this.department.id,
                    queryObject: {
                        batches: true,
                        page: this.department.batches.current_page - 1
                    }
                })
                .then(res => {
                    console.log('department single: ', res)
                    this.department = res

                })


            } else if(dir == 1 && this.department.batches.next_page_url){
                // forward
                console.log('forward')
                /* this.$store.dispatch('batch/all', {
                    page: this.department.batches.current_page + 1
                })
                .then(res => {
                    this.department.batches = res
                }) */
                this.$store.dispatch('department/get', {
                    id: this.department.id,
                    queryObject: {
                        batches: true,
                        page: this.department.batches.current_page + 1
                    }
                })
                .then(res => {
                    console.log('department single: ', res)
                    this.department = res

                })
            }
        },
        //----------------------------------
  }
};
</script>
