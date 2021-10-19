<template>
    <header class="main-header">
        <nav class="navbar navbar-static-top">
            <div class="container">
                <div class="navbar-header">
                    <router-link to="/" class="navbar-brand"><b>Co</b>Coil</router-link>
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                        <i class="fa fa-bars"></i>
                    </button>
                </div>

                <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li><router-link to="/">Home <span class="sr-only">(current)</span></router-link></li>
                        <li  v-if="$can('read gudangs')"><router-link :to="{ name: 'gudang.data' }">Gudang</router-link></li>
                        <li v-if="$can('import coil')"><router-link :to="{ name: 'coil.data' }">Coil</router-link></li>
                        <li ><router-link :to="{ name: 'employee.data' }">Employee</router-link></li>
                            <li class="dropdown" v-if="authenticated.role == 0">
                                <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">Settings <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><router-link :to="{name: 'role.permissions'}">Role Permission</router-link></li>
                                </ul>
                            </li>
                    </ul>
                    <form class="navbar-form navbar-left" role="search">
                        <div class="form-group">
                            <input type="text" class="form-control" id="navbar-search-input" placeholder="Search">
                        </div>
                    </form>
                </div>
                
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                       
                            <li class="dropdown messages-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-newspaper-o"></i>
                                    
                                    <!-- FUNGSI INI UNTUK MENGHITUNG JUMLAH DATA NOTIFIKASI YANG ADA -->
                                    <span v-if="notifications_gudang.length > 0" class="label label-success">{{ notifications_gudang.length }}</span>
                                </a>
                               
                                <ul class="dropdown-menu">
                                    <li class="header">ada {{ notifications_gudang.length }} Berita </li>
                                    <li>
                                        <ul class="menu" v-if="notifications_gudang.length > 0">
                                            
                                            <!-- KITA MELAKUKAN LOOPING TERHADAP DATA NOTIFIKASI YANG DISIMPAN KE DALAM STATE NOTIFICATIONS -->
                                            <li v-for="(row, index) in notifications_gudang" :key="index">
                                                <a href="javascript:void(0)" @click="readNotif(row)">
                                                    <div class="pull-left">
                                                        <img src="https://via.placeholder.com/160" class="img-circle" alt="User Image">
                                                    </div>
                                                    <h4>
                                                        <!-- TAMPILKAN NAMA PENGIRIM NOTIFIKASI -->
                                                        {{ row.data.sender_name }}
                                                        <!-- TAMPILKAN WAKTU NOTIFIKASI -->
                                                        <small><i class="fa fa-clock-o"></i> {{ row.created_at | formatDate }}</small>
                                                    </h4>
                                                    <!-- TAMPILKAN JENIS PERMINTAAN NOTIFIKASI -->
                                                    <p>menambahkan gudang baru : {{ row.data.dataNotif.name.substr(0, 30) }}</p>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <!-- <li class="footer"><a href="#">See All Messages</a></li> -->
                                </ul>
                            </li>
                            <li class="dropdown messages-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-bell-o"></i>
                                   
                                    <!-- FUNGSI INI UNTUK MENGHITUNG JUMLAH DATA NOTIFIKASI YANG ADA -->
                                    <span v-if="notifications_action.length > 0" class="label label-danger">{{ notifications_action.length }}</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="header">ada {{ notifications_action.length }} permintaan Action untuk di konfirmasi. </li>
                                    <li>
                                        <ul class="menu" v-if="notifications_action.length > 0">
                                            
                                            <!-- KITA MELAKUKAN LOOPING TERHADAP DATA NOTIFIKASI YANG DISIMPAN KE DALAM STATE NOTIFICATIONS -->
                                            <li v-for="(row2, index2) in notifications_action" :key="index2">
                                                <a href="javascript:void(0)" @click="readNotifAction(row2)">
                                                    <div class="pull-left">
                                                        <img src="https://via.placeholder.com/160" class="img-circle" alt="User Image">
                                                    </div>
                                                    <h4>
                                                        <!-- TAMPILKAN NAMA PENGIRIM NOTIFIKASI -->
                                                        {{ row2.data.sender_name }}
                                                        <!-- TAMPILKAN WAKTU NOTIFIKASI -->
                                                        <small><i class="fa fa-clock-o"></i> {{ row2.created_at | formatDate }}</small>
                                                    </h4>
                                                    <!-- TAMPILKAN JENIS PERMINTAAN NOTIFIKASI -->
                                                    <p>konfirmasi <b>{{ row2.data.dataNotif.action_tipe.substr(0, 30) }}</b> coil <b>{{row2.data.serial_code}}</b></p>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <!-- <li class="footer"><a href="#">See All Messages</a></li> -->
                                </ul>
                            </li>
                        
                       <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="https://via.placeholder.com/160" class="user-image" alt="User Image">

                                    <!-- MODIFIKASI BAGIAN INI -->
                                <span class="hidden-xs">{{ authenticated.name }}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="user-header">
                                    <img src="https://via.placeholder.com/160" class="img-circle" alt="User Image">

                                    <!-- MODIFIKASI BAGIAN INI -->
                                    <p>{{ authenticated.name }}</p>
                                </li>
                                <li class="user-body">
                                    <div class="row">
                                        <!-- <div class="col-xs-4 text-center">
                                            <a href="#">Followers</a>
                                        </div>
                                        <div class="col-xs-4 text-center">
                                            <a href="#">Sales</a>
                                        </div>
                                        <div class="col-xs-4 text-center">
                                            <a href="#">Friends</a>
                                        </div> -->
                                    </div>
                                </li>
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="#" class="btn btn-default btn-flat">Profile</a>
                                    </div>
                                    <div class="pull-right">

                                        <!-- MODIFIKASI BAGIAN INI -->
                                        <a href="javascript:void(0)" @click="logout" class="btn btn-default btn-flat">Sign out</a>

                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
</template>

<script>

import { mapState, mapActions } from 'vuex'
import moment from 'moment'


export default {

    
    computed: {
        ...mapState('user', {
            authenticated: state => state.authenticated //ME-LOAD STATE AUTHENTICATED
        }),

         //CUKUP TAMBAHKAN BAGIAN INI
        ...mapState('notification', {
            notifications_gudang : state => state.notifications_gudang ,//MENGAMBIL STATE NOTIFICATIONS
  
        }),

          ...mapState('notification', {
            notifications_action : state => state.notifications_action
        })
 
    },

    filters: {
    //UNTUK MENGUBAH FORMAT TANGGAL MENJADI TIME AGO
        formatDate(val) {
            return moment(new Date(val)).fromNow()
        }
    },

    methods: {
         ...mapActions('notification', ['readNotificationGudang','readNotificationAction']), //DEFINISIKAN FUNGSI UNTUK READ NOTIF
    
            //KETIKA NOTIFIKASI DI KLIK MAKA AKAN MENJALANKAN FUNGSI INI
            readNotif(row) {
                //MENGIRIMKAN REQUEST KE SERVER UNTUK MENANDAI BAHWA NOTIFIKASI TELAH DI BACA
                //KEMUDIAN SELANJUTNYA KITA REDIRECT KE HALAMAN VIEW EXPENSES
                this.readNotificationGudang({ id: row.id}).then(() => this.$router.push({ name: 'gudang.data' }))
                
                //this.readNotificationAction({ id: row.id}).then(() => this.$router.push({ name: 'gudang.data', params: {id: row.data.dataNotif.id} }))
            },

            readNotifAction(row){
                this.readNotificationAction({ id: row.id}).then(() => this.$router.push({ name: 'konfirmasi.view', params: { id: row.data.dataNotif.id } }))
            },


        //KETIKA TOMBOL LOGOUT DITEKAN, FUNGSI INI DIJALANKAN
        logout() {
            return new Promise((resolve, reject) => {
                localStorage.removeItem('token') //MENGHAPUS TOKEN DARI LOCALSTORAGE
                resolve()
            }).then(() => {
                //MEMPERBAHARUI STATE TOKEN
                this.$store.state.token = localStorage.getItem('token')
                this.$router.push('/login') //REDIRECT KE PAGE LOGIN
            })
        }
    }
}
</script>