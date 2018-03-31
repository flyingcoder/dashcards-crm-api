<template>
    <div class="empty-table">
        <img class="empty-icon" src="img/icons/empty/projects.svg">
        <div class="empty-button">
            <button class="add"  @click="$modal.show('add-project_page')">
                Add New Project
            </button>
        </div>

        <modal name="add-project" transition="nice-modal-fade" @before-open="beforeOpen">
            <section class="content">
                <div class="buzz-modal-header"> {{ title }} </div>
                <div class="buzz-scrollbar" id="buzz-scroll">
                    <el-form :model="projectForm" :rules="rules" ref="projectForm" label-position="top" v-loading="isProcessing" style="width: 100%">
                        <div class="buzz-modal-option">
                            <el-form-item  class="option">
                                <el-button class="option-item"> <img src="img/icons/modal/members.png" alt="">  Members </el-button>
                                <el-button class="option-item">
                                    <div class="date-project">
                                         <img src="img/icons/modal/date.svg" alt="" class="button-icon">                                    
                                        <el-date-picker
                                            :clearable="false"
                                            v-model="projectForm.due_date"
                                            type="date"
                                            placeholder="Due Date">
                                        </el-date-picker>
                                    </div>
                                </el-button>
                                 <el-button class="option-item"> <img src="img/icons/modal/attachment.svg" alt=""> Attachment </el-button>
                            </el-form-item>
                        </div>
                        <div class="buzz-modal-content">
                            <el-form-item prop="name">
                                <el-input type="text" v-model="projectForm.name" placeholder="Untitled Project"></el-input>
                            </el-form-item>
                            <el-form-item>
                                <el-select v-model="projectForm.client" clearable placeholder="Select Client">
                                    <el-option
                                    v-for="item in client_options"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value">
                                    </el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item>
                                <el-select v-model="projectForm.service" clearable placeholder="Select Service">
                                    <el-option
                                    v-for="item in service_options"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value">
                                    </el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item label="Add Description">
                                <quill-editor v-model="projectForm.description" ref="myQuillEditor">
                                </quill-editor>
                            </el-form-item>
                            <el-form-item label="Add Comment">
                                <quill-editor v-model="projectForm.comment" ref="myQuillEditor">
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
                                <el-button @click="submitForm('projectForm')">Save</el-button>
                                <el-button @click="$modal.hide('add-project')">Cancel</el-button>
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
                title: 'Add New Project',
                action: 'Save',
                id: 0,
                oldName: '',
                isProcessing: false,
                projectForm: {
                    name: '',
                    description: '',
                    comment: '',
                    due_date: '',
                    content: '',
                    client: '',
                    service: '',
                },
                rules: {
                    name: [
                        { required: true, message: 'Please input Project Name', trigger: 'change' },
                    ],
                },
                client_options: [{
                    value: 'Option1',
                    label: 'Option1'
                    }, {
                    value: 'Option2',
                    label: 'Option2'
                    }, {
                    value: 'Option3',
                    label: 'Option3'
                    }, {
                    value: 'Option4',
                    label: 'Option4'
                    }, {
                    value: 'Option5',
                    label: 'Option5'
                }],
                service_options: [{
                    value: 'Option1',
                    label: 'Option1'
                    }, {
                    value: 'Option2',
                    label: 'Option2'
                    }, {
                    value: 'Option3',
                    label: 'Option3'
                    }, {
                    value: 'Option4',
                    label: 'Option4'
                    }, {
                    value: 'Option5',
                    label: 'Option5'
                }],
        		error: {
        			name: [],
                    description: [],
                    due_date: [],
                    content: [],
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
                    this.header = 'Edit Project';
                    this.id = event.params.data.id;
                    var vm = this;
                    axios.get('api/projects/'+this.id)
                        .then( response => {
                            this.projectForm = response.data;
                        });
                }
            },
            submit(){
                this.$refs[projectForm].validate((valid) => {
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
                axios.post('/api/projects/new',this.projectForm)
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
                axios.put('/api/projects/'+this.id+'/edit', this.projectForm)
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
            }
        },
        mounted() {
            
        }
    }
</script>