<template>
            <div>
				<modal name="example">
				<div class="basic-modal" style="width:60%">
					<h1 class="title">Buat blok baru</h1>
					<h4 class="title">masukkan panjang dan lebar denah blok</h4>
					<label>nama blok</label>
					<input class="form-control" type="text" placeholder="Default input" aria-label="default input example">
					<label>panjang</label>
					<input class="form-control" type="text" placeholder="Default input" aria-label="default input example">
					<label>lebar</label>
					<input class="form-control" type="text" placeholder="Default input" aria-label="default input example">
					<br>
					<button class="btn btn-primary" type="button">Simpan</button>
					<button class="btn btn-danger" type="button" @click="close">Close</button>
				</div>
				</modal>
			</div>
</template>
<script>

</script>