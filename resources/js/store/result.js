const baseUrl = '/api/results'
const { makeQueryString, modelUpdate, modelGet, modelSearch, modelDelete, modelCreate } = require('./common')


export default {
    actions: {
        /* async all({}, queryObject = {}) {
            const queryString = makeQueryString(queryObject)
            const response = await axios.get(`${baseUrl}?${queryString}`)


            if(response.data.status == 200) {
                // console.log(response.data)
                return response.data.departments_paginated
            } else {
                return null
            }
        }, */

        search: ({}, data) => modelSearch(data, baseUrl),

        get: ({}, data) => modelGet({}, data, baseUrl, 'result'),

        create: ({}, payload) =>  modelCreate(payload, baseUrl, 'result'),
        update: ({}, payload) => modelUpdate({}, payload, baseUrl, 'result'),
        delete:({}, payload) => modelDelete(payload, baseUrl)
    }
}
