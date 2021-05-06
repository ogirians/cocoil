<template>
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Add New Employee</h3>
            </div>
            <div class="panel-body">
                <employee-form ref="FormEmployee"></employee-form>
                <div class="form-group">
                    <button class="btn btn-primary btn-sm btn-flat" @click.prevent="submit">
                        <i class="fa fa-save"></i> Add New
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import { mapActions, mapState, mapMutations } from 'vuex'
    import FormEmployee from './Form.vue'
    export default {
        name: 'AddEmployee',
        methods: {
            submit() {
                this.$refs.FormEmployee.submit()
            }
        },
        components: {
            'employee-form': FormEmployee
        }
    }
</script>
