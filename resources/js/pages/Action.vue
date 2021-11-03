<!-- HTML SECTION -->
<template>

    <div class="container">
         <div class="text-center">
                                <b-spinner label="Spinning"></b-spinner>
                                <b-spinner type="grow" label="Spinning"></b-spinner>
                                <b-spinner variant="primary" label="Spinning"></b-spinner>
                                <b-spinner variant="primary" type="grow" label="Spinning"></b-spinner>
                                <b-spinner variant="success" label="Spinning"></b-spinner>
                                <b-spinner variant="success" type="grow" label="Spinning"></b-spinner>
                                </div>
        <div class="login-box">
            <div class="login-logo">
                <router-link :to="{ name: 'home' }">Serial Code  <b>{{this.$route.params.id}}</b></router-link>
            </div>
                <div class="panel">
                      <ul class="nav" style="width:100%;" id="listgudang">				
							<li> 
									<a><b>item kategori</b> : {{coil.item_category}}</a>
									<a><b>item tipe</b> : {{coil.item_type}}</a>
									<a><b>item code</b> : {{coil.item_code}}</a>
									<a><b>deskripsi</b> : {{coil.item_description}}</a>
									<a><b>serial code</b> : {{coil.serial_Code}}</a>
									<a><b>id coil</b> : {{coil.ID_coil}}</a>
									<a><b>balance</b> : {{coil.balance}}</a>
                                   
							</li>					
						</ul>
                    <br>

                    <div class="panel-heading" v-if="coil.location_id != null">
                        <h3 class="panel-title">pilih aksi</h3>
                    </div> 
                    <div class="panel-heading" v-else>
                        <h3 class="panel-title">Coil belum punya lokasi</h3>
                    </div> 
                    <div class="panel-body" v-if="coil.location_id != null">
                      
                        <div class="form-group">
                            <label for="">aksi <span style="color:red">*</span></label>
                            <select v-model="action_detail.action_tipe" class="form-control" >
                                <option value="">Pilih</option>
                                <option value="jual">jual</option>
                                <option value="produksi">produksi</option>
                                <option value="pindah">pindah gudang</option>
                            </select>
                            <p class="text-danger" ></p>
                             <p class="text-danger" v-if="errors.action_tipe">{{ errors.action_tipe[0] }}</p>
                        </div>
                        <label for="">NO Dokumen <span style="color:red">*</span></label>
                        <div class="form-group has-feedback" :class="{'has-error': errors.no_dokumen}">
                            <input v-model="action_detail.no_dokumen" type="text" class="form-control">
                           <p class="text-danger" v-if="errors.no_dokumen">{{ errors.no_dokumen [0] }}</p>
                            
                        </div>
                         <label for="">Catatan</label>
                        <div class="form-group has-feedback" :class="{'has-error': errors.no_dokumen}">
                            <textarea v-model="action_detail.catatan" type="textarea" class="form-control"></textarea>
                           <p class="text-danger" v-if="errors.catatan">{{ errors.catatan [0] }}</p>
                        </div>

                         <label for="">password <span style="color:red">*</span></label>
                        <div class="form-group has-feedback" :class="{'has-error': errors.password}">
                            <input v-model="action_detail.password" type="password" class="form-control" placeholder="Password" >
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                            <p class="text-danger" v-if="errors.password">{{ errors.password[0] }}</p>
                        </div>
                        <div class="alert alert-danger" v-if="errors.invalid">{{ errors.invalid }}</div>
                      
                        <div class="form-group">
                            <button class="btn btn-success btn-sm" @click.prevent="simpanAct">Simpan</button>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</template>

<!-- JAVASCRIPT SECTION -->
<script>
import { mapActions, mapMutations, mapGetters, mapState } from 'vuex';
export default {
    data() {
        return {
            
        }
    },
    //SEBELUM COMPONENT DI-RENDER
    created() {
        //KITA MELAKUKAN PENGECEKAN JIKA SUDAH LOGIN DIMANA VALUE isAuth BERNILAI TRUE
        //if (this.isAuth) {
        //    //MAKA DI-DIRECT KE ROUTE DENGAN NAME home
        //    this.$router.push({ name: 'home' })
        //}
        
        this.detailcoil(this.$route.params.id);
        this.ASSIGN_ACTION_SERIALCODE(this.$route.params.id);
        
    },
    computed: {
        ...mapGetters(['isAuth']), //MENGAMBIL GETTERS isAuth DARI VUEX
      	...mapState(['errors']),

         ...mapState('coil', {
			 coil : state => state.coil
		 }),  

          ...mapState('action_coil', { 
            action_detail: state => state.action_detail //MENGAMBIL STATE gudang
        })

    },
    methods: {
        ...mapActions('auth', ['submit']), //MENGISIASI FUNGSI submit() DARI VUEX AGAR DAPAT DIGUNAKAN PADA COMPONENT TERKAIT. submit() BERASAL DARI ACTION PADA FOLDER STORES/auth.js
        ...mapMutations(['CLEAR_ERRORS']),
        ...mapActions('user', ['getUserLogin']),
        ...mapActions('coil',['getcoils','getcoilsnoplace','detailcoil']),

        ...mapMutations('action_coil', ['ASSIGN_ACTION_SERIALCODE']), //PANGGIL MUTATIONS CLEAR_FORM
        ...mapActions('action_coil',['simpanAction']),

      	//KETIKA TOMBOL LOGIN DITEKAN, MAKA AKAN MEMINCU METHODS postLogin()
      
        simpanAct(){
            this.simpanAction().then(() => {
                    //APABILA BERHASIL MAKA AKAN DI-DIRECT KE HALAMAN /outlets
                    this.$router.push({ name: 'post_Action' })
                    this.CLEAR_ERRORS()
                });
        }


    },
    destroyed() {
    //this.getUserLogin()
    }
}
</script>