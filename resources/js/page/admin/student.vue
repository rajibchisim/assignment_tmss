<template>

    <div>
        <div class="relative">
            <card-student-profile :profileData="studentProfile"/>
            <button class="absolute p-2 top-4 right-4 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
            </button>
        </div>
        <div class="mt-8 text-center">
            <card-student-results v-if="student" :initRows="student.results" @sort="sortOptionsToggle"/>
        </div>
        <div class="mt-6 text-center">
            <button class="font-normal text-gray-600 hover:text-gray-800"
            @click.prevent="loadmore(
                student.results,
                {
                    name: 'student/get',
                    params: {
                        id: student.id
                    },
                    query: {
                        results: true,
                        ...sortOptionsToKeyStingValue(sortOptions['result'])
                    }
                },
                'results'
            )"
            >
              Load more
            </button>
        </div>
    </div>
</template>

<script>
import SortableOptionToggle from '@/mixins/sortableOptionToggle'
import ForwardPagination from '@/mixins/forwadPagination'

import CardStudentProfile from '@/components/Cards/CardStudentProfile'
import CardStudentResults from '@/components/Cards/CardStudentResults'

export default {
    components: {
        CardStudentProfile,
        CardStudentResults
    },
    mixins: [
        ForwardPagination,
        SortableOptionToggle
    ],
    data() {
        return {
            student: null,
        }
    },
    computed: {
        studentProfile() {
            return {
                name: this.student ? this.student.name : '',
                department: this.student ? this.student.department.id + '|' + this.student.department.name : '',
                batch: this.student ? this.student.batch.id + ' | ' + this.student.batch.name : ''
            }
        },
        results() {
            if(!this.student) return null
            return this.student.results
        }
    },
    methods: {
        fetchPageMasterData() {
            this.$store.dispatch('student/get', {
                id: this.$route.params.id,
                queryObject: {
                    results: true,
                    ...this.sortOptionsToKeyStingValue(this.sortOptions['result'])
                }
            })
            .then(res => {
                console.log('batch single: ', res)
                this.student = res

            })
        },

    },
    created() {
        this.fetchPageMasterData()

        // related to sort mixin
        this.sortOptions.callbacks.push({ group: 'result', cb: this.fetchPageMasterData })
    }
}
</script>

<style>

</style>
