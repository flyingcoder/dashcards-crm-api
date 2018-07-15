<template>
    <div>
        <div class="add-button" @click="$modal.show('add-report')">
            <span> ADD NEW </span>
            <button>
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                    width="22px" height="21px">
                    <path fill-rule="evenodd"  fill="rgb(255, 255, 255)"
                    d="M20.486,10.861 L12.470,10.861 L12.470,19.861 C12.470,20.302 12.031,20.661 11.490,20.661 C10.949,20.661 10.510,20.302 10.510,19.861 L10.510,10.861 L1.511,10.861 C1.069,10.861 0.710,10.423 0.710,9.882 C0.710,9.340 1.069,8.902 1.511,8.902 L10.510,8.902 L10.510,0.885 C10.510,0.443 10.949,0.085 11.490,0.085 C12.031,0.085 12.470,0.443 12.470,0.885 L12.470,8.902 L20.486,8.902 C20.928,8.902 21.286,9.340 21.286,9.882 C21.286,10.423 20.928,10.861 20.486,10.861 Z"/>
                </svg>
            </button>
        </div>
        <modal name="add-report" class="add-reports"  transition="nice-modal-fade" @before-open="beforeOpen">
            <section class="content">
                <v-layout row wrap>
                    <div class="buzz-modal-header"> {{ title }} </div>
                    <div class="buzz-scrollbar" id="buzz-scroll">
                        <el-form :model="form" ref="form" label-position="top" v-loading="isProcessing" style="width: 100%">
                            <div class="buzz-modal-content">
                                <el-form-item prop="name">
                                    <el-input type="text" v-model="form.name" placeholder="Untitled Report"></el-input>
                                </el-form-item>
                                <el-form-item  class="form-buttons">
                                    <el-button @click="submit"> {{ action }}</el-button>
                                    <el-button @click="$modal.hide('add-report')">Cancel</el-button>
                                </el-form-item>
                            </div>
                        </el-form>
                    </div>
                </v-layout>
            </section>
        </modal>
    </div>
</template>

<script>

    export default {
    	data: function () {
        	return {    
                title: 'Add New Report',
                action: 'Save',
                id: 0,
                oldName: '',
                isProcessing: false,
                form: {
                    name: '',
                },
        		error: {
        			name: [],
        		},
        	}
        },

        methods: {
            beforeOpen (event) {
                if(typeof event.params != 'undefined' && event.params.action == 'update') {
                    this.action = 'Update';
                    this.header = 'Edit Report';
                    this.id = event.params.data.id;
                    var vm = this;
                    axios.get('api/reports/'+this.id)
                        .then( response => {
                            this.form = response.data;
                        });
                }
            },
            submit(){
                this.$refs[form].validate((valid) => {
                    if (valid) {
                        alert('submit!');
                        if(this.action == 'Save'){
                            this.save();
                        }
                        else {
                            this.update();
                        }
                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                });
            },
            save: function () {
                this.isProcessing = true;
                axios.post('/api/reports/new',this.form)
                .then( response => {
                    this.isProcessing = false;
                    swal('Success!', 'Report is saved!', 'success');
                })
                .catch ( error => {
                    this.isProcessing = false;
                    if(error.response.status == 422){
                    this.errors = error.response.data.errors;
                    }
                })
            },
            update: function () {
                axios.put('/api/reports/'+this.id+'/edit', this.form)
                .then( response => {
                    this.isProcessing = false;
                    swal('Success!', 'Report is updated!', 'success');
                })
                .catch ( error => {
                    if(error.response.status == 422){
                    this.errors = error.response.data.errors;
                    }
                    this.isProcessing = false;        
                })
            },
            getReports(){
                axios.get('api/reports')
                .then( response => {
                    console.info(response.data);
                })
            },
        },
        mounted() {
            this.getReports();
        }
    }
</script>