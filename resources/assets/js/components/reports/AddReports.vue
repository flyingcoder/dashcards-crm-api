<template>
    <li>
        <div class="add-button" @click="$modal.show('add-report')">
            <span> ADD NEW </span>
            <button>
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                    width="20px" height="20px">
                    <path fill-rule="evenodd"  fill="rgb(255, 255, 255)"
                    d="M18.852,10.789 L11.590,10.789 L11.590,19.039 C11.590,19.444 11.193,19.773 10.703,19.773 C10.212,19.773 9.815,19.444 9.815,19.039 L9.815,10.789 L1.663,10.789 C1.262,10.789 0.937,10.387 0.937,9.892 C0.937,9.395 1.262,8.993 1.663,8.993 L9.815,8.993 L9.815,1.645 C9.815,1.240 10.212,0.911 10.703,0.911 C11.193,0.911 11.590,1.240 11.590,1.645 L11.590,8.993 L18.852,8.993 C19.252,8.993 19.577,9.395 19.577,9.892 C19.577,10.387 19.252,10.789 18.852,10.789 Z"/>
                </svg>
            </button>
        </div>

        <modal name="add-report" transition="nice-modal-fade" @before-open="beforeOpen">
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
                                <el-button @click="$modal.hide('add-report')">Cancel</el-button>
                            </el-form-item>
                        </div>
                    </el-form>
                </div>
            </section>
        </modal>
    </li>
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
                status: [{
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