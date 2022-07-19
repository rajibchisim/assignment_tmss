const makeQueryString = (queryObject) => {
    let queryString = ''
    Object.keys(queryObject).forEach(key => {
        queryString += `${key}=${queryObject[key]}&&`
    })

    return queryString
}

export { makeQueryString }
