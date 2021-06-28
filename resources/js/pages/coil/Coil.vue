<template>
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <div class="pull-right">
                    <input type="text" class="form-control" placeholder="Cari..." v-model="search">
                </div>
            </div><br>
            <div class="panel-body">
                <b-table responsive striped hover bordered :items="coils.data" :fields="fields" show-empty>

                    <template slot="actions" slot-scope="row">
                        <router-link :to="{ name: 'coil.edit', params: {id: row.item.id} }" class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i></router-link>
                        <button class="btn btn-danger btn-sm" @click="deletecoil(row.item.id)"><i class="fa fa-trash"></i></button>
                    </template>
                </b-table>

                <div class="row">
                    <div class="col-md-6">
                        <p v-if="coils.data"><i class="fa fa-bars"></i> {{ coils.data.length }} item dari {{ coils.meta.total }} total data</p>
                    </div>
                    <div class="col-md-6">
                        <div class="pull-right">
                            <b-pagination
                                v-model="page"
                                :total-rows="coils.meta.total"
                                :per-page="coils.meta.per_page"
                                aria-controls="coils"
                                v-if="coils.data && coils.data.length > 0"
                                ></b-pagination>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapActions, mapState } from 'vuex'

export default {
    name: 'Datacoil',
    created() {
        //SEBELUM COMPONENT DI-LOAD, REQUEST DATA DARI SERVER
        this.getcoils()
    },
    data() {
        return {
            //FIELD UNTUK B-TABLE, PASTIKAN KEY NYA SESUAI DENGAN FIELD DATABASE
            //AGAR OTOMATIS DI-RENDER
            fields: [
                { key: 'item_category', label: 'Kategori' },
                { key: 'item_type', label: 'Tipe' },
                { key: 'item_code', label: 'item_code' },
                { key: 'item_description', label: 'deskripsi' },
                { key: 'serial_Code', label: 'Serial_code' },
                { key: 'ID_coil', label: 'ID Coil' },
                { key: 'balance', label: 'balance' },
                { key: 'gudang_id', label: 'gudang' },
                { key: 'blok_id', label: 'blok' },
                { key: 'actions', label: 'aksi' }

            ],
            search: ''
        }
    },
    computed: {
        //MENGAMBIL DATA OUTLETS DARI STATE OUTLETS
        ...mapState('coil', {
            coils: state => state.coils
        }),

        page: {
            get() {
                //MENGAMBIL VALUE PAGE DARI VUEX MODULE outlet
                return this.$store.state.coil.page
            },
            set(val) {
                //APABILA TERJADI PERUBAHAN VALUE DARI PAGE, MAKA STATE PAGE
                //DI VUEX JUGA AKAN DIUBAH
                this.$store.commit('coil/SET_PAGE', val)
            }
        }
    },
    watch: {
        page() {
            //APABILA VALUE DARI PAGE BERUBAH, MAKA AKAN MEMINTA DATA DARI SERVER
            this.getcoils()
        },
        search() {
            //APABILA VALUE DARI SEARCH BERUBAH MAKA AKAN MEMINTA DATA
            //SESUAI DENGAN DATA YANG SEDANG DICARI
            this.getcoils(this.search)
        }
    },
    methods: {
        //MENGAMBIL FUNGSI DARI VUEX MODULE outlet
        ...mapActions('coil', ['getcoils', 'removecoil']),
        //KETIKA TOMBOL HAPUS DICLICK, MAKA AKAN MENJALANKAN METHOD INI
        deletecoil(id) {
            //AKAN MENAMPILKAN JENDELA KONFIRMASI
            this.$swal({
                title: 'Kamu Yakin?',
                text: "Tindakan ini akan menghapus secara permanent!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Iya, Lanjutkan!'
            }).then((result) => {
                //JIKA DISETUJUI
                if (result.value) {
                    //MAKA FUNGSI removeOutlet AKAN DIJALANKAN
                    this.removecoil(id)
                }
            })
        }
    }
}
</script>