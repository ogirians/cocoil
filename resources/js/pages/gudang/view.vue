<template>
<div class="container">
 <div class="row">
<div class="col-md-3">
 <nav class="navbar navbar-inverse sidebar" role="navigation">
    <div class="container-fluid">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-sidebar-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#">gudang/block</a>
		</div>
		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-sidebar-navbar-collapse-1">
			<ul class="nav navbar-nav" id="listgudang">				
				 <li v-for="(gudang,index) in gudangs.data" :key="index"> 
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"> {{gudang.name }}  <span class="caret"></span><span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon glyphicon-cog"></span></a>
						<ul class="dropdown-menu forAnimate" role="menu">
							<li><a href="#">blok 1</a></li> 
							<li><a href="#"><i class="glyphicon glyphicon-plus"></i> tambah blok baru</a></li>
						</ul>
				</li>
				
			</ul>
		</div>
	</div>
</nav>
</div>
<div class="col-md-8">
        <maps-gudang>
        </maps-gudang>
</div>
 </div>
</div>
</template>
<script>
import MapsGudang from './map.vue'
import { mapActions, mapState } from 'vuex'

export default {
	 //el : listgudang,

	 name : 'viewGudang',

	 created(){
		 this.getGudangs()
	 },

	 computed: {
		 ...mapState('gudang', {
			 gudangs : state => state.gudangs
		 }),
	 },
	 methods : {
		 ...mapActions('gudang',['getGudangs']),
	 },
     components: {
            'maps-gudang': MapsGudang
    },
	
}
</script>