<template>
  <div
    class="relative flex flex-col w-full min-w-0 mt-8 mb-6 break-words bg-white rounded-lg shadow-xl"
  >
    <div class="px-6">
      <div class="mt-8 text-center">
        <h3
          class="text-xl font-semibold leading-normal text-blueGray-700"
        >
          Department of: <span class="capitalize">{{ department ? department.name : ''  }}</span>
        </h3>
        <div class="mb-2 text-blueGray-600">
          <i class="mr-2 text-lg fas fa-university text-blueGray-400"></i>
          University of Computer Science
        </div>
      </div>
      <div class="w-full px-4 text-center">
          <div class="flex justify-center py-4 pt-6 lg:pt-4">
            <div class="p-3 mr-4 text-center">
              <span
                class="block text-xl font-bold tracking-wide uppercase text-blueGray-600"
              >
                {{ total_student }}
              </span>
              <span class="text-sm text-blueGray-400">Students</span>
            </div>
            <div class="p-3 mr-4 text-center">
              <span
                class="block text-xl font-bold tracking-wide uppercase text-blueGray-600"
              >
                {{ total_batch }}
              </span>
              <span class="text-sm text-blueGray-400">Batches</span>
            </div>
          </div>
    </div>



      <div class="py-10 mt-10 text-center border-t border-blueGray-200">
        <div class="flex flex-wrap justify-center">
            <div class="w-full px-4 lg:w-3/12">
                <div>
                    <button @click="openBatchAddEditModal(null)">Create Batch</button>
                </div>
                <div>
                    <button @click="openStudentAddEditModal(null)">Create Student</button>
                </div>
            </div>
          <div class="w-full px-4 lg:w-9/12">
            <card-batches v-if="department && department.batches" :departmentId="department" :initRows="department.batches.data" @editBatch="openBatchAddEditModal"/>
            <button @click.prevent="loadmorebatch" class="font-normal text-gray-600 hover:text-gray-800">
              Load more
            </button>
          </div>
        </div>
      </div>
    </div>

    <batch-add-edit-modal :modalData="batchAddEditModalData" v-if="batchAddEditModalData.show" @close="closeBatchAddEditModal" @saveSync="syncBatch"/>
    <student-add-edit-modal :modalData="studentAddEditModalData" v-if="studentAddEditModalData.show" @close="closeStudentAddEditModal" @saveSync="syncStudent"/>
  </div>
</template>
<script>

import CardBatches from "@/components/Cards/CardBatches.vue"
import BatchAddEditModal from "@/components/Modals/BatchAddEdit.vue"
import StudentAddEditModal from "@/components/Modals/StudentAddEdit.vue"

export default {
    components: {
        CardBatches,
        BatchAddEditModal,
        StudentAddEditModal
    },
    data() {
        return {
            department: null,
            error: null,
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
        };
    },
    computed: {
        total_student() {
            if(this.department) {
                return this.department.students_count
            }
            return 0
        },
        total_batch() {
            if(this.department) {
                return this.department.batches_count
            } else {
                return 0
            }
        }
    },
    watch: {
        department: {
            immediate: true,
            handler() {
                if(this.department) {
                    this.batchAddEditModalData.parentModel = this.department.id
                    this.studentAddEditModalData.parentModel = { id: this.department.id, name: this.department.name }
                }
            }
        }
    },
    created() {
        console.log('created')
        this.fetchDepartment()
    },
    methods: {
        fetchDepartment() {
            this.$store.dispatch('department/get', {
                id: this.$route.params.id,
                queryObject: {
                    batches: true,
                }
            })
            .then(res => {
                console.log('department single: ', res)
                this.department = res

            })
        },
        loadmorebatch() {
            if(this.department.batches.next_page_url) {
                this.$store.dispatch('department/get', {
                    id: this.department.id,
                    queryObject: {
                        batches: true,
                        page: this.department.batches.current_page + 1
                    }
                })
                .then(res => {
                    console.log('department single: ', res.batches.data)
                    this.department.batches.data.push(...res.batches.data)
                    // this.department.batches.data.splice(this.department.batches.data.length, 0, ...res.batches.data)
                    this.department.batches.next_page_url = res.batches.next_page_url
                    this.department.batches.current_page = res.batches.current_page
                })

            }
        },
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
            this.batchAddEditModalData.show = true


        },
        closeBatchAddEditModal() {
            this.batchAddEditModalData.show = false
        },

        // --- student add edit modal
        openStudentAddEditModal(model=null) {
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
        // --- student add edit modal


        syncBatch(batch) {
            this.closeBatchAddEditModal()
            this.fetchDepartment()
        },

        syncStudent(batch) {
            this.closeStudentAddEditModal()
        }
    }
};
</script>
