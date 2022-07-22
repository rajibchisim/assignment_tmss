<template>
<div class="relative flex flex-col w-full min-w-0 mt-8 mb-6 break-words bg-white rounded-lg shadow-xl">
    <div class="px-6">
        <div class="relative mt-8 text-center text-gray-700">
            <h3 class="text-xl font-semibold leading-normal">
                Batch: {{ batchName }}
            </h3>
            <h2 class="text-lg">
                <router-link :to="{ name: 'department', params: { id: this.department ? this.department.id : '' } }"
                    class="hover:text-gray-800">
                    Department of: <span class="capitalize">{{ departmentName  }}</span>
                </router-link>
            </h2>
            <div class="mb-2">
                University of Computer Science
            </div>

            <link-back-arrow-url :route="{ name: 'department', params: { id: batch ? batch.department.id : '' } }"/>

            <button-edit-pen @click="openBatchAddEditModal" />

        </div>




        <div class="py-10 mt-10 text-center border-t border-blueGray-200">
            <div class="flex flex-wrap justify-center">
                <div class="w-full px-4 lg:w-3/12">
                    <div class="flex justify-center py-4 pt-6 lg:pt-4">
                        <div class="p-3 mr-4 text-center">
                        <span
                            class="block text-xl font-bold tracking-wide uppercase"
                        >
                            {{ total_student }}
                        </span>
                        <span class="text-sm text-blueGray-400">Students</span>
                        </div>
                    </div>
                </div>
                <div class="w-full px-4 lg:w-9/12">
                <div class="px-4 mb-4 text-left">
                    <button class="inline-flex items-center text-gray-600 hover:text-gray-700" @click="openStudentAddEditModal(null)">
                        <span>Create Student</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </button>
                </div>
                    <card-students v-if="batch" :initRows="batch.students.data" :parentScope="{ department: department.id, batch: batch.id }" @edit="openStudentAddEditModal"/>
                <button
                    v-if="batch && batch.students && batch.students.next_page_url"
                    @click.prevent="loadmore(batch.students, { name: 'batch/get', params: { id: batch.id }, query: { students: true } }, 'students')" class="font-normal text-gray-600 hover:text-gray-800">
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
    :enableDelete="true"
    @saveSync="syncBatch"
    @deleteSync="syncBatchDelete"
/>
<student-add-edit-modal :transfer="false" :modalData="studentAddEditModalData" v-if="studentAddEditModalData.show" @close="closeStudentAddEditModal" @saveSync="syncStudent"/>
</div>
</template>
<script>

import ForwardPagination from '@/mixins/forwadPagination'

import CardBatches from "@/components/Cards/CardBatches.vue"
import CardStudents from '@/components/Cards/CardStudents.vue'
import BatchAddEditModal from "@/components/Modals/BatchAddEdit_v2.vue"
import StudentAddEditModal from "@/components/Modals/StudentAddEdit_v2.vue"

export default {
    components: {
        CardBatches,
        CardStudents,
        BatchAddEditModal,
        StudentAddEditModal
    },
    mixins: [ForwardPagination],
    data() {
        return {
            department: null,
            total_student: 0,
            batch: null,
            error: null,
            batchAddEditModalData: {
                show: false,
                model: null,
                parentModel: null,
                labels: {
                    heading: 'Edit Batch',
                    subheading: 'Update Batch information',
                    input: 'Batch Name',
                    button: 'Update',

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
        departmentName() {
            if(!this.batch) return ''
            return this.batch.department.name + ' | ' + this.department.id
        },
        batchName() {
            if(!this.batch) return ''
            return this.batch.name + ' | ' + this.batch.id
        }
    },
    watch: {
        batch: {
            immediate: true,
            handler() {
                if(this.batch) {
                    this.batchAddEditModalData.model = { id: this.batch.id }
                    this.batchAddEditModalData.parentModel = this.batch.department

                    this.studentAddEditModalData.parentModel = {
                        department: this.batch.department,
                        batch: { id: this.batch.id, name: this.batch.name }
                    }
                }
            }
        }
    },
    created() {
        console.log('created')
        this.fetchPageMasterData()
    },
    methods: {
        fetchPageMasterData() {
            this.$store.dispatch('batch/get', {
                id: this.$route.params.id,
                queryObject: {
                    students: true
                }
            })
            .then(res => {
                console.log('batch single: ', res)
                this.batch = res
                this.department = res.department
                this.total_student = res.students_count
            })
        },
        openBatchAddEditModal() {
            this.batchAddEditModalData.model.name = this.batch.name
            this.batchAddEditModalData.show = true


        },
        closeBatchAddEditModal() {
            this.batchAddEditModalData.show = false
        },

        // --- student add edit modal
        openStudentAddEditModal(model=null) {
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
        // --- student add edit modal


        syncBatch(batch) {
            this.batch.name = batch.name
            this.closeBatchAddEditModal()
        },
        syncBatchDelete() {
            this.$router.push({name: 'department', params: { id: this.batch.department.id }})
        },

        syncStudent(studentData) {
            this.closeStudentAddEditModal()
            const index = this.batch.students.data.findIndex(item => item.id == studentData.id)
            if(index != -1) {
                this.batch.students.data.splice(index, 1, studentData)
            } else {
                this.batch.students.data.push(studentData)
                this.total_student ++
            }
        }
    }
};
</script>
