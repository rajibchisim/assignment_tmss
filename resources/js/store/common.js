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

export { makeQueryString, modelUpdate }
