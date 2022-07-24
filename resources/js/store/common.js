import store from './index'

const makeQueryString = (queryObject) => {
    if(!queryObject) return ''
    let queryString = ''
    Object.keys(queryObject).forEach(key => {
        const queryItem = queryObject[key]
        if(Array.isArray(queryItem)) {
            /**
             * Array contains
             * {
             *      column: 'columnName',
             *      value: 'columnValue'
             * }
             *
             */
            queryItem.forEach(item => {
                queryString += `${key}[${item.column}]=${item.value}&&`
            })
        } else {
            queryString += `${key}=${queryObject[key]}&&`
        }

        return queryString
    })

    return queryString
}

const modelUpdate = ({}, data, baseUrl, responsKey, customUrl) => {
    return new Promise(async (resolve, reject) => {
        let url = ''
        if(baseUrl === false) {
            url = customUrl
        } else {
            url = `${baseUrl}/${data.id}/update`
        }
        const response = await axios.patch(url, data.payload)
        if(response.data.status == 200) {
            resolve(response.data[responsKey])
        }

        checkAuthResponse(response) ? reject('') : reject(response.data.errors)
    })
}


const modelIndex = (
    data = { query: {} },
    baseUrl,
    responsKey
) => {
    return new Promise(async (resolve, reject) => {
        const queryString = makeQueryString(data.query)
        const response = await axios.get(`${baseUrl}?${queryString}`)

        if(response.data.status == 200) {
            resolve(response.data[responsKey])
        }

        checkAuthResponse(response) ? reject('') : reject(response.data.errors)

    })
}


const modelGet = ({}, data={id: '', queryObject: {}}, baseUrl, responsKey) => {
    console.log('modelGet: ', data.queryObject)
    return new Promise(async (resolve, reject) => {
        const queryString = makeQueryString(data.queryObject)
        const response = await axios.get(`${baseUrl}/${data.id}?${queryString}`)

        if(response.data.status == 200) {
            resolve(response.data[responsKey])
        }

        checkAuthResponse(response) ? reject('') : reject(response.data.errors)
    })

}

const modelCreate = (data, baseUrl, responsKey, customUrl) => {
    return new Promise(async (resolve, reject) => {
        let url = ''
        if(baseUrl === false) {
            url = customUrl
        } else {
            url = `${baseUrl}/store`
        }
        const response = await axios.post(url, data)
        if(response.data.status == 200) {
            resolve(response.data[responsKey])
        }

        checkAuthResponse(response) ? reject('') : reject(response.data.errors)
    })
}


const modelDelete = (data={id: ''}, baseUrl, responsKey) => {
    return new Promise(async (resolve, reject) => {
        const response = await axios.delete(`${baseUrl}/${data.id}/delete`)

        if(response.data.status == 200) {
            resolve(response.data[responsKey])
        }

        checkAuthResponse(response) ? reject('') : reject(response.data.errors)
    })

}


const modelSearch = (
    data = { query: {} },
    baseUrl
) => {
    return new Promise(async (resolve, reject) => {
        const queryString = makeQueryString(data.query)
        const response = await axios.get(`${baseUrl}/search?${queryString}`)
        if(response.data.status == 200) {
            resolve(response.data.result)
        }

        checkAuthResponse(response) ? reject('') : reject(response.data.errors)
    })
}


const mapOrderbyToString = (order) => {
    const orderByString = {}
    Object.keys(order).forEach(key => {
        orderByString[key] = order[key] ? 'desc' : 'asc'
    })

    return orderByString
}


const checkAuthResponse = (response) => {
    if(response.data.status == 401) {
        store.commit('auth/timeout')
    }
}

export {
    makeQueryString,
    modelUpdate,
    modelGet,
    modelSearch,
    mapOrderbyToString,
    modelCreate,
    modelDelete,
    modelIndex,
    checkAuthResponse
}
