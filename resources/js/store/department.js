const baseUrl = '/api/departments'
const { makeQueryString, modelCreate, modelDelete, modelUpdate } = require('./common')


export default {
    actions: {
        async all({}, queryObject = {}) {
            const queryString = makeQueryString(queryObject)
            const response = await axios.get(`${baseUrl}?${queryString}`)


            if(response.data.status == 200) {
                // console.log(response.data)
                return response.data.departments_paginated
            } else {
                return null
            }
        },
        async search({}, queryObject) {
            const queryString = makeQueryString(queryObject)
            const response = await axios.get(`${baseUrl}/search?${queryString}`)
            if(response.data.status == 200) {
                return response.data.result
            }
        },
        async get({}, data={id: '', queryObject: {}}) {
            const queryString = makeQueryString(data.queryObject)
            const response = await axios.get(`${baseUrl}/${data.id}?${queryString}`)

            if(response.data.status == 200) {
                return response.data.department
            } else {
                return null
            }
        },
        create: ({}, data) => modelCreate(data, baseUrl, 'department'),

        update: ({}, payload) => modelUpdate({}, payload, baseUrl, 'department'),

        delete:({}, payload) => modelDelete(payload, baseUrl, 'department')
    }
}
