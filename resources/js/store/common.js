const makeQueryString = (queryObject) => {
    if(!queryObject) return ''
    let queryString = ''
    Object.keys(queryObject).forEach(key => {
        queryString += `${key}=${queryObject[key]}&&`
    })

    return queryString
}

const modelUpdate = ({}, data, baseUrl, responsKey) => {
    return new Promise(async (resolve, reject) => {
        const response = await axios.patch(`${baseUrl}/${data.id}/update`, data.payload)
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


const mapOrderbyToString = (order) => {
    const orderByString = {}
    Object.keys(order).forEach(key => {
        orderByString[key] = order[key] ? 'desc' : 'asc'
    })

    return orderByString
}

export { makeQueryString, modelUpdate, modelGet, mapOrderbyToString }
