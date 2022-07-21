<template>
  <div
    class="relative flex flex-col w-full min-w-0 mt-8 mb-6 break-words bg-white rounded-lg shadow-xl"
  >
    <div class="px-6">
      <div class="relative mt-6 text-center">
            <h3
            class="text-xl font-semibold leading-normal"
            >
            Department of: <span class="capitalize">{{ department ? department.name : ''  }}</span>
            </h3>
            <div class="mb-2 text-blueGray-600">University of Computer Science</div>

            <link-back-arrow-url :route="{ name: 'home' }" />

            <button-edit-pen @click="openDepartmentAddEditModal(null, department)" />

      </div>
      <div class="w-full px-4 text-center">
          <div class="flex justify-center py-2 pt-2 lg:pt-4">
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



      <div class="py-10 mt-6 text-center border-t border-blueGray-200">
        <div class="flex flex-wrap justify-center">
            <div class="w-full px-4 lg:w-6/12">
                <div class="px-4 mb-2 text-left">
                    <button class="inline-flex items-center text-gray-600 hover:text-gray-700" @click="prepareStudentAddEditModal(null)">
                        <span>Create Student</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </button>
                </div>
                <card-students v-if="students" :initRows="students.data" :parentScope="{ department: this.department.id }" @edit="prepareStudentAddEditModal"/>
            </div>
          <div class="w-full px-4 lg:w-6/12">
            <div class="px-4 mb-2 text-left">
                <button class="inline-flex items-center text-gray-600 hover:text-gray-700" @click="openBatchAddEditModal(null)">
                    <span>Create Batch</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </button>
            </div>
            <card-batches v-if="department && department.batches" :departmentId="department" :initRows="department.batches.data" @edit="openBatchAddEditModal"/>
            <button
                v-if="department && department.batches && department.batches.next_page_url"
                @click.prevent="loadmore(department.batches, { name: 'department/get', params: { id: department.id }, query: { batches: true } }, 'batches')" class="font-normal text-gray-600 hover:text-gray-800">
              Load more
            </button>
          </div>
        </div>
      </div>
    </div>

    <batch-add-edit-modal
        v-if="batchAddEditModalData.show"
        :modalData="batchAddEditModalData"
        @close="closeBatchAddEditModal"
        @saveSync="syncBatch"
        @deleteSync="syncBatchDelete"
    />
    <student-add-edit-modal
        :modalData="studentAddEditModalData"
        v-if="studentAddEditModalData.show"
        @close="closeStudentAddEditModal"
        @saveSync="syncStudent"
        :disableBatch="false"
        @deleteSync="syncStudentDelete"
    />
    <DepartmentAddEdit
        :modalData="departmentAddEditModalData"
        v-if="departmentAddEditModalData.show"
        :enableDelete="true"
        @close="closedepartmentAddEditModal"
        @saveSync="syncDepartment"
        @deleteSync="syncDepartmentDelete"
    />
  </div>
</template>
<script>
import ForwardPagination from '@/mixins/forwadPagination'
import SortableOptionToggle from '@/mixins/sortableOptionToggle'
import ModalDepartmentAddEdit from '@/mixins/modalDepartmentAddEdit'


import CardBatches from "@/components/Cards/CardBatches.vue"
import CardStudents from '@/components/Cards/CardStudents.vue'
import BatchAddEditModal from "@/components/Modals/BatchAddEdit_v2.vue"

import StudentAddEditModal from "@/components/Modals/StudentAddEdit_v2.vue"
import StudentAddEditMixin from '@/mixins/studentAddEditModal'


export default {
    components: {
        CardBatches,
        CardStudents,
        BatchAddEditModal,
        StudentAddEditModal
    },
    mixins: [
        ForwardPagination,
        SortableOptionToggle,
        StudentAddEditMixin,
        ModalDepartmentAddEdit
    ],
    data() {
        return {
            department: null,
            students: null,
            total_student: 0,
            total_batch: 0,
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
        };
    },
    watch: {
        department: {
            immediate: true,
            handler() {
                if(this.department) {
                    this.batchAddEditModalData.parentModel = { id: this.department.id, name: this.department.name }

                    this.setupStudentAddEdit({
                        department: { id: this.department.id, name: this.department.name }
                    })


                    this.fetchStudents()
                }
            }
        }
    },
    created() {
        console.log('created')
        this.fetchDepartment()

        this.sortOptions.callbacks.push({ group: 'student', cb: this.fetchPageMasterData })
    },
    methods: {
        fetchStudents() {
            this.$store.dispatch('student/all', {
                byDepartment: this.department.id,
                ...this.sortOptionsToKeyStingValue(this.sortOptions['student'])
            })
            .then(res => {
                this.students = res
                this.total_student = res.total
            })


        },
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
                this.total_student = this.department.students_count
                this.total_batch = this.department.batches_count

            })
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

        syncBatch(batch) {
            this.closeBatchAddEditModal()
            this.fetchDepartment()
        },
        syncBatchDelete(data) {
            const index = this.department.batches.data.findIndex(item => item.id == data.id)
            if(index != -1) {
                this.total_student -= this.department.batches.data[index].students_count
                this.department.batches.data.splice(index, 1)
            }
        },

        async prepareStudentAddEditModal(data) {
            if(data) {
                const batch = await this.$store.dispatch('batch/check', {id: data.batch_id})
                if(batch.id) {
                    this.updateStudentAddEdit({
                        batch: {
                            id: batch.id,
                            name: batch.name
                        }
                    })
                }

            }

            this.openStudentAddEditModal(data)

        },

        syncStudent(model) {
            this.closeStudentAddEditModal()
            const index = this.students.data.findIndex(item => item.id == model.id)
            if(index != -1) {
                this.students.data.splice(index, 1, model)
            } else {
                this.students.data.push(model)
                this.total_student = this.students.total + 1
            }

        },
        syncStudentDelete(data) {
            const index = this.students.data.findIndex(item => item.id == data.id)
            if(index != -1) {
                this.students.data.splice(index, 1)
                this.total_student--
            }
        },
        syncDepartment(model) {

            this.department.name = model.name
            this.closedepartmentAddEditModal()
        },
        syncDepartmentDelete(data) {
            this.$router.push({ name: 'home' })
        }
    }
};
</script>
