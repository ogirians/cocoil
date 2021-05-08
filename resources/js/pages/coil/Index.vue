<template>
    <div class="container">
        <section class="content-header">
            <label class="form-control-label"  for="input-file-import">Upload Excel File</label>
            <div class="row">

                    <div class="col-md-5" style="margin-bottom:5px">
                        <input type="file" class="form-control" :class="{ ' is-invalid' : error.message }" id="input-file-import" name="file_import" ref="import_file"  @change="onFileChange">
                        <div v-if="error.message" class="invalid-feedback"></div>
                    </div>

                    <div class="col-md-3">
                        <button class="btn btn-primary btn-sm btn-flat" @click.prevent="proceedAction">
                        <i class="fa fa-save"></i> import data
                        </button>
                    </div>

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
    import $axios from '../../api.js'

    export default {
        name: 'IndexGudang',
        components: {
            'breadcrumb': Breadcrumb
        },
        data() {
            return {
                error: {},
                import_file: '',
             }
        },
        methods: {
        onFileChange(e) {
        this.import_file = e.target.files[0];
        },

        proceedAction() {
            let formData = new FormData();
            formData.append('import_file', this.import_file);

            $axios.post('/import', formData, {
                headers: { 'content-type': 'multipart/form-data' }
                })
                .then(response => {
                    if(response.status === 200) {
                    // codes here after the file is upload successfully
                    }
                })
                .catch(error => {
                    // code here when an upload is not valid
                    this.uploading = false
                    this.error = error.response.data
                    console.log('check error: ', this.error)
                });
            },
        }
    }
</script>