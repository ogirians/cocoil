<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors.item_category }">
            <label for="">item_category</label>
            <input type="text" class="form-control" v-model="coil.item_category">
            <p class="text-danger" v-if="errors.item_category">{{ errors.item_category[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.item_type }">
            <label for="">item_type</label>
            <input type="text" class="form-control" v-model="coil.item_type">
            <p class="text-danger" v-if="errors.item_type">{{ errors.item_type[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.item_code }">
            <label for="">item_code</label>
            <textarea cols="5" rows="5" class="form-control" v-model="coil.item_code"></textarea>
            <p class="text-danger" v-if="errors.item_code">{{ errors.item_code[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.item_description }">
            <label for="">item_description</label>
            <input type="text" class="form-control" v-model="coil.item_description">
            <p class="text-danger" v-if="errors.item_description">{{ errors.item_description[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.serial_Code }">
            <label for="">serial_Code</label>
            <input type="text" class="form-control" v-model="coil.serial_Code">
            <p class="text-danger" v-if="errors.serial_Code">{{ errors.serial_Code[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.id_coil }">
            <label for="">id_coil</label>
            <input type="text" class="form-control" v-model="coil.id_coil">
            <p class="text-danger" v-if="errors.id_coil">{{ errors.id_coil[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.balance }">
            <label for="">balance</label>
            <input type="text" class="form-control" v-model="coil.balance">
            <p class="text-danger" v-if="errors.balance">{{ errors.balance[0] }}</p>
        </div>

    </div>
</template>

<script>
import { mapState, mapMutations } from 'vuex'
export default {
    name: 'Formcoil',
    computed: {
        ...mapState(['errors']), //MENGAMBIL STATE ERRORS
        ...mapState('coil', {
            coil: state => state.coil //MENGAMBIL STATE coil
        })
    },
    methods: {
        ...mapMutations('coil', ['CLEAR_FORM']), //PANGGIL MUTATIONS CLEAR_FORM
    },
    //KETIKA PAGE INI DITINGGALKAN MAKA
    destroyed() {
        //FORM DI BERSIHKAN
        this.CLEAR_FORM()
    }
}
</script>