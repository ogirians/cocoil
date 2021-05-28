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

import { mapActions, mapGetters } from 'vuex'

new Vue({
    el: '#dw',
    router,
    store,
    components: {
        App
    },
    computed: {
        ...mapGetters(['isAuth'])
    },
    methods: {
        ...mapActions('user', ['getUserLogin'])
    },
    created() {
        if (this.isAuth) {
            this.getUserLogin() //REQUEST DATA YANG SEDANG LOGIN
        }
    }
})