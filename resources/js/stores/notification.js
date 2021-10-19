import $axios from '../api.js'

const state = () => ({
        notifications_gudang: [] ,//MENAMPUNG DATA NOTIFIKASI
    
        notifications_action: []
})

const mutations = {
    //ASSIGN DATA NOTIFIKASI KE DALAM STATE NOTIFICATIONS
    ASSIGN_DATA(state, payload) {
        state.notifications_gudang = payload
    },

    ASSIGN_DATA_ACTION(state, payload) {
        state.notifications_action = payload
    }
}

const actions = {
    getNotificationsGudang({ commit }) {
        return new Promise((resolve, reject) => {
            //REQUEST KE SERVER UNTUK MENGAMBIL NOTIFIKASI
            $axios.get(`/notification-gudang`)
            .then((response) => {
                //DATA YANG DITERIMA DI COMMIT KE MUTATIONS ASSING_DATA
                commit('ASSIGN_DATA', response.data.data)
                resolve(response.data)
            })
        })
    },
    readNotificationGudang({ dispatch }, payload) {
        return new Promise((resolve, reject) => {
            //UNTUK MENGUPDATE DATA NOTIFIKASI BAHWA NOTIF TERSEBUT SUDAH DIBACA
            $axios.post(`/notification-gudang`, payload)
            .then((response) => {
                //AMBIL DATA NOTIFIKASI TERBARU
                dispatch('getNotificationsGudang').then(() => resolve(response.data))
            })
        })   
    },

    getNotificationsAction({ commit }) {
        return new Promise((resolve, reject) => {
            //REQUEST KE SERVER UNTUK MENGAMBIL NOTIFIKASI
            $axios.get(`/notification-action`)
            .then((response) => {
                //DATA YANG DITERIMA DI COMMIT KE MUTATIONS ASSING_DATA
                commit('ASSIGN_DATA_ACTION', response.data.data)
                resolve(response.data)
            })
        })
    },
    readNotificationAction({ dispatch }, payload) {
        return new Promise((resolve, reject) => {
            //UNTUK MENGUPDATE DATA NOTIFIKASI BAHWA NOTIF TERSEBUT SUDAH DIBACA
            $axios.post(`/notification-action`, payload)
            .then((response) => {
                //AMBIL DATA NOTIFIKASI TERBARU
                dispatch('getNotificationsAction').then(() => resolve(response.data))
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