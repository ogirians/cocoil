import Vue from 'vue'
import Vuex from 'vuex'
import { modalModule } from 'vuex-modal'

//IMPORT MODULE SECTION
import auth from './stores/auth.js'
import gudang from './stores/gudang.js'
import user from './stores/user.js'
import employee from './stores/employee.js'
import importExcel from './stores/import.js'
import coil from './stores/coil.js'
import blok from './stores/blok.js'
import from_view from './stores/from_view.js'

Vue.use(Vuex)

//DEFINE ROOT STORE VUEX
const store = new Vuex.Store({
    //SEMUA MODULE YANG DIBUAT AKAN DITEPATKAN DIDALAM BAGIAN INI DAN DIPISAHKAN DENGAN KOMA UNTUK SETIAP MODULE-NYA
    modules: {
        auth,
        gudang,
        user,
        employee,
        importExcel,
        coil,
        blok,
        modalModule,
        from_view,
    },
  	//STATE HAMPIR SERUPA DENGAN PROPERTY DATA DARI COMPONENT HANYA SAJA DAPAT DIGUNAKAN SECARA GLOBAL
    state: {
        //VARIABLE TOKEN MENGAMBIL VALUE DARI LOCAL STORAGE token
        token: localStorage.getItem('token'),
        errors: []
    },
    getters: {
        //KITA MEMBUAT SEBUAH GETTERS DENGAN NAMA isAuth
        //DIMANA GETTERS INI AKAN BERNILAI TRUE/FALSE DENGAN KONDISI BERDASARKAN
        //STATE token.
        isAuth: state => {
            return state.token != "null" && state.token != null
        }
    },
    mutations: {
        //SEBUAH MUTATIONS YANG BERFUNGSI UNTUK MEMANIPULASI VALUE DARI STATE token
        SET_TOKEN(state, payload) {
            state.token = payload
        },
        SET_ERRORS(state, payload) {
            state.errors = payload
        },
        CLEAR_ERRORS(state) {
            state.errors = []
        }
    }
})

export default store