const { makeQueryString, modelUpdate, modelDelete, modelCreate, checkAuthResponse, modelGet, modelSearch } = require('./common')
const baseUrl = '/api/batches'
const responseKey = 'batch'

export default {
    actions: {
        // async all({}, queryObject) {
        //     let queryString = makeQueryString(queryObject)

        //     const response = await axios.get(`${baseUrl}${queryString}`)
        //     if(response.data.status == 200) {
        //         // console.log(response.data)
        //         return response.data.batches
        //     }

        //     checkAuthResponse(response) ? reject('') : reject(response.data.errors)
        // },
        search: ({}, queryObject) => modelSearch(baseUrl, queryObject),
        get: ({}, data) => modelGet({}, data, baseUrl, responseKey),
        create: ({}, payload) => modelCreate(payload, baseUrl, responseKey),
        check({}, params) {
            return new Promise(async (resolve, reject) => {
                const response = await axios.get(`${baseUrl}/${params.id}/check`)
                if(response.data.status == 200) {
                    resolve(response.data.batch)
                }

                checkAuthResponse(response) ? reject('') : reject(response.data.errors)
            })
        },
        update: ({}, payload) => modelUpdate({}, payload, baseUrl, responseKey),
        delete:({}, payload) => modelDelete(payload, baseUrl)
    }
}
