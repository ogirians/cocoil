import $axios from '../api.js'

const state = () => ({
    actions: '', //MENAMPUNG DATA NOTIFIKASI
    
    action_detail: {
        serial_code : '',
        password : '',
        no_dokumen : '',
        action_tipe : '',
    },

    status:'',


})

const mutations = {
    //ASSIGN DATA NOTIFIKASI KE DALAM STATE NOTIFICATIONS
    ASSIGN_ACTION_STATUS(state, payload) {
        state.actions = payload
    },

    ASSIGN_ACTION_SERIALCODE(state, payload) {
        state.action_detail.serial_code = payload
    }
}

const actions = {
    simpanAction({ dispatch, state, commit }, payload) {
        return new Promise((resolve, reject) => {
            //UNTUK MENGUPDATE DATA NOTIFIKASI BAHWA NOTIF TERSEBUT SUDAH DIBACA
            $axios.post(`/action-coil/store`, state.action_detail)
            .then((response) => {
                if (response.data.password == true) {
                    dispatch('getActionDetail').then(() => resolve(response.data))
                } else {
                    commit('SET_ERRORS', { invalid: 'Password Salah' }, { root: true })
                }
                //AMBIL DATA NOTIFIKASI TERBARU
               
            })
            .catch((error) => {
                if (error.response.status == 422) {
                    commit('SET_ERRORS', error.response.data.errors, { root: true })
                }
            })
        })   
    },


    getActionDetail({ commit },payload) {
        return new Promise((resolve, reject) => {
            //REQUEST KE SERVER UNTUK MENGAMBIL NOTIFIKASI
            $axios.get(`/action-coil-detail/${payload}`)
           
            .then((response) => {
                //DATA YANG DITERIMA DI COMMIT KE MUTATIONS ASSING_DATA
                commit('ASSIGN_ACTION_STATUS', response.data)
                resolve(response.status_action)
            })
        })
    },
    
    readAction({ dispatch }, payload) {
        return new Promise((resolve, reject) => {
            //UNTUK MENGUPDATE DATA NOTIFIKASI BAHWA NOTIF TERSEBUT SUDAH DIBACA
            $axios.post(`/action-coil-read`, payload)
            .then((response) => {
                //AMBIL DATA NOTIFIKASI TERBARU
                //dispatch('readAction').then(() => resolve(response.data))
            })
        })   
    },

    terimaAction({ dispatch }, payload) {
        return new Promise((resolve, reject) => {
            //UNTUK MENGUPDATE DATA NOTIFIKASI BAHWA NOTIF TERSEBUT SUDAH DIBACA
            $axios.post(`/action-coil/terima/`,payload)
            .then((response) => {
                //AMBIL DATA NOTIFIKASI TERBARU
                dispatch('getActionDetail').then(() => resolve(response.status))
            })
        })   
    },

    tolakAction({ dispatch }, payload) {
        return new Promise((resolve, reject) => {
            //UNTUK MENGUPDATE DATA NOTIFIKASI BAHWA NOTIF TERSEBUT SUDAH DIBACA
            $axios.post(`/action-coil/tolak/`,payload)
            .then((response) => {
                //AMBIL DATA NOTIFIKASI TERBARU
                dispatch('getActionDetail').then(() => resolve(response.data))
            })
        })   
    },

    bacaAction({ dispatch }, payload) {
        return new Promise((resolve, reject) => {
            //UNTUK MENGUPDATE DATA NOTIFIKASI BAHWA NOTIF TERSEBUT SUDAH DIBACA
            $axios.post(`/action-coil/baca/`,payload)
            .then((response) => {
                //AMBIL DATA NOTIFIKASI TERBARU
                dispatch('getActionDetail').then(() => resolve(response.data))
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