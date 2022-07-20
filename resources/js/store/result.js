const baseUrl = '/api/results'
const { makeQueryString, modelUpdate, modelGet, modelSearch } = require('./common')


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

        search: ({}, queryObject) => modelSearch(baseUrl, queryObject),

        get: ({}, data) => modelGet({}, data, baseUrl, 'result'),

        create({}, payload) {
            return new Promise(async (resolve, reject) => {
                const response = await axios.post(`${baseUrl}/store`, payload)
                if(response.data.status == 200) {
                    resolve(response.data.result)
                } else {
                    reject(response.data.errors)
                }
            })
        },
        update: ({}, payload) => modelUpdate({}, payload, baseUrl, 'result'),
        delete() {

        }
    }
}
