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

                            <dt>Tanggal </dt>
                            <dd>{{ actions.data.created_at | formatDateAction }} </dd>
                            <hr>

                            <dt>gudang </dt>
                            <dd>{{ actions.data.coil.location.gudang.name }}</dd>
                            <hr>

                            <dt>blok </dt>
                            <dd>{{ actions.data.coil.location.blok.name }}</dd>
                            <hr>

                        
                            <!-- INFORMASI DETAIL EXPENSES -->

                            <!-- JIKA STATUSNYA 2 = CANCEL, MAKA ALASANNYA DITAMPILKAN -->
                           
                        
                            <!-- JIKA STATUS 0 = BARU ATAU BARU DAN formReason = false MAKA TOMBOL TOLAK DAN TERIMA DITAMPILKAN -->
                            <div class="pull-right" >
                                <!-- KETIKA TOMBOL INI DITEKAN MAKA AKAN MENGUBAH VALUE formReason JADI TRUE -->
                                <button class="btn btn-danger btn-sm" @click="tolak(action.data.id)">Tolak</button>
                                <button class="btn btn-primary btn-sm" @click="accept(action.data.id)">Terima</button>
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
            })
        },
        methods: {
            ...mapActions('action_coil', ['getActionDetail','terimaAction','tolakAction']),
            //KETIKA TOMBOL TERIMA DITEKAN, MAKA AKAN MENJALANKAN FUNGSI accpet
            ...mapActions('coil', ['editcoil']),
            
            accept(id) {
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
                        this.terimaAction({id_action : this.$route.params.id, id_notif : 'test'}).then(() => this.$router.push({ name: 'coil.data' }))
                    }
                })
            },
          
            //KETIKA TOMBOL RESPON PENOLAK DITEKAN, MAKA FUNGSI INI AKAN DIJALANKAN
            tolak(id) {
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
                        this.tolakAction({id: this.$route.params.id}).then(() => {
                            this.$router.push({ name: 'coil.data' }) //DAN REDIRECT KEMBALI KE HALAMAN LIST EXPENSES
                        })
                    }
                })
            }
        }
    }
</script>