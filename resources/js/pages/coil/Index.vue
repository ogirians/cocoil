<template>
    <div class="container">
        <section class="content-header">
            <h1>
                Manage Coil
            </h1>
            <breadcrumb></breadcrumb>
            <br>
            <div v-if="$route.name == 'coil.data'">
                <label class="form-control-label"  for="input-file-import">Upload Excel File</label><br>
                <importexcel></importexcel>
            </div>
        </section>

        <section class="content">
            <div class="row">
                <router-view></router-view>
            </div>
        </section>
    </div>
</template>
<script>

    import Breadcrumb from '../../components/Breadcrumb.vue'
    import ImportExcel from './import.vue'

    export default {
        name: 'IndexGudang',
        components: {
            'breadcrumb': Breadcrumb,
            'importexcel' : ImportExcel
        }
    }
</script>