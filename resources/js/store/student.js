const baseUrl = '/api/students'
const responseKey = 'student'
const { makeQueryString, modelUpdate, modelGet, modelSearch, modelCreate, modelDelete, modelIndex } = require('./common')


export default {
    actions: {
        all: ({}, queryObject = {}) => modelIndex({query: queryObject}, baseUrl, 'students'),

        search: ({}, queryObject) => modelSearch(baseUrl, queryObject),

        get: ({}, data) => modelGet({}, data, baseUrl, responseKey),

        create: ({}, payload) => modelCreate(payload, baseUrl, responseKey),
        update: ({}, payload) => modelUpdate({}, payload, baseUrl, responseKey),
        updateTransfer: ({}, payload) => modelUpdate({}, payload, false, responseKey, `/api/students/${payload.id}/update-transfer`),
        delete:({}, payload) => modelDelete(payload, baseUrl)
    }
}
