<template>
    <li>
        <div class="add-button">
            <span> ADD NEW </span>
            <button  @click="$modal.show('add-project')">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                    width="20px" height="20px">
                    <path fill-rule="evenodd"  fill="rgb(255, 255, 255)"
                    d="M18.852,10.789 L11.590,10.789 L11.590,19.039 C11.590,19.444 11.193,19.773 10.703,19.773 C10.212,19.773 9.815,19.444 9.815,19.039 L9.815,10.789 L1.663,10.789 C1.262,10.789 0.937,10.387 0.937,9.892 C0.937,9.395 1.262,8.993 1.663,8.993 L9.815,8.993 L9.815,1.645 C9.815,1.240 10.212,0.911 10.703,0.911 C11.193,0.911 11.590,1.240 11.590,1.645 L11.590,8.993 L18.852,8.993 C19.252,8.993 19.577,9.395 19.577,9.892 C19.577,10.387 19.252,10.789 18.852,10.789 Z"/>
                </svg>
            </button>
        </div>

        <modal name="add-project" transition="nice-modal-fade" @before-open="beforeOpen">
            <section class="content">
                <div class="buzz-modal-header"> {{ title }} </div>
                <div class="buzz-scrollbar" id="buzz-scroll">
                    <el-form :model="form" ref="projectForm" label-position="top" v-loading="isProcessing" style="width: 100%">
                        <div class="buzz-modal-option">
                            <el-form-item  class="option">
                                <div class="option-item"> 
                                     <el-dropdown trigger="click" placement="bottom" class="member-option">
                                        <el-button size="small" class="el-dropdown-link"> 
                                            <img src="/img/icons/modal/members.png" alt="" class="button-icon">   
                                            Members 
                                        </el-button>
                                        <el-dropdown-menu slot="dropdown" class="member-option-dropdown">
                                           
                                        </el-dropdown-menu>
                                    </el-dropdown>
                                </div>
                                <div class="option-item">
                                    <div class="date-option">
                                        <img src="img/icons/modal/date.svg" alt="" class="button-icon">                                    
                                        <el-date-picker
                                            :clearable="false"
                                            format="yyyy-MM-dd"
                                            value-format="yyyy-MM-dd"
                                            v-model="form.end_at"
                                            type="date"
                                            placeholder="Due Date">
                                        </el-date-picker>
                                    </div>
                                </div>
                                <div class="option-item">
                                    <div class="file-upload">
                                        <img src="img/icons/modal/attachment.svg" alt="" class="button-icon"> 
                                        <el-upload
                                            class=""
                                            ref="upload"
                                            action=""
                                            :auto-upload="false">
                                            <el-button slot="trigger">
                                                Attachment 
                                            </el-button>
                                            <!-- <el-button style="margin-left: 10px;" size="small" type="success" @click="submitUpload">upload to server</el-button> -->
                                            <!-- <div class="el-upload__tip" slot="tip">jpg/png files with a size less than 500kb</div> -->
                                        </el-upload>
                                    </div>
                                </div>
                            </el-form-item>
                        </div>
                        <div class="buzz-modal-content">
                            <el-form-item prop="name">
                                <el-input type="text" v-model="form.name" placeholder="Untitled Project"></el-input>
                            </el-form-item>
                            <el-form-item>
                                <el-select v-model="form.client" clearable placeholder="Select Client">
                                    <el-option
                                    v-for="item in clients"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value">
                                    </el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item>
                                <el-select v-model="form.service" clearable placeholder="Select Service">
                                    <el-option
                                    v-for="item in services"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value">
                                    </el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item label="Add Description">
                                <quill-editor v-model="form.description" ref="myQuillEditor">
                                </quill-editor>
                            </el-form-item>
                            <el-form-item label="Add Comment">
                                <quill-editor v-model="form.comment" ref="myQuillEditor">
                                </quill-editor>
                            </el-form-item>
                            <!-- <el-form-item>
                                <ckeditor 
                                    v-model="form.description" 
                                    :config="config"
                                    :row="4"
                                     placeholder="Description"
                                    @blur="onBlur($event)" 
                                    @focus="onFocus($event)">
                                </ckeditor>
                            </el-form-item>
                            <el-form-item>
                                <h2> Add Comments </h2>
                                <ckeditor 
                                    v-model="form.comment" 
                                    :config="config"
                                    :row="4"
                                     placeholder=" Comments"
                                    @blur="onBlur($event)" 
                                    @focus="onFocus($event)">
                                </ckeditor>
                            </el-form-item> -->
                            <el-form-item  class="form-buttons">
                                <el-button @click="submit"> {{ action }}</el-button>
                                <el-button @click="$modal.hide('add-project')">Cancel</el-button>
                            </el-form-item>
                        </div>
                    </el-form>
                </div>
            </section>
        </modal>
    </li>
</template>

<script>

var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();

    export default {
    	data: function () {
        	return {    
                title: 'Add New Project',
                action: 'Save',
                id: 0,
                oldName: '',
                isProcessing: false,
                form: {
                    title: '',
                    description: '',
                    comment: '',
                    end_at: '',
                    start_at: yyyy + '-' + mm + '-' + dd,
                    content: '',
                    client_id: 1,
                    service_id: 1,
                },
                clients: [],
                services: [],
        		error: {
        			title: [],
                    description: [],
                    comment: [],
                    end_at: [],
                    start_at: [],
                    content: [],
                    client_id: [],
                    service_id: [],
        		},
        	}
        },

        methods: {
            beforeOpen (event) {
                if(typeof event.params != 'undefined' && event.params.action == 'update') {
                    this.action = 'Update';
                    this.header = 'Edit Project';
                    this.id = event.params.data.id;
                    var vm = this;
                    axios.get('api/projects/'+this.id)
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
                axios.post('/api/projects/new',this.form)
                .then( response => {
                    this.isProcessing = false;
                    swal('Success!', 'Project is saved!', 'success');
                })
                .catch ( error => {
                    this.isProcessing = false;
                    if(error.response.status == 422){
                    this.errors = error.response.data.errors;
                    }
                })
            },
            update: function () {
                axios.put('/api/projects/'+this.id+'/edit', this.form)
                .then( response => {
                    this.isProcessing = false;
                    swal('Success!', 'Project is updated!', 'success');
                })
                .catch ( error => {
                    if(error.response.status == 422){
                    this.errors = error.response.data.errors;
                    }
                    this.isProcessing = false;        
                })
            },
            getClients(){
                axios.get('api/clients')
                .then( response => {
                    console.info(response.data);
                })
            },
            getServices(){
                axios.get('api/services')
                .then( response => {
                    console.info(response.data);
                })
            }
        },
        mounted() {
            this.getClients();
            this.getServices();
        }
    }
</script>