<template>
    <modal name="add-from-template" class="add-reports"  transition="nice-modal-fade" @before-open="beforeOpen">
        <section class="content">
            <v-layout row wrap>
                <div class="buzz-modal-header"> {{ title }} </div>
                <div class="buzz-scrollbar" id="buzz-scroll">
                    <el-form :model="form" ref="form" label-position="top" v-loading="isProcessing" style="width: 100%">
                        <div class="buzz-modal-content">
                            <el-form-item>
                                <el-select v-model="form.report" clearable placeholder="Select Report">
                                    <el-option
                                    v-for="item in fromTemplates"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value">
                                    </el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item  class="form-buttons">
                                <el-button @click="submit"> {{ action }}</el-button>
                                <el-button @click="$modal.hide('add-from-template')">Cancel</el-button>
                            </el-form-item>
                        </div>
                    </el-form>
                </div>
            </v-layout>
        </section>
    </modal>
</template>

<script>

    export default {
    	data: function () {
        	return {    
                title: 'Add From Report',
                action: 'Save',
                id: 0,
                oldName: '',
                isProcessing: false,
                form: {
                    report: '',
                },
                fromTemplates: [],
        		error: {
        			report: [],
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