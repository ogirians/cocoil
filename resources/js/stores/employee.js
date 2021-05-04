import $axios from '../api.js'

const state = () => ({
    Employees: [],
    page: 1,
    id: ''
})

const mutations = {
    ASSIGN_DATA(state, payload) {
        state.Employees = payload
    },
    SET_PAGE(state, payload) {
        state.page = payload
    },
    SET_ID_UPDATE(state, payload) {
        state.id = payload
    }
}

const actions = {
    getEmployees({ commit, state }, payload) {
        let search = typeof payload != 'undefined' ? payload:''
        return new Promise((resolve, reject) => {
            $axios.get(`/Employees?page=${state.page}&q=${search}`)
            .then((response) => {
                commit('ASSIGN_DATA', response.data)
                resolve(response.data)
            })
        })
    },
    submitEmployee({ dispatch, commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios.post(`/Employees`, payload, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then((response) => {
                dispatch('getEmployees').then(() => {
                    resolve(response.data)
                })
            })
            .catch((error) => {
                if (error.response.status == 422) {
                    commit('SET_ERRORS', error.response.data.errors, { root: true })
                }
            })
        })
    },
    editEmployee({ commit }, payload) {
        return new Promise((resolve, reject) => {
            $axios.get(`/Employees/${payload}/edit`)
            .then((response) => {
                resolve(response.data)
            })
        })
    },
    updateEmployee({ state }, payload) {
        return new Promise((resolve, reject) => {
            $axios.post(`/Employees/${state.id}`, payload, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then((response) => {
                resolve(response.data)
            })
        })
    } ,
    removeEmployee({ dispatch }, payload) {
        return new Promise((resolve, reject) => {
            $axios.delete(`/Employees/${payload}`)
            .then((response) => {
                dispatch('getEmployees').then(() => resolve(response.data))
            })
        })
    }
}

export default {
    namespaced: true,
    state,
    actions,
    mutations
}