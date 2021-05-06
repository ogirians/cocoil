<template>
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <router-link :to="{ name: 'employee.add' }" class="btn btn-primary btn-sm btn-flat">Tambah</router-link>
                <div class="pull-right">
                    <input type="text" class="form-control" placeholder="Cari..." v-model="search">
                </div>
            </div>
            <div class="panel-body">
                <b-table striped hover bordered :items="Employees.data" :fields="fields" show-empty>
                    <template slot="gudang_id" slot-scope="row">
                        {{ row.item.gudang.name }}
                    </template>
                    <template slot="actions" slot-scope="row">
                        <router-link :to="{ name: 'employee.edit', params: {id: row.item.id} }" class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i></router-link>
                        <button class="btn btn-danger btn-sm" @click="deleteEmployee(row.item.id)"><i class="fa fa-trash"></i></button>
                    </template>
                </b-table>

                <div class="row">
                    <div class="col-md-6">
                        <p v-if="Employees.data"><i class="fa fa-bars"></i> {{ Employees.data.length }} item dari {{ Employees.meta.total }} total data</p>
                    </div>
                    <div class="col-md-6">
                        <div class="pull-right">
                            <b-pagination
                                v-model="page"
                                :total-rows="Employees.meta.total"
                                :per-page="Employees.meta.per_page"
                                aria-controls="Employees"
                                v-if="Employees.data && Employees.data.length > 0"
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
    name: 'DataEmployee',
    created() {
        this.getEmployees()
    },
    data() {
        return {
            fields: [
                { key: 'name', label: 'Nama Lengkap' },
                { key: 'email', label: 'Email' },
                { key: 'gudang_id', label: 'Gudang' },
                { key: 'actions', label: 'Aksi' }
            ],
            search: ''
        }
    },
    computed: {
        ...mapState('employee', {
            Employees: state => state.Employees
        }),
        page: {
            get() {
                return this.$store.state.employee.page
            },
            set(val) {
                this.$store.commit('Employee/SET_PAGE', val)
            }
        }
    },
    watch: {
        page() {
            this.getEmployees()
        },
        search() {
            this.getEmployees(this.search)
        }
    },
    methods: {
        ...mapActions('employee', ['getEmployees', 'removeEmployee']),
        deleteEmployee(id) {
            this.$swal({
                title: 'Kamu Yakin?',
                text: "Tindakan ini akan menghapus secara permanent!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Iya, Lanjutkan!'
            }).then((result) => {
                if (result.value) {
                    this.removeEmployee(id)
                }
            })
        }
    }
}
</script>
