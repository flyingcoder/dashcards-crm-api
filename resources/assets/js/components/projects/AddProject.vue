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
                                            <el-option  class="member-items" v-for="m in members" :key="m.id" 
                                            :value="m.id" :label="m.name">
                                                <span class="user-image"> <img :src="m.image_url"/> </span>
                                                <div class="user-name"> {{ m.name }} </div>
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
                                            :on-change="handleAdd"
                                            :on-remove="handleRemove"
                                            :before-upload="beforeImport"
                                            :http-request='submitFiles'                           
                                            :auto-upload="false">
                                            <el-button slot="trigger">
                                                Attachment 
                                            </el-button>
                                        </el-upload>
                                        <div v-on:click="attachmentList = !attachmentList"> 
                                            <el-badge :value="attachmentsLength" :max="99" class="file-badge"></el-badge>
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
                            <el-form-item class="modal-editor" v-if="!isProcessing" label="Add Description" :error="formError.description">
                                <ckeditor id="description" v-model="form.description"></ckeditor>
                            </el-form-item>
                            <el-form-item label="Add Comment" :error="formError.comment" v-if="action != 'Update'">
                                <ckeditor id="comment" v-model="form.comment" :value="form.comment"></ckeditor>
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
                members: [],
                clients: [],
                services: [],
                attachmentsLength: 0,
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
                this.files = new FormData();     
                this.form = this.initFormData();
                this.title = 'Add New Project';
                this.action = 'Save';
                this.formError = '';                
                if(typeof event.params != 'undefined' && event.params.action == 'Update') {   
                    this.isProcessing = true;
                    this.action = 'Update';
                    this.title = 'Edit Project';
                    this.data = event.params.data;
                    var vm = this;
                    axios.get('api/projects/'+this.data.id)
                    .then( response => {
                        this.isProcessing = false;
                        vm.id = response.data.id;
                        vm.form = this.initFormData();
                        vm.form.title = response.data.title;
                        vm.form.description = response.data.description;
                        //if(response.data.comment){
                        //    this.form.comment = response.data.comment[0].body
                        //}
                        //this.form.members = response.data.members.map(function(e){
                        //    return e.id;
                        //})
                        vm.form.end_at = response.data.end_at;
                        vm.form.start_at = response.data.started_at;
                        vm.form.client_id = response.data.client[0].id;
                        vm.form.service_id = response.data.service.id;
                        
                        
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
                this.hideMembers();
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
            handleAdd(file, fileList) {
                this.attachmentsLength = fileList.length;
            },
            handleRemove(file, fileList) {
                
               this.attachmentsLength -= fileList.length;
            },
            save: function () {
                this.isProcessing = true;
                var vm = this;
                axios.post('/api/projects/',this.form)
                .then( response => {
                    this.id = response.data.id;
                    this.$refs.attachments.submit();
                    this.isProcessing = false;        
                    this.formError = '';                                                
                    swal({
                        title: 'Success!',
                        text: 'Project is saved!',
                        type: 'success'
                    }).then( function() {
                        vm.$modal.hide('add-project');
                        vm.$emit('updated',response.data);
                    });
                })
                .catch ( error => {
                    this.isProcessing = false;
                    this.formError = '';
                    if(error.response.status == 422){
                        this.formError = error.response.data;
                        if (typeof this.formError === 'object'){
                            swal('Saving Failed!','Form validation failed! ', 'error');
                        }
                        else {
                            swal('Form validation failed!',this.formError, 'error');
                        }
                        
                    }
                    else {
                        swal('Saving Failed!','Server Error! ', 'error');  
                    }
                });
                
            },
            update: function () {
                this.isProcessing = true;
                var vm = this;
                
                axios.put('/api/projects/'+this.id, this.form)
                .then( response => {
                    this.isProcessing = false;
                     swal({
                        title: 'Success!',
                        text: 'Project is updated!',
                        type: 'success'
                    }).then( function() {
                        vm.$modal.hide('add-project');
                        vm.$emit('updated',response.data);
                    });
                })
                .catch ( error => {
                    this.isProcessing = false;
                    this.formError = '';
                    if(error.response.status == 422){
                        this.formError = error.response.data;
                        if (typeof this.formError === 'object'){
                            swal('Saving Failed!','Form validation failed! ', 'error');
                        }
                        else {
                            swal('Form validation failed!',this.formError, 'error');
                        }
                    }
                    else {
                        swal('Saving Failed!','Server Error! ', 'error');  
                    }
                });
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
                console.log(file)
                this.files.append('file', file);
                return true;
            },
            getMembers(){
                axios.get('api/company/members')
                .then( response => {
                    this.members = response.data
                })
            },
        },
        mounted() {
            this.getMembers();
            this.getClients();
            this.getServices();       
        }
    }
</script>