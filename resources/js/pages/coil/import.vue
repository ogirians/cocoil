<template>
            <div class="row">
                <div class="alert alert-success" v-if="import_ok">Data telah ditambahkan</div>
                    <div class="col-md-5" style="margin-bottom:5px">
                        <input type="file" class="form-control" :class="{ ' is-invalid' : error.message }" id="input-file-import" name="file_import" ref="import_file"  @change="onFileChange">
                        <div v-if="error.message" class="invalid-feedback"></div>
                        <p class="text-danger" v-if="error.message">{{ error.errors.import_file[0] }}</p>
                        <div v-if="error_excell">
                            <p class="text-danger" v-for="(error, index) in error_excell" :key="index">{{error}}</p>
                        </div>

                    </div>

                    <div class="col-md-3">
                        <button class="btn btn-primary btn-sm btn-flat" @click.prevent="proceedAction">
                        <i class="fa fa-save"></i> {{ loading ? 'Importing...':'Import data' }}
                        </button>
                         <a class="btn btn-primary btn-sm btn-flat" href="/import.xlsx">download .xlsx</a>
                    </div><br>

            </div>


</template>

<script>
import { mapActions} from 'vuex'
import $axios from '../../api.js'
export default {
    data() {
            return {
                error_excell:[],
                error:[],
                import_file: '',
                import_ok: false,
                loading: false,
             }
        },

        methods: {

            ...mapActions('coil',   [
                'getcoils'
            ]),

            onFileChange(e) {
            this.import_file = e.target.files[0];
            },

            proceedAction() {
                this.loading = true;
                let formData = new FormData();
                formData.append('import_file', this.import_file);


                $axios.post('/import', formData, {
                    headers: { 'content-type': 'multipart/form-data' }
                    })
                    .then(response => {
                        this.loading = false;
                        if(response.status === 200) {
                        // codes here after the file is upload successfully
                            //console.log(response.data.error_excell);
                            this.import_ok = true //AKTIFKAN ALERT JIKA BERHASIL
                            this.$refs.import_file.value = null
                            this.import_file = '';
                            this.error = [];
                            this.getcoils()
                            setTimeout(() => {
                                //BEBERAPA DETIK KEMUDIAN, SET DEFAULT ROLE USER

                                //MATIKAN ALERT
                                this.import_ok = false
                            }, 3000)

                        }

                        if(response.data.error_excell != null) {
                        // codes here after the file is upload successfully
                            console.log(response.data.error_excell);
                            
                            this.import_ok = false //AKTIFKAN ALERT JIKA BERHASIL
                            this.$refs.import_file.value = null
                            this.import_file = '';
                           
                            this.error_excell = response.data.error_excell;

                            this.getcoils()
                            setTimeout(() => {
                                //BEBERAPA DETIK KEMUDIAN, SET DEFAULT ROLE USER

                                //MATIKAN ALERT
                                this.import_ok = false
                            }, 3000)

                        }

                    })
                    .catch(error => {
                        // code here when an upload is not valid
                        this.loading = false
                        this.error = error.response.data
                        
                       
                        console.log(error.response.data.errors[0][0])

                    });

                },
        }

}
</script>
