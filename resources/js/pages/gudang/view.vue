<template>

	<div class="col-md-12">
		
			<div>
				<modal name="example">
				<div class="basic-modal" style="width:60%">
					<h1 v-if ="selected_blok_id != ''" class="title">Update Blok</h1>
					<h1 v-else class="title">Buat blok baru</h1>
					<h4 class="title">masukkan panjang dan lebar denah blok</h4>
						<input type="hidden" class="form-control" v-model="blok.gudang_id" :readonly="$route.name == 'blok.edit'">
					 <div class="form-group" :class="{ 'has-error': errors.name }">
						<label for="">Nama blok</label>
						<input type="text" class="form-control" v-model="blok.name" :readonly="$route.name == 'blok.edit'">
						<p class="text-danger" v-if="errors.name">{{ errors.name[0] }}</p>
					</div>
					<div class="form-group" :class="{ 'has-error': errors.panjang }">
						<label for="">panjang</label>
						<input type="number" class="form-control" v-model="blok.panjang">
						<p class="text-danger" v-if="errors.panjang">{{ errors.panjang[0] }}</p>
					</div>
					<div class="form-group" :class="{ 'has-error': errors.lebar }">
						<label for="">lebar</label>
						<input type="number" class="form-control" v-model="blok.lebar"/>
						<p class="text-danger" v-if="errors.lebar">{{ errors.lebar[0] }}</p>
					</div>
					<br>
				
						<button v-if ="selected_blok_id != ''" class="btn btn-primary btn-sm btn-flat" @click.prevent="ngupdateBlok(selected_blok_id)">
							<i class="fa fa-save"></i> update
						</button>
						<button v-else class="btn btn-primary btn-sm btn-flat" @click.prevent="submit">
							simpan
						</button>
						<button v-if ="selected_blok_id != ''" class="btn btn-danger btn-sm btn-flat" type="button" @click="delete_blok(selected_blok_id)"><i class="fa fa-trash"></i> hapus blok</button>
               		
						<button class="btn btn-secondary btn-sm btn-flat pull-right" type="button" @click="close">Close</button>
				</div>
				</modal>
			</div>
		<div class="row">
			<div class="col-md-3">
			<nav class="navbar-inverse sidebar" role="navigation" style="margin-bottom:20px;">
				<div class="container-fluid">
					<!-- Brand and toggle get grouped for better mobile display -->
					<div class="navbar-header">
							<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-sidebar-navbar-collapse-1">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
							<a class="navbar-brand" style="margin-top: 20px;">gudang/block</a>
					</div>
					<br>
					<!-- Collect the nav links, forms, and other content for toggling -->
					
					<div class="collapse navbar-collapse" id="bs-sidebar-navbar-collapse-1">
						<ul class="nav navbar-nav" id="listgudang">				
							<li v-for="(gudang,index) in gudangs.data" :key="index"> 
									<a href="#" class="dropdown-toggle" data-toggle="dropdown"> {{ gudang.name }}  <span class="caret"></span><span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-home"></span></a>
									<ul class="dropdown-menu forAnimate" role="menu">

										
										<li v-for="(blo, index) in gudang.blok" :key="index">
											
											<div v-for="(coile, index) in blo.coil_location" :key="index" >
												<div v-if="coile.coil_id != null">
													{{counting[index]= coile.coil.item_code }} - {{index}} 
												</div>
											</div>

										
											<a @click="selectBlok(blo.name, blo.id, gudang.id, gudang.name)">{{ blo.name }}-{{blo.id}} -  ( {{hitung.length}}/{{blo.coil_location.length}})
											</a>
										
										</li> 
										
										<button style="margin:5px" class="btn btn-secondary btn-sm btn-sm btn-flat " type="button" @click="openBaru(gudang.id)" href="#"><i class="glyphicon glyphicon-plus" ></i> blok baru {{gudang.id}}</button>
										
									</ul>
							</li>					
						</ul>
					</div>
					
				</div>
			</nav>
			<nav v-if="show_panel_coil == true" class="navbar-inverse sidebar "  role="navigation" style="width:100%;">
				<div class="container-fluid">
					<!-- Brand and toggle get grouped for better mobile display -->
					<div class="navbar-header">
							<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-sidebar-navbar-collapse-2">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
							<a class="navbar-brand" style="margin-top: 20px;">Unsetted Coil</a>
					</div>
					<div class="">
                    	<input type="text" class="form-control" placeholder="Cari..." v-model="search">
                	</div>
					<br>
					<!-- Collect the nav links, forms, and other content for toggling -->
					
					<div class="collapse navbar-collapse" id="bs-sidebar-navbar-collapse-2">
						<ul class="nav navbar-nav  coil-data" style="width:100%;" id="listgudang">				
							<li v-for="(coildata,index) in coils.data" :key="index"> 
									<a @click="selectCoil(coildata.item_code,coildata.serial_Code)" href="#" class="dropdown-toggle" data-toggle="dropdown"> {{ coildata.serial_Code }}  </a>
									
							</li>					
						</ul>
					</div>
					
				</div>
			</nav>
			<nav v-if="show_panel_info == true" class="navbar-inverse sidebar " role="navigation" style="width:100%;">
				<div class="container-fluid">
					<!-- Brand and toggle get grouped for better mobile display -->
					<div class="navbar-header">
							<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-sidebar-navbar-collapse-2">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
							<a class="navbar-brand" style="margin-top: 20px;">Info Coil</a>
					</div>
					
					<br>
					<!-- Collect the nav links, forms, and other content for toggling -->
					
					<div class="collapse navbar-collapse" id="bs-sidebar-navbar-collapse-2">
						<ul class="nav navbar-nav  coil-data" style="width:100%;" id="listgudang">				
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
					</div>
					
				</div>
			</nav>
			</div>
			
			<div class="col-md-9">
				<div class="panel">
					<div class="row" style="margin-right: 0px;margin-left: -15px;">
						<div class="col-md-10">
							<h3 style="margin : 15px; " v-if="selected_gudang_id == ''">pilih blok</h3>
							<h3 style="margin: 15px;margin-bottom: 0px;" v-else>Gudang {{selected_gudang_name}} : {{selected_blok}}</h3>							
						</div>
						<div class="col-md-2 ">
							<button v-if="selected_gudang_id != ''"  @click="setting()" style="margin: 15px;padding-right: 10px;margin-right: 15px;margin-bottom: 15px;" type="button" class="btn btn-secondary btn-sm btn-sm btn-flat"><i class="glyphicon glyphicon-wrench"></i> blok setting</button>
						</div>
					</div>
					
					
					<br>
						<div id="stage-parent">
							<div class="panel" id="container" style="background: #eee">
								<maps-gudang v-if="selected_gudang_id != ''">
								</maps-gudang>
							</div>
						</div>
					
				</div>
			</div>
		</div>

</div>

</template>
 
<script>
import MapsGudang from './map.vue'
import { mapActions, mapMutations, mapState } from 'vuex'
import { Modal, PUSH, POP } from 'vuex-modal'
import 'vuex-modal/dist/vuex-modal.css'


export default {
	 //el : listgudang,

	 name : 'viewGudang',

	 data(){
		 return{
			 selected_blok:'',
			 //selected_blok_id:'',
			 blok_id:'',
			 //selected_gudang_id :'',
			 selected_gudang_name : '',
			 modal_stat: false,

			 search: '',
			 hitung : [],
		 }
	 },

	watch: {
       
        search() {
            //APABILA VALUE DARI SEARCH BERUBAH MAKA AKAN MEMINTA DATA
            //SESUAI DENGAN DATA YANG SEDANG DICARI
            this.getcoilsnoplace(this.search)
        }
    },

	 created(){
		 this.getGudangs(),
		 this.getBloks()
		 //this.getcoils()
		 this.getcoilsnoplace()
	 },

	 computed: {
		 ...mapState (['errors']),

		 ...mapState('blok', {
			 blok : state => state.blok
		 }),

		  ...mapState('blok', {
			 bloks : state => state.bloks
		 }),

		 ...mapState('gudang', {
			 gudangs : state => state.gudangs
		 }),

		  ...mapState('coil', {
			 coils : state => state.coils
		 }),

		  ...mapState('coil', {
			 coil : state => state.coil
		 }),

		

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
		 show_panel_coil: {
            get() {
                //MENGAMBIL VALUE PAGE DARI VUEX MODULE outlet
                return this.$store.state.gudang.show_panel_coil
            },
            set(val) {
                //APABILA TERJADI PERUBAHAN VALUE DARI PAGE, MAKA STATE PAGE
                //DI VUEX JUGA AKAN DIUBAH
                this.$store.commit('gudang/ASSIGN_PANEL_COIL', val)
            }
        },

        show_panel_info: {
            get() {
                //MENGAMBIL VALUE PAGE DARI VUEX MODULE outlet
                return this.$store.state.gudang.show_panel_info
            },
            set(val) {
                //APABILA TERJADI PERUBAHAN VALUE DARI PAGE, MAKA STATE PAGE
                //DI VUEX JUGA AKAN DIUBAH
                this.$store.commit('gudang/ASSIGN_PANEL_INFO', val)
            }
        },

		
	 },

	 methods : {
		 ...mapActions('gudang',['getGudangs']),
		 ...mapMutations(['CLEAR_ERRORS']),
		 ...mapMutations('blok',['CLEAR_FORM']),
		 ...mapActions('blok',['submitBlok', 'getBloks','editBlok','removeBlok','updateBlok']),
		 ...mapActions('coil',['getcoils','getcoilsnoplace','detailcoil']),
		 
		 

		counting(coil){

			//this.count.push(coil);
			console.log(coil);
			this.hitung.push(coil);
			//return this.count;
		},

		open (id) {
      		this.$store.dispatch(PUSH, { name: 'example' })
			this.blok.gudang_id = id;
			this.modal_stat =true;
			
			
    	},
 
		close () {
			this.$store.dispatch(POP)
			this.$store.commit('blok/CLEAR_FORM')
			// /this.selected_blok_id ='';
			this.modal_stat =false;
			this.CLEAR_ERRORS()
			 
		},

		submit(){
			
			
			this.submitBlok().then(() => {
					this.getBloks()
					this.getGudangs()
					this.close()

					this.$swal({
						type: 'success',
						title: 'Blok ditambahkan',
						showConfirmButton: false,
						timer: 1500
					})
			})
			
		},

		selectBlok(nama,id,idGudang,namaGudang){
			this.selected_blok = nama;
			this.selected_blok_id = id;
			this.blok_id= id;
			this.selected_gudang_id=idGudang;
			this.selected_gudang_name=namaGudang;
		},

		selectCoil(id,serial_code){
			this.selected_coil_id = id ;
			this.selected_serial_code = serial_code ;
			console.log(this.selected_coil_id);
		},

		setting(){
			this.selected_blok_id = this.blok_id;
			this.editBlok(this.blok_id);
			this.open(this.idGudang);
		},

		openBaru(idblok){
			this.selected_gudang_id = '';
			this.selected_blok_id = '';
			this.$store.commit('blok/CLEAR_FORM');
			this.open(idblok);
			

		},
		ngupdateBlok(id){
			 this.updateBlok(id).then(() => {
                	this.getGudangs()
					this.close()
					this.$swal({
					type: 'success',
					title: 'Blok diupdate',
					showConfirmButton: false,
					timer: 1500
					})
                })
		},

		delete_blok(id){

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
                    this.selected_gudang_id ='';
					this.removeBlok(id)
					this.getGudangs()
					this.close()
					this.$swal({
					type: 'success',
					title: 'Blok dihapus',
					showConfirmButton: false,
					timer: 1500
					})
                }
            })
			
		}
		

	 },
     components: {
            'maps-gudang': MapsGudang,
			Modal,
    },

	destroyed(){
		this.CLEAR_FORM()	
	},
	
}
</script>



<style>
    body,html{
		height: 100%;
	}

	/* remove outer padding */
	.main .row{
		padding: 0px;
		margin: 0px;
	}

	/*Remove rounded coners*/

	nav.sidebar.navbar {
		border-radius: 0px;
	}

	.navbar-brand > a:hover,
	.navbar-brand > a:focus{
		color: rgb(0, 0, 0);
	}
	.navbar-inverse .navbar-nav > li > a:hover,
	.navbar-inverse .navbar-nav > li > a:focus {
			color: rgb(0, 0, 0);
	    background: #f7f7f7;
	}

	.navbar-inverse .navbar-nav > .open > a, .navbar-inverse .navbar-nav > .open > a:focus, .navbar-inverse .navbar-nav > .open > a:hover {
    color: #fff;
    background-color: #3c8dbc;
	}
	
	.navbar-inverse .navbar-brand #listgudang :hover {
    color: rgb(0, 0, 0);
   
	}

	.navbar-inverse .navbar-nav #listgudang > li > a:hover  {
    color: rgba(68, 60, 60, 0.205);
    
	}

	nav.sidebar, .main{
		-webkit-transition: margin 200ms ease-out;
	    -moz-transition: margin 200ms ease-out;
	    -o-transition: margin 200ms ease-out;
	    transition: margin 200ms ease-out;
	}

	/* Add gap to nav and right windows.*/
	.main{
		padding: 10px 10px 0 10px;
	}

	/* .....NavBar: Icon only with coloring/layout.....*/

	/*small/medium side display*/
	@media (min-width: 991px) {

		/*Allow main to be next to Nav*/
		.main{
			position: absolute;
			width: calc(100% - 40px); /*keeps 100% minus nav size*/
			margin-left: 40px;
			float: right;
		}

        .navbar-inverse {
            background-color: #fff0;
            border-color : #fff0;

        }

        .skin-blue .sidebar a {
            color: black;
        }

        .navbar-inverse .navbar-nav > li > a {
            color: black;
        }
		/*lets nav bar to be showed on mouseover*/
		nav.sidebar:hover + .main{
			margin-left: 200px;
		}

		/*Center Brand*/
		nav.sidebar.navbar.sidebar>.container .navbar-brand, .navbar>.container-fluid .navbar-brand {
			margin-left: 0px;
		}
		/*Center Brand*/
		nav.sidebar .navbar-brand, nav.sidebar .navbar-header{
			text-align: center;
			width: 100%;
			margin-left: 0px;
		}

		/*Center Icons*/
		nav.sidebar a{
			padding-right: 13px;
		}

		/*adds border top to first nav box */
		nav.sidebar .navbar-nav > li:first-child{
			border-top: 1px #e5e5e5 solid;
		}

		/*adds border to bottom nav boxes*/
		nav.sidebar .navbar-nav > li{
			border-bottom: 1px #e5e5e5 solid;
		}

		/* Colors/style dropdown box*/
		nav.sidebar .navbar-nav .open .dropdown-menu {
			position: static;
			float: none;
			width: auto;
			margin-top: 0;
			background-color: transparent;
			border: 0;
			-webkit-box-shadow: none;
			box-shadow: none;
		}

		/*allows nav box to use 100% width*/
		nav.sidebar .navbar-collapse, nav.sidebar .container-fluid{
			padding: 15px;
		}

		/*colors dropdown box text */
		.navbar-inverse .navbar-nav .open .dropdown-menu>li>a {
			color: #000;
			background: cornsilk;
			margin: 5px;
		}

		/*gives sidebar width/height*/
		nav.sidebar{
			width: auto;
			height: 100%;
			margin-left: -160px;
			float: left;
			z-index: 8000;
			margin-bottom: 0px;
		}

		/*give sidebar 100% width;*/
		nav.sidebar li {
			width: 100%;
		}

		/* Move nav to full on mouse over*/
		nav.sidebar:hover{
			margin-left: 0px;
		}
		/*for hiden things when navbar hidden*/
		.forAnimate{
			opacity: 0;
		}
	}

	/* .....NavBar: Fully showing nav bar..... */

	@media (min-width:992px) {

		/*Allow main to be next to Nav*/
		.main{
			width: calc(100% - 200px); /*keeps 100% minus nav size*/
			margin-left: 200px;
		}

		/*Show all nav*/
		nav.sidebar{
			margin-left: 0px;
			float: left;
			background :white;
		}
		/*Show hidden items on nav*/
		nav.sidebar .forAnimate{
			opacity: 1;
		}
	}

	nav.sidebar .navbar-nav .open .dropdown-menu>li>a:hover, nav.sidebar .navbar-nav .open .dropdown-menu>li>a:focus {
		color: #000;
		background-color: #9f997f;
	}

	nav:hover .forAnimate{
		opacity: 1;
	}
	section{
		padding-left: 15px;
	}

    .maps {
        width: 100%;
        height: 500px;
        background-color :grey;
    }

	
    .coil-data{
        max-height:300px;
        overflow-y:scroll;
    }

    </style>

