<template>
    <div>
        <div class="form-group" :class="{ 'has-error': errors.name }">
            <label for="">Nama Lengkap</label>
            <input type="text" class="form-control" v-model="employee.name" :readonly="$route.name == 'gudangs.edit'">
            <p class="text-danger" v-if="errors.name">{{ errors.name[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.email }">
            <label for="">Email</label>
            <input type="text" class="form-control" v-model="employee.email">
            <p class="text-danger" v-if="errors.email">{{ errors.email[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.password }">
            <label for="">Password</label>
            <input type="password" class="form-control" v-model="employee.password">
            <p class="text-warning">Leave blank if you don't want to change password</p>
            <p class="text-danger" v-if="errors.password">{{ errors.password[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.gudang_id }">
            <label for="">gudang</label>
            <select name="gudang_id" class="form-control" v-model="employee.gudang_id">
                <option value="">Pilih</option>
                <option v-for="(row, index) in gudangs.data" :value="row.id" :key="index">{{ row.name }}</option>
            </select>
            <p class="text-danger" v-if="errors.gudang_id">{{ errors.gudang_id[0] }}</p>
        </div>
        <div class="form-group" :class="{ 'has-error': errors.photo }">
            <label for="">Foto</label>
            <input type="file" class="form-control" accept="image/*" @change="uploadImage($event)" id="file-input">
            <p class="text-warning">Leave blank if you don't want to change photo</p>
            <p class="text-danger" v-if="errors.photo">{{ errors.photo[0] }}</p>
        </div>
    </div>
</template>

<script>
import { mapState, mapMutations, mapActions } from 'vuex'
export default {
    name: 'FormEmployee',
    created() {
        this.getGudangs()
        if (this.$route.name == 'employee.edit') {
            this.editEmployee(this.$route.params.id).then((res) => {
                this.employee = {
                    name: res.data.name,
                    email: res.data.email,
                    password: '',
                    photo: '',
                    gudang_id: res.data.gudang_id
                }
            })
        }
    },
    data() {
        return {
            employee: {
                name: '',
                email: '',
                password: '',
                photo: '',
                gudang_id: ''
            }
        }
    },
    computed: {
        ...mapState(['errors']),
        ...mapState('gudang', {
            gudangs: state => state.gudangs
        })
    },
    methods: {
        ...mapActions('gudang', ['getGudangs']),
        ...mapActions('employee', ['submitEmployee', 'editEmployee', 'updateEmployee']),
        ...mapMutations('employee', ['SET_ID_UPDATE']),
        uploadImage(event) {
            this.employee.photo = event.target.files[0]
        },
        submit() {
            let form = new FormData()
            form.append('name', this.employee.name)
            form.append('email', this.employee.email)
            form.append('password', this.employee.password)
            form.append('gudang_id', this.employee.gudang_id)
            form.append('photo', this.employee.photo)

            if (this.$route.name == 'employee.add') {
                this.submitEmployee(form).then(() => {
                    this.employee = {
                        name: '',
                        email: '',
                        password: '',
                        photo: '',
                        gudang_id: ''
                    }
                    this.$router.push({ name: 'employee.data' })
                })
            } else if (this.$route.name == 'employee.edit') {
                this.SET_ID_UPDATE(this.$route.params.id)
                this.updateEmployee(form).then(() => {
                    this.employee = {
                        name: '',
                        email: '',
                        password: '',
                        photo: '',
                        gudang_id: ''
                    }
                    this.$router.push({ name: 'employee.data' })
                })
            }
        }
    }
}
</script>
