import $axios from '../api.js'

const state = () => ({
    coi_locations: [], //UNTUK MENAMPUNG DATA OUTLETS YANG DIDAPATKAN DARI DATABASE

    page: 1 //UNTUK MENCATAT PAGE PAGINATE YANG SEDANG DIAKSES
})

const mutations = {
    //MEMASUKKAN DATA KE STATE locationS
    ASSIGN_DATA(state, payload) {
        state.locations = payload
    },
    //MENGUBAH DATA STATE PAGE
    SET_PAGE(state, payload) {
        state.page = payload
    },
    //MENGUBAH DATA STATE location
    ASSIGN_FORM(state, payload) {
        state.location = {
            item_category: payload.item_category,
            item_type: payload.item_type,
            item_code: payload.item_code,
            item_description: payload.item_description,
            serial_Code: payload.serial_Code,
            id_location: payload.id_location,
            balance: payload.balance,
        }
    },
    //ME-RESET STATE OUTLET MENJADI KOSONG
    CLEAR_FORM(state) {
        state.location = {
            item_category: '',
            item_type: '',
            item_code: '',
            item_description: '',
            serial_code: '',
            id_location:'',
            balance: '',
        }
    }
}

const actions = {
    //FUNGSI INI UNTUK MELAKUKAN REQUEST DATA OUTLET DARI SERVER
    getlocations({ commit, state }, payload) {
        //MENGECEK PAYLOAD ADA ATAU TIDAK
        let search = typeof payload != 'undefined' ? payload:''
        return new Promise((resolve, reject) => {
            //REQUEST DATA DENGAN ENDPOINT /OUTLETS
            $axios.get(`/locations?page=${state.page}&q=${search}`)
            .then((response) => {
                //SIMPAN DATA KE STATE MELALUI MUTATIONS
                commit('ASSIGN_DATA', response.data)
                resolve(response.data)
            })
        })
    },
    //FUNGSI UNTUK MENAMBAHKAN DATA BARU
    submitlocation({ dispatch, commit, state }) {
        return new Promise((resolve, reject) => {
            //MENGIRIMKAN PERMINTAAN KE SERVER DAN MELAMPIRKAN DATA YANG AKAN DISIMPAN
            //DARI STATE OUTLET
            $axios.post(`/locations`, state.location)
            .then((response) => {
                //APABILA BERHASIL KITA MELAKUKAN REQUEST LAGI
                //UNTUK MENGAMBIL DATA TERBARU
                dispatch('getlocations').then(() => {
                    resolve(response.data)
                })
            })
            .catch((error) => {
                //APABILA TERJADI ERROR VALIDASI
                //DIMANA LARAVEL MENGGUNAKAN CODE 422
                if (error.response.status == 422) {
                    //MAKA LIST ERROR AKAN DIASSIGN KE STATE ERRORS
                    commit('SET_ERRORS', error.response.data.errors, { root: true })
                }
            })
        })
    },
    //UNTUK MENGAMBIL SINGLE DATA DARI SERVER BERDASARKAN CODE OUTLET
    editlocation({ commit }, payload) {
        return new Promise((resolve, reject) => {
            //MELAKUKAN REQUEST DENGAN MENGIRIMKAN CODE OUTLET DI URL
            $axios.get(`/locations/${payload}/edit`)
            .then((response) => {
                //APABIL BERHASIL, DI ASSIGN KE FORM
                commit('ASSIGN_FORM', response.data.data)
                resolve(response.data)
            })
        })
    },
    //UNTUK MENGUPDATE DATA BERDASARKAN CODE YANG SEDANG DIEDIT
    updatelocation({ state, commit }, payload) {
        return new Promise((resolve, reject) => {
            //MELAKUKAN REQUEST DENGAN MENGIRIMKAN CODE DIURL
            //DAN MENGIRIMKAN DATA TERBARU YANG TELAH DIEDIT
            //MELALUI STATE OUTLET
            $axios.put(`/locations/${payload}`, state.location)
            .then((response) => {
                //FORM DIBERSIHKAN
                commit('CLEAR_FORM')
                resolve(response.data)
            })
        })
    } ,
    //MENGHAPUS DATA
    removelocation({ dispatch }, payload) {
        return new Promise((resolve, reject) => {
            //MENGIRIM PERMINTAAN KE SERVER UNTUK MENGHAPUS DATA
            //DENGAN METHOD DELETE DAN ID OUTLET DI URL
            $axios.delete(`/locations/${payload}`)
            .then((response) => {
                //APABILA BERHASIL, FETCH DATA TERBARU DARI SERVER
                dispatch('getlocations').then(() => resolve())
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