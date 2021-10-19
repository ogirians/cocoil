import Vue from 'vue'
import router from './router.js'
import store from './store.js'
import App from './App.vue'
import BootstrapVue from 'bootstrap-vue'
import VueSweetalert2 from 'vue-sweetalert2'
import VueKonva from 'vue-konva';



Vue.use(VueSweetalert2)
Vue.use(BootstrapVue)
Vue.use(VueKonva);

import 'bootstrap-vue/dist/bootstrap-vue.css'

import Permissions from './mixins/Permission.js'
Vue.mixin(Permissions)

import { mapActions, mapGetters, mapState } from 'vuex'
import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

new Vue({
    el: '#dw',
    router,
    store,
    components: {
        App
    },
    computed: {
        ...mapGetters(['isAuth']),
        ...mapState(['token']), //GET TOKEN
        ...mapState('user', {
            user_authenticated: state => state.authenticated //MENGAMBIL STATE USER YANG SEDANG LOGIN
        })
    },
    methods: {
        ...mapActions('user', ['getUserLogin']),
        ...mapActions('notification', ['getNotificationsGudang']), //DEFINISIKAN FUNGSI UNTUK MENGAMBIL NOTIFIKASI DARI TABLE NOTIFICATIONS
        ...mapActions('notification', ['getNotificationsAction']),
        ...mapActions('gudang', ['getGudangs']), //FUNGSI UNTUK MENGAMBIL EXPENSES YANG BARU
          //METHOD INI UNTUK MENGISIASI LISTER MENGGUNAKAN LARAVEL ECHO
          initialLister_gudang() {
            //JIKA USER SUDAH LOGIN
            if (this.isAuth) {
                //MAKA INISIASI FUNGSI BROADCASTER DENGAN KONFIGURASI BERIKUT
                window.Echo = new Echo({
                    broadcaster: 'pusher',
                    key: process.env.MIX_PUSHER_APP_KEY, //VALUENYA DI AMBIL DARI FILE .ENV
                    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
                    encrypted: false,
                    auth: {
                        headers: {
                            Authorization: 'Bearer ' + this.token
                        },
                    },
                });
    
                if (typeof this.user_authenticated.id != 'undefined') {
                    //KEMUDIAN KITA MENGAKSES CHANNEL BROADCAST SECARA PRIVATE
                    window.Echo.private(`App.User.${this.user_authenticated.id}`)
                    .notification(() => {   
                        //APABILA DITEMUKAN, MAKA KITA MENJALANKAN KEDUA FUNGSI INI
                        //UNTUK MENGAMBIL DATA TERBARU
                       
                        this.getNotificationsGudang()
                        this.getGudangs()
                    })
                }
            }
        },
        initialLister_action() {
            //JIKA USER SUDAH LOGIN
            if (this.isAuth) {
                //MAKA INISIASI FUNGSI BROADCASTER DENGAN KONFIGURASI BERIKUT
                window.Echo = new Echo({
                    broadcaster: 'pusher',
                    key: process.env.MIX_PUSHER_APP_KEY, //VALUENYA DI AMBIL DARI FILE .ENV
                    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
                    encrypted: false,
                    auth: {
                        headers: {
                            Authorization: 'Bearer ' + this.token
                        },
                    },
                });
    
                if (typeof this.user_authenticated.id != 'undefined') {
                    //KEMUDIAN KITA MENGAKSES CHANNEL BROADCAST SECARA PRIVATE
                    window.Echo.private(`App.User.${this.user_authenticated.id}`)
                    .notification((notification) => {   
                        //APABILA DITEMUKAN, MAKA KITA MENJALANKAN KEDUA FUNGSI INI
                        //UNTUK MENGAMBIL DATA TERBARU
                        console.log(notification.type);
                        this.getNotificationsAction()
                       
                      
                    })
                }
            }
        }

    },
    watch: {
        //KITA WATCH KETIKA VALUE TOKEN BERUBAH, MAKA 
        token() {
            this.initialLister_gudang() 
            this.initialLister_action()//KITA JALANKAN FUNGSI UNTUK MENGINISIASI LAGI
        },
        //KETIKA VALUE USER YANG SEDANG LOGIN BERUBAH
        user_authenticated() {
            this.initialLister_gudang() 
            this.initialLister_action() //KITA JALANKAN LAGI
        }
    },
    created() {
        if (this.isAuth) {
            this.getUserLogin() //REQUEST DATA YANG SEDANG LOGIN
            
             //TAMBAHKAN BAGIAN INI KETIKA COMPONENT DILOAD
             this.initialLister_gudang() 
             this.initialLister_action()

            this.getNotificationsAction()
            this.getNotificationsGudang()
        
        }
        
    }
})