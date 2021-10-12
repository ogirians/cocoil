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
                        <router-link v-if="row.item.location_id" :to="{ name: 'gudang.view'}" @click.native="setView(row.item.location.gudang.name,row.item.location.blok.name,row.item.location.gudang.id, row.item.location.blok.id ,row.item.location.slot_id)" class="btn btn-success btn-sm"><i class="fa fa-eye"></i></router-link>
                        <router-link :to="{ name: 'coil.edit', params: {id: row.item.id} }" class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i></router-link>
                        <button class="btn btn-danger btn-sm" @click="deletecoil(row.item.id)"><i class="fa fa-trash"></i></button>
                        <!--<a v-bind:href="url" class="btn btn-info btn-sm d-none" target="_blank"><i class="fa fa-qrcode"></i></a>
                        <a @click.native="setURl(row.item.serial_Code)" v-bind:href="url" class="btn btn-info btn-sm" target="_blank"><i class="fa fa-qrcode"></i></a>-->
                        <b-link :href="'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=http://localhost:8000/action/'+row.item.serial_Code" target="_blank" class="btn btn-info btn-sm"><i class="fa fa-qrcode"></i></b-link>


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
                { key: 'location.gudang.name', label: 'gudang' ,  formatter: 'formatGudang' },
                { key: 'location.blok.name', label: 'blok', formatter: 'formatBlok' },
                { key: 'actions', label: 'aksi' }
            ],
            search: '',
            url: '',
        }
    },
    computed: {
        //MENGAMBIL DATA OUTLETS DARI STATE OUTLETS
        ...mapState('coil', {
            coils: state => state.coils_data
        
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
        },
        selected_blok_id: {
            get() {
                //MENGAMBIL VALUE PAGE DARI VUEX MODULE outlet
                return this.$store.state.gudang.selected_blok_id
            },
            set(val) {
                //APABILA TERJADI PERUBAHAN VALUE DARI PAGE, MAKA STATE PAGE
                //DI VUEX JUGA AKAN DIUBAH
                this.$store.commit('gudang/ASSIGN_BLOK_ID', val)
            }
        },
		selected_gudang_id: {
            get() {
                //MENGAMBIL VALUE PAGE DARI VUEX MODULE outlet
                return this.$store.state.gudang.selected_gudang_id
            },
            set(val) {
                //APABILA TERJADI PERUBAHAN VALUE DARI PAGE, MAKA STATE PAGE
                //DI VUEX JUGA AKAN DIUBAH
                this.$store.commit('gudang/ASSIGN_GUDANG_ID', val)
            }
        },
		selected_coil_id: {
            get() {
                //MENGAMBIL VALUE PAGE DARI VUEX MODULE outlet
                return this.$store.state.gudang.selected_coil_id
            },
            set(val) {
                //APABILA TERJADI PERUBAHAN VALUE DARI PAGE, MAKA STATE PAGE
                //DI VUEX JUGA AKAN DIUBAH
                this.$store.commit('gudang/ASSIGN_COIL_ID', val)
            }
        },
		selected_serial_code: {
            get() {
                //MENGAMBIL VALUE PAGE DARI VUEX MODULE outlet
                return this.$store.state.gudang.selected_serial_code
            },
            set(val) {
                //APABILA TERJADI PERUBAHAN VALUE DARI PAGE, MAKA STATE PAGE
                //DI VUEX JUGA AKAN DIUBAH
                this.$store.commit('gudang/ASSIGN_SERIAL_CODE', val)
            }
        },
		selected_slot: {
            get() {
                //MENGAMBIL VALUE PAGE DARI VUEX MODULE outlet
                return this.$store.state.location.selected_slot
            },
            set(val) {
                //APABILA TERJADI PERUBAHAN VALUE DARI PAGE, MAKA STATE PAGE
                //DI VUEX JUGA AKAN DIUBAH
                this.$store.commit('location/ASSIGN_SELECTED_SLOT', val)
            }
        },
        selected_slot_name: {
            get() {
                //MENGAMBIL VALUE PAGE DARI VUEX MODULE outlet
                return this.$store.state.gudang.selected_slot_name;
            },
            set(val) {
                //APABILA TERJADI PERUBAHAN VALUE DARI PAGE, MAKA STATE PAGE
                //DI VUEX JUGA AKAN DIUBAH
                this.$store.commit('gudang/ASSIGN_SLOT_NAME', val);
            }
        },
        //UNTUK NAME GUDANG DAN BL0K KETIKA KLIK VIEWW
        selected_blok: {
            get() {
                //MENGAMBIL VALUE PAGE DARI VUEX MODULE outlet
                return this.$store.state.gudang.selected_blok
            },
            set(val) {
                //APABILA TERJADI PERUBAHAN VALUE DARI PAGE, MAKA STATE PAGE
                //DI VUEX JUGA AKAN DIUBAH
                this.$store.commit('gudang/ASSIGN_BLOK', val)
            }
        },
        selected_gudang_name: {
            get() {
                //MENGAMBIL VALUE PAGE DARI VUEX MODULE outlet
                return this.$store.state.gudang.selected_gudang_name
            },
            set(val) {
                //APABILA TERJADI PERUBAHAN VALUE DARI PAGE, MAKA STATE PAGE
                //DI VUEX JUGA AKAN DIUBAH
                this.$store.commit('gudang/ASSIGN_GUDANG_NAME', val)
            }
        },
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
        },

        formatGudang(value) {
            return value || '-'
        },

         formatBlok(value) {
            return value || '-'
        },

        setView(gudang_name,blok_name,gudang_id, blok_id, slot_id){
            console.log(gudang_id);
            this.selected_gudang_name = gudang_name;
            this.selected_blok = blok_name;
            this.selected_gudang_id = gudang_id;
            this.selected_blok_id = blok_id;
            this.selected_slot_name = 'group'+slot_id;
        },
        
        setURl(serial_code){
            
         this.url =  "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=http://localhost:8000/action/"+serial_code ;
        },
    }
}
</script>