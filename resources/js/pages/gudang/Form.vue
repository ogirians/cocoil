<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors.code }">
            <label for="">Kode gudang</label>
            <input type="text" class="form-control" v-model="gudang.code" :readonly="$route.name == 'gudang.edit'">
            <p class="text-danger" v-if="errors.code">{{ errors.code[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.name }">
            <label for="">Nama gudang</label>
            <input type="text" class="form-control" v-model="gudang.name">
            <p class="text-danger" v-if="errors.name">{{ errors.name[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.address }">
            <label for="">Alamat</label>
            <textarea cols="5" rows="5" class="form-control" v-model="gudang.address"></textarea>
            <p class="text-danger" v-if="errors.address">{{ errors.address[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.phone }">
            <label for="">No Telp</label>
            <input type="text" class="form-control" v-model="gudang.phone">
            <p class="text-danger" v-if="errors.phone">{{ errors.phone[0] }}</p>
        </div>
        <div class="form-group">
            <input type="checkbox" v-model="gudang.status">
            <label for=""> Set Active</label>
        </div>
    </div>
</template>

<script>
import { mapState, mapMutations } from 'vuex'
export default {
    name: 'FormGudang',
    computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('gudang', { 
            gudang: state => state.gudang //MENGAMBIL STATE gudang
        })
    },
    methods: {
        ...mapMutations('gudang', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
    },
    //KETIKA PAGE INI DITINGGALKAN MAKA
    destroyed() {
        //FORM DI BERSIHKAN
        this.CLEAR_FORM()
    }
}
</script>