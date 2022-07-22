const baseUrl = '/api/departments'
const responseKey = 'department'
const { makeQueryString, modelCreate, modelDelete, modelUpdate, modelIndex, modelGet, modelSearch } = require('./common')


export default {
    actions: {
        all: ({}, queryObject) => modelIndex({ query: queryObject}, baseUrl, 'departments_paginated'),
        search: ({}, queryObject) => modelSearch(baseUrl, queryObject),
        get: ({}, data) => modelGet({}, data, baseUrl, responseKey),
        create: ({}, data) => modelCreate(data, baseUrl, responseKey),

        update: ({}, payload) => modelUpdate({}, payload, baseUrl, responseKey),

        delete:({}, payload) => modelDelete(payload, baseUrl, responseKey)
    }
}
