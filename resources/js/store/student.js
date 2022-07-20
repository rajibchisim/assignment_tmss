const baseUrl = '/api/students'
const { makeQueryString, modelUpdate, modelGet, modelSearch, modelCreate, modelDelete } = require('./common')


export default {
    actions: {
        async all({}, queryObject = {}) {
            const queryString = makeQueryString(queryObject)
            const response = await axios.get(`${baseUrl}?${queryString}`)


            if(response.data.status == 200) {
                return response.data.students
            } else {
                return null
            }
        },

        search: ({}, queryObject) => modelSearch(baseUrl, queryObject),

        get: ({}, data) => modelGet({}, data, baseUrl, 'student'),

        create: ({}, payload) => modelCreate(payload, baseUrl, 'student'),
        update: ({}, payload) => modelUpdate({}, payload, baseUrl, 'student'),
        updateTransfer: ({}, payload) => modelUpdate({}, payload, false, 'student', `/api/students/${payload.id}/update-transfer`),
        delete:({}, payload) => modelDelete(payload, baseUrl)
    }
}
