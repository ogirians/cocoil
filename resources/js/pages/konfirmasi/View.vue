<template>

            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-heading">
                        <h3 class="panel-title">Detail Konfirmasi</h3>
                    </div>
                    <div class="panel-body">
                        <template>
                            <!-- INFORMASI DETAIL EXPENSES -->
                            <dt>Serial Code </dt>
                            <dd>{{ actions.data.coil.serial_Code }}</dd>
                            <hr>
                            
                            <dt>Jenis Permintaan aksi </dt>
                            <dd>{{ actions.data.action_tipe }}</dd>
                            <hr>
                            
                            <dt>no Dokumen </dt>
                            <dd>{{ actions.data.no_dokumen }}</dd>
                            <hr>

                             <dt>catatan</dt>
                            <dd>{{ actions.data.catatan != null ? actions.data.catatan : '-' }}</dd>
                            <hr>

                            <dt>Tanggal </dt>
                            <dd>{{ actions.data.created_at | formatDateAction }} </dd>
                            <hr>

                            <dt v-if="actions.data.action_status == null">gudang </dt>
                            <dd v-if="actions.data.action_status == null">{{ actions.data.coil.location.gudang.name }}</dd>
                            <hr v-if="actions.data.action_status == null">

                            <dt v-if="actions.data.action_status == null">blok </dt>
                            <dd v-if="actions.data.action_status == null">{{ actions.data.coil.location.blok.name }}</dd>
                            <hr v-if="actions.data.action_status == null">

                            <dt v-if="actions.data.action_status ">Keterangan</dt>
                            <dd v-if="actions.data.action_status ">coil sudah {{actions.data.action_status}} oleh <b>{{ actions.data.user.name != null ? actions.data.user.name : 'unknown' }}</b> untuk <b>{{ actions.data.action_tipe }}</b></dd>
                            <hr v-if="actions.data.action_status ">

                        
                            <!-- INFORMASI DETAIL EXPENSES -->

                            <!-- JIKA STATUSNYA 2 = CANCEL, MAKA ALASANNYA DITAMPILKAN -->
                           
                        
                            <!-- JIKA STATUS 0 = BARU ATAU BARU DAN formReason = false MAKA TOMBOL TOLAK DAN TERIMA DITAMPILKAN -->
                            <div class="pull-right" >
                                <!-- KETIKA TOMBOL INI DITEKAN MAKA AKAN MENGUBAH VALUE formReason JADI TRUE -->
                                <button v-if="actions.data.action_status == null" class="btn btn-danger btn-sm" @click="tolak(actions.data.id)">Tolak</button>
                                <button v-if="actions.data.action_status == null" class="btn btn-primary btn-sm" @click="accept(actions.data.id)">Terima</button>
                                <button v-else class="btn btn-primary btn-sm" @click="baca(actions.data.id)" >Baca</button>
                            </div>
                        </template>

                        <!-- JIKA formReason NILAINYA TRUE, MAKA FORM INI DITAMPILAKN UNTUK MENGISI ALASAN PENOLAKAN -->
                     
                    </div>
                </div>
            </div>

</template>
<script>
import { mapActions, mapState } from 'vuex'
import moment from 'moment'

    export default {
        name: 'viewKonfirmasi',

        created() {
            this.getActionDetail(this.$route.params.id);

           
        },
        
        data() {
            return {
               
            }
        },

        filters: {
        //UNTUK MENGUBAH FORMAT TANGGAL MENJADI TIME AGO
            formatDateAction(val) {
                return moment(val).format('MMMM Do YYYY, h:mm:ss a');
            }
        },
        computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('action_coil', {
            actions: state => state.actions //MENGAMBIL STATE coil
            }),

            selected_notification: {
                get() {
                    //MENGAMBIL VALUE PAGE DARI VUEX MODULE outlet
                    return this.$store.state.notification.selected_notification
                },
                set(val) {
                    //APABILA TERJADI PERUBAHAN VALUE DARI PAGE, MAKA STATE PAGE
                    //DI VUEX JUGA AKAN DIUBAH
                    this.$store.commit('notification/ASSIGN_SELECTED_NOTIFICATION', val)
                }
            },
        },

        methods: {
            ...mapActions('action_coil', ['getActionDetail','terimaAction','tolakAction','bacaAction']),
            //KETIKA TOMBOL TERIMA DITEKAN, MAKA AKAN MENJALANKAN FUNGSI accpet
            ...mapActions('coil', ['editcoil']),

            ...mapActions('notification', ['getNotificationsAction']),
            
            accept() {
                this.$swal({
                    title: 'Kamu Yakin?',
                    text: "Permintaan yang disetujui tidak dapat dikembalikan!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Iya, Lanjutkan!'
                }).then((result) => {
                    if (result.value) {
                        //JIKA YES, MAKA KITA AKAN MENGIRIMKAN PERMINTAAN KE SERVER UNTUK MENYETUJUI PERMINTAAN TERSEBUT DAN REDIRECT KE HALAMAN LIST EXPENSES
                        this.terimaAction({id_action : this.$route.params.id, id_notif : this.selected_notification, action_tipe : this.actions.data.action_tipe }).then(() => this.$router.push({ name: 'coil.data' })).then(() => this.getNotificationsAction())
                    }
                })
            },
          
            //KETIKA TOMBOL RESPON PENOLAK DITEKAN, MAKA FUNGSI INI AKAN DIJALANKAN
            tolak() {
                this.$swal({
                    title: 'Kamu Yakin?',
                    text: "Permintaan yang ditolak tidak dapat dikembalikan!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Iya, Lanjutkan!'
                }).then((result) => {
                    if (result.value) {
                        //JIKA IYA, MAKA KITA AKAN MENGIRIMKAN PERMINTAAN KE BACKEND UNTUK MENGUBAH STATUS EXPENSES MENJADI DITOLAK
                        this.tolakAction({id_action : this.$route.params.id, id_notif : this.selected_notification, action_tipe : this.actions.data.action_tipe }).then(() => this.$router.push({ name: 'coil.data' })).then(() => this.getNotificationsAction())

                    }
                })
            },
            baca() {
                this.$swal({
                    title: 'Kamu Yakin?',
                    text: "Permintaan yang sudah dibaca tidak dapat dikembalikan!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Iya, Lanjutkan!'
                }).then((result) => {
                    if (result.value) {
                         this.bacaAction({id_action : this.$route.params.id, id_notif : this.selected_notification, action_tipe : this.actions.data.action_tipe }).then(() => this.$router.push({ name: 'coil.data' })).then(() => this.getNotificationsAction())
   
                    }
                })
            }


        },

        updated(){
             if(this.selected_notification == ''){
                this.$router.push({ name: 'home' });
            }
        },
        destroy(){
            this.selected_notification = ''
        }
    }
</script>