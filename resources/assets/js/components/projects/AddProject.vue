<template>
    <modal name="add-project" transition="nice-modal-fade" @before-open="beforeOpen">
        <section class="content add-project">
            <v-layout row wrap>
                <div class="buzz-modal-header"> {{ title }} </div>
                <div class="buzz-scrollbar" id="buzz-scroll">
                    <el-form :model="form" ref="projectForm" label-position="top" v-loading="isProcessing" style="width: 100%">
                        <div class="modal-options">
                            <el-form-item  class="option">
                                <div class="option-item">
                                    <div class="member-option" v-on:click="membersClick">
                                        <el-button size="small" class="el-dropdown-link" id="member-option"> 
                                            <img src="/img/icons/modal/members.png" alt="" class="button-icon">   
                                            Members 
                                        </el-button>
                                        <el-badge :value="form.members.length" :max="99" class="member-badge"></el-badge>
                                    </div>
                                    <div v-show="selectMembers" class="selectMembers">
                                        <el-select class="selectMembers__content" 
                                            v-model="form.members" 
                                            multiple
                                            filterable
                                            default-first-option
                                            ref="memberSelect"
                                            placeholder="Choose a Member"
                                        >
                                        <div class="selectMembers__dropdown">
                                            <el-option  class="member-items" v-for="m in clients" :key="m.id" 
                                            :value="m.id" :label="m.first_name + ' ' + m.last_name">
                                                <span class="user-image"> <img :src="m.image_url"/> </span>
                                                <div class="user-name"> {{ m.first_name + ' ' + m.last_name }} </div>
                                            </el-option>
                                        </div>
                                    </el-select>
                                </div>
                            </div>
                                <div class="option-item">
                                    <div class="date-option">
                                        <img src="/img/icons/modal/date.svg" alt="" class="button-icon">
                                        <el-form-item :error="formError.end_at">
                                            <el-date-picker @focus="hideMembers"
                                            :clearable="false"
                                            v-model="form.end_at"
                                            type="date"
                                            placeholder="Due Date"
                                            value-format="yyyy-MM-dd"
                                            >
                                         </el-date-picker>
                                        </el-form-item>                                    
                                        
                                    </div>
                                </div>
                                <div class="option-item">
                                    <div class="file-upload" v-bind:class="{ attachmentList: attachmentList }">
                                        <img src="/img/icons/modal/attachment.svg" alt="" class="button-icon"> 
                                        <el-upload @focus="hideMembers"
                                            multiple
                                            class=""
                                            ref="attachments"
                                            action=""
                                            :before-upload="beforeImport"
                                            :http-request='submitFiles'                           
                                            :auto-upload="false">
                                            <el-button slot="trigger">
                                                Attachment 
                                            </el-button>
                                        </el-upload>
                                        <div v-on:click="attachmentList = !attachmentList"> 
                                            <el-badge :value="10" :max="99" class="file-badge"></el-badge>
                                        </div>
                                    </div>
                                </div>
                            </el-form-item>
                        </div>
                        <div class="buzz-modal-content">
                            <el-form-item :error="formError.title">
                                <el-input type="text" @focus="hideMembers" v-model="form.title" placeholder="Untitled Project"></el-input>
                            </el-form-item>
                            <el-form-item :error="formError.client_id">
                                <el-select v-model="form.client_id" clearable placeholder="Select Client" @focus="hideMembers">
                                    <el-option 
                                    v-for="c in clients"
                                    :key="c.id"
                                    :label="c.company_name"
                                    :value="c.id">
                                    </el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item :error="formError.service_id">
                                <el-select v-model="form.service_id" clearable placeholder="Select Service" @focus="hideMembers">
                                    <el-option
                                    v-for="s in services"
                                    :key="s.id"
                                    :label="s.name"
                                    :value="s.id">
                                    </el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item class="modal-editor" label="Add Description" :error="formError.description">
                                <ckeditor id="description" v-model="form.description"></ckeditor>
                            </el-form-item>
                            <el-form-item label="Add Comment" :error="formError.comment">
                                <ckeditor id="comment" v-model="form.comment"></ckeditor>
                            </el-form-item>
                            <el-form-item  class="form-buttons" >
                                <el-button @click="submit"> {{ action }}</el-button>
                                <el-button @click="$modal.hide('add-project')">Cancel</el-button>
                            </el-form-item>
                        </div>
                    </el-form>
                </div>
            </v-layout>
        </section>
    </modal>
</template>

<script>

var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();

    export default {
    	data: function () {
        	return {    
                selectMembers: false,
                attachmentList: false,
                descriptionEditor: false,
                commentEditor: false,
                title: 'Add New Project',
                action: 'Save',
                id: 0,
                oldName: '',
                isProcessing: false,
                files: [],
                form: this.initFormData(),
                clients: [],
                services: [],
                formError: '',
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
                editorOption: {
                // some quill options
                }
        	}
        },

        methods: {
            beforeOpen (event) {
                this.form = this.initFormData();
                if(typeof event.params != 'undefined' && event.params.action == 'Update') {
                    this.action = 'Update';
                    this.title = 'Edit Project';
                    this.data = event.params.data;
                    var vm = this;
                    axios.get('api/projects/'+this.data.id)
                    .then( response => {
                        this.id = response.data.id;
                        this.form = this.initFormData();
                        this.form.title = response.data.title;
                        this.form.description = response.data.description;
                        if(response.data.comment){
                            this.form.comment = response.data.comment[0].body
                        }
                        this.form.members = response.data.members.map(function(e){
                            return e.id;
                        })
                        this.form.end_at = response.data.end_at;
                        this.form.start_at = response.data.started_at;
                        this.form.client_id = response.data.client[0].id;
                        this.form.service_id = response.data.service.id;
                    });
                }
            },
            initFormData(){
                 return {
                    title: '',
                    description: '',
                    comment: '',
                    members: [],
                    end_at: '',
                    start_at: yyyy + '-' + mm + '-' + dd,
                    client_id: '',
                    service_id: '',
                }
            },
            submit(){
                if(this.action == 'Save'){
                    this.save();
                }
                else {
                    this.update();
                }
            },
            submitFiles () {
                axios.post('/project-hq/' + this.id + '/files', this.files)
                .then (response => {
                })
                .catch (error => {
                });
            },
            save: function () {
                this.isProcessing = true;
                axios.post('/api/projects/',this.form)
                .then( response => {
                    this.id = response.data.id;
                    this.$refs.attachments.submit();
                    this.isProcessing = false;                                    
                    swal('Success!', 'Project is saved!', 'success');
                    this.$modal.hide('add-project');

                })
                .catch ( error => {
                    this.isProcessing = false;
                    this.formError = '';
                    if(error.response.status == 422){
                        this.formError = error.response.data;
                        swal('Saving Failed!','Form validation failed! ', 'error');
                    }
                    else {
                        swal('Saving Failed!','Server Error! ', 'error');  
                    }
                });
                
            },
            update: function () {
                this.isProcessing = true;
                axios.put('/api/projects/'+this.id+'/edit', this.form)
                .then( response => {
                    this.isProcessing = false;
                    swal('Success!', 'Project is updated!', 'success');
                })
                .catch ( error => {
                    this.isProcessing = false;
                    if(error.response.status == 422){
                        this.errors = error.response.data.errors;
                    }
                    else {
                        swal('Saving Failed!', error.response.data, 'error');
                    }      
                })
            },
            getClients(){
                axios.get('api/clients?all=true')
                .then( response => {
                    this.clients = response.data
                })
            },
            getServices(){
                axios.get('api/services?all=true')
                .then( response => {
                    this.services = response.data
                })
            },
            membersClick(){
                this.selectMembers = !this.selectMembers;
                if(this.selectMembers){
                    this.$refs.memberSelect.toggleMenu();
                }
                else {
                     this.$refs.memberSelect.blur()
                }
                
            },
            hideMembers(){
                this.selectMembers = false;
                this.$refs.memberSelect.blur()
            },
            memberBlur(bool){
                 if(!bool){
                     this.selectMembers = bool;
                 }
            },
            beforeImport(file) {
                this.files.append('file', file);
                return true;
            },
        },
        mounted() {
            this.getClients();
            this.getServices();
            this.files = new FormData();            
        }
    }
</script>