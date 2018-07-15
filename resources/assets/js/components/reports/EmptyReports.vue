<template>
    <div class="empty-table">
        <img class="empty-icon" src="img/icons/empty/projects.svg">
        <!-- <span class="empty-icon" > 
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <path fill-rule="evenodd"
                d="M52.797,47.878 L14.098,47.878 C12.297,47.878 10.659,47.142 9.473,45.956 C8.288,44.771 7.552,43.134 7.552,41.333 L7.552,39.737 L7.405,39.737 C5.603,39.737 3.966,39.002 2.781,37.816 C1.595,36.631 0.858,34.993 0.858,33.192 L0.858,6.831 C0.858,5.030 1.595,3.393 2.781,2.207 C3.966,1.022 5.603,0.285 7.405,0.285 L46.104,0.285 C47.906,0.285 49.543,1.022 50.728,2.207 C51.914,3.393 52.650,5.030 52.650,6.831 L52.650,8.426 L52.797,8.426 C54.599,8.426 56.236,9.162 57.421,10.347 C58.607,11.533 59.343,13.170 59.343,14.972 L59.343,41.333 C59.343,43.134 58.607,44.771 57.421,45.956 C56.236,47.142 54.599,47.878 52.797,47.878 ZM49.499,6.831 C49.499,5.898 49.116,5.050 48.501,4.435 C47.885,3.819 47.036,3.436 46.104,3.436 L7.405,3.436 C6.473,3.436 5.624,3.819 5.008,4.435 C4.393,5.050 4.010,5.898 4.010,6.831 L4.010,33.192 C4.010,34.124 4.393,34.973 5.008,35.589 C5.624,36.204 6.473,36.587 7.405,36.587 L7.552,36.587 L7.552,14.972 C7.552,13.170 8.288,11.533 9.473,10.347 C10.659,9.162 12.297,8.426 14.098,8.426 L49.499,8.426 L49.499,6.831 ZM56.192,14.972 C56.192,14.040 55.809,13.191 55.194,12.575 C54.578,11.960 53.729,11.577 52.797,11.577 L14.098,11.577 C13.165,11.577 12.317,11.960 11.701,12.575 C11.085,13.191 10.703,14.040 10.703,14.972 L10.703,41.333 C10.703,42.264 11.085,43.113 11.701,43.729 C12.317,44.345 13.165,44.727 14.098,44.727 L52.797,44.727 C53.729,44.727 54.578,44.345 55.194,43.729 C55.809,43.113 56.192,42.264 56.192,41.333 L56.192,14.972 ZM51.678,42.224 C51.125,42.776 50.364,43.119 49.528,43.119 L44.461,43.119 C43.620,43.119 42.857,42.776 42.306,42.224 C41.754,41.673 41.411,40.910 41.411,40.070 L41.411,16.926 C41.411,16.086 41.754,15.323 42.306,14.771 C42.857,14.219 43.620,13.877 44.461,13.877 L49.528,13.877 C50.364,13.877 51.125,14.219 51.678,14.771 L51.684,14.771 C52.236,15.323 52.578,16.086 52.578,16.926 L52.578,40.070 C52.578,40.910 52.236,41.673 51.684,42.224 L51.678,42.224 ZM49.427,17.027 L44.562,17.027 L44.562,39.968 L49.427,39.968 L49.427,17.027 ZM35.781,43.119 L30.712,43.119 C29.872,43.119 29.109,42.776 28.557,42.224 C28.006,41.673 27.663,40.910 27.663,40.070 L27.663,22.341 C27.663,21.501 28.006,20.738 28.557,20.186 C29.109,19.634 29.872,19.292 30.712,19.292 L35.781,19.292 C36.621,19.292 37.384,19.634 37.936,20.186 C38.487,20.738 38.831,21.501 38.831,22.341 L38.831,40.070 C38.831,40.910 38.487,41.673 37.936,42.224 C37.384,42.776 36.621,43.119 35.781,43.119 ZM35.679,22.443 L30.814,22.443 L30.814,39.968 L35.679,39.968 L35.679,22.443 ZM22.095,43.119 L17.027,43.119 C16.187,43.119 15.424,42.776 14.872,42.224 C14.320,41.673 13.978,40.910 13.978,40.070 L13.978,33.959 C13.978,33.119 14.320,32.356 14.872,31.804 C15.424,31.252 16.187,30.910 17.027,30.910 L22.095,30.910 C22.935,30.910 23.699,31.252 24.250,31.804 C24.802,32.356 25.145,33.119 25.145,33.959 L25.145,40.070 C25.145,40.910 24.802,41.673 24.250,42.224 C23.699,42.776 22.935,43.119 22.095,43.119 ZM21.994,34.061 L17.129,34.061 L17.129,39.968 L21.994,39.968 L21.994,34.061 Z"/>
            </svg>
        </span> -->
        <div class="empty-button">
            <button class="add"  @click="$modal.show('add-report')">
                Add New Report
            </button>
        </div>

        <modal name="add-report_page" transition="nice-modal-fade" @before-open="beforeOpen">
            <section class="content">
                <div class="buzz-modal-header"> {{ title }} </div>
                <div class="buzz-scrollbar" id="buzz-scroll">
                    <el-form :model="form" :rules="rules" ref="form" label-position="top" v-loading="isProcessing" style="width: 100%">
                        <div class="buzz-modal-content report-content">
                            <el-form-item prop="name">
                                <el-input type="text" v-model="form.name" placeholder="Untitled Report"></el-input>
                            </el-form-item>
                            <el-form-item>
                                <el-select v-model="form.status" clearable placeholder="Select Status">
                                    <el-option
                                    v-for="item in status"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value">
                                    </el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item  class="form-buttons">
                                <el-button @click="submitForm('form')">Save</el-button>
                                <el-button @click="$modal.hide('add-report_page')">Cancel</el-button>
                            </el-form-item>
                        </div>
                    </el-form>
                </div>
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
                    status: '',
                },
                rules: {
                    name: [
                        { required: true, message: 'Please input Report Name', trigger: 'change' },
                    ],
                },
                status: [
                    {
                        value: 'Active',
                        label: 'Active'
                    }, {
                        value: 'Inactive',
                        label: 'Inactive'
                    },
                    ],
        		error: {
        			name: [],
                    status: [],
        		},
                config: {
                    toolbar: [
                      [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript' ]
                    ],
                    height: 500
                }
        	}
        },

        methods: {
            onBlur (e) {
                console.log(e)
            },
            onFocus (e) {
                console.log(e)
            },
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
            }
        },
        mounted() {
            
        }
    }
</script>