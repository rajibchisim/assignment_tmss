const { makeQueryString, modelUpdate, modelDelete } = require('./common')
const baseUrl = '/api/batches'
export default {
    actions: {
        async all({}, queryObject) {
            let queryString = makeQueryString(queryObject)

            const response = await axios.get(`${baseUrl}${queryString}`)
            if(response.data.status == 200) {
                // console.log(response.data)
                return response.data.batches
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
                return response.data.batch
            } else {
                return null
            }
        },
        create({}, payload) {
            return new Promise(async (resolve, reject) => {
                const response = await axios.post(`${baseUrl}/store`, payload)
                if(response.data.status == 200) {
                    resolve(response.data.batch)
                } else {
                    reject(response.data.errors)
                }
            })
        },
        update: ({}, payload) => modelUpdate({}, payload, baseUrl, 'batch'),
        delete:({}, payload) => modelDelete(payload, baseUrl)
    }
}
