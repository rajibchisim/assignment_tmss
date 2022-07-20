const makeQueryString = (queryObject) => {
    if(!queryObject) return ''
    let queryString = ''
    Object.keys(queryObject).forEach(key => {
        queryString += `${key}=${queryObject[key]}&&`
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
        } else {
            reject(response.data.errors)
        }
    })
}


const modelGet = ({}, data={id: '', queryObject: {}}, baseUrl, responsKey) => {
    return new Promise(async (resolve, reject) => {
        const queryString = makeQueryString(data.queryObject)
        const response = await axios.get(`${baseUrl}/${data.id}?${queryString}`)

        if(response.data.status == 200) {
            resolve(response.data[responsKey])
        } else {
            reject(response.data.errors)
        }
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
        } else {
            reject(response.data.errors)
        }
    })
}


const modelSearch = (baseUrl, queryObject) => {
    return new Promise(async (resolve, reject) => {
        const queryString = makeQueryString(queryObject)
        const response = await axios.get(`${baseUrl}/search?${queryString}`)
        if(response.data.status == 200) {
            resolve(response.data.result)
        } else {
            reject(response.data.errors)
        }
    })
}


const mapOrderbyToString = (order) => {
    const orderByString = {}
    Object.keys(order).forEach(key => {
        orderByString[key] = order[key] ? 'desc' : 'asc'
    })

    return orderByString
}

export {
    makeQueryString,
    modelUpdate,
    modelGet,
    modelSearch,
    mapOrderbyToString,
    modelCreate
}
