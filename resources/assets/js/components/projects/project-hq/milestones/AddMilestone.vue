<template>
    <div>
        <div class="multiple-add" v-on:click="multipleBtnVisible = !multipleBtnVisible" v-bind:class="{ rotateIcon: multipleBtnVisible }">
            <i class="el-icon-arrow-down el-icon--right"></i>
            <div class="multiple-btn"  v-bind:class="{ multipleBtnVisible: multipleBtnVisible }">
                <div class="add-button one"  @click="$modal.show('add-milestone')">
                    <span> ADD NEW </span>
                    <button>
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            width="22px" height="21px">
                            <path fill-rule="evenodd"  fill="rgb(255, 255, 255)"
                            d="M20.486,10.861 L12.470,10.861 L12.470,19.861 C12.470,20.302 12.031,20.661 11.490,20.661 C10.949,20.661 10.510,20.302 10.510,19.861 L10.510,10.861 L1.511,10.861 C1.069,10.861 0.710,10.423 0.710,9.882 C0.710,9.340 1.069,8.902 1.511,8.902 L10.510,8.902 L10.510,0.885 C10.510,0.443 10.949,0.085 11.490,0.085 C12.031,0.085 12.470,0.443 12.470,0.885 L12.470,8.902 L20.486,8.902 C20.928,8.902 21.286,9.340 21.286,9.882 C21.286,10.423 20.928,10.861 20.486,10.861 Z"/>
                        </svg>
                    </button>
                </div>
                <div class="add-button two"  @click="$modal.show('add-template')">
                    <span> Import Template </span>
                    <button>
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            width="22px" height="21px">
                            <path fill-rule="evenodd"  fill="rgb(255, 255, 255)"
                            d="M20.486,10.861 L12.470,10.861 L12.470,19.861 C12.470,20.302 12.031,20.661 11.490,20.661 C10.949,20.661 10.510,20.302 10.510,19.861 L10.510,10.861 L1.511,10.861 C1.069,10.861 0.710,10.423 0.710,9.882 C0.710,9.340 1.069,8.902 1.511,8.902 L10.510,8.902 L10.510,0.885 C10.510,0.443 10.949,0.085 11.490,0.085 C12.031,0.085 12.470,0.443 12.470,0.885 L12.470,8.902 L20.486,8.902 C20.928,8.902 21.286,9.340 21.286,9.882 C21.286,10.423 20.928,10.861 20.486,10.861 Z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <modal name="add-milestone" class="add-hq-milestone" transition="nice-modal-fade" @before-open="beforeOpen">
            <section class="content">
                <v-layout row wrap>
                    <div class="buzz-modal-header"> {{ title }} </div>
                    <div class="buzz-scrollbar milestone-form" id="buzz-scroll">
                        <el-form :model="form" ref="form" v-loading="isProcessing">
                            <div class="buzz-modal-content">
                                <el-form-item prop="title" :error="formError.title">
                                    <el-input v-model="form.title" placeholder="Title"></el-input>
                                </el-form-item>
                                <el-form-item prop="started_at" :error="formError.started_at">
                                <el-date-picker
                                        style="width: 100%"
                                        v-model="form.started_at"
                                        type="date"
                                        value-format="yyyy-MM-dd"
                                        placeholder="Select Start Date">
                                    </el-date-picker>
                                </el-form-item>
                                <el-form-item prop="end_at" :error="formError.end_at">
                                    <el-date-picker
                                        style="width: 100%"
                                        v-model="form.end_at"
                                        type="date"
                                        value-format="yyyy-MM-dd"
                                        placeholder="Select End Date">
                                    </el-date-picker>
                                </el-form-item>
                                <el-form-item prop="days" :error="formError.days">
                                    <el-input v-model="form.days" placeholder="Days"></el-input>
                                </el-form-item>
                                <el-form-item  class="milestone-note">
                                    <span> Note! If Start and End dates are provided, Milestone duration (days) will be ignored </span>
                                </el-form-item>
                                <el-form-item class="form-buttons">
                                    <el-button type="primary" @click="submit('form')">Save</el-button>
                                    <el-button @click="$modal.hide('add-milestone')">Cancel</el-button>
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
        props: ['projectId'],
    	data: function () {
        	return {    
                multipleBtnVisible: false,
                title: 'Add New Milestone',
                action: 'Save',
                id: 0,
                isProcessing: false,
                form: this.initFormData(),
                formError: '',
        	}
        },

        methods: {
            beforeOpen (event) {
                this.files = new FormData();     
                this.form = this.initFormData();
                this.title = 'Add New Milestone';
                this.action = 'Save';
                this.formError = '';                
                if(typeof event.params != 'undefined' && event.params.action == 'Update') {   
                    this.isProcessing = true;
                    this.action = 'Update';
                    this.title = 'Edit Milestone';
                    this.data = event.params.data;
                    var vm = this;
                    axios.get('api/milestone/'+this.data.id)
                    .then( response => {
                        this.isProcessing = false;
                        vm.id = response.data.id;
                        vm.form = this.initFormData();
                        vm.form.title = response.data.title;
                        vm.form.started_at = response.data.started_at;
                        vm.form.end_at = response.data.end_at;
                        vm.form.days = response.data.days;         
                        
                    });
                }
            },
            initFormData(){
                 return {
                    title: '',
                    started_at: '',
                    end_at: '',
                    days: ''
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
            save: function () {
                this.isProcessing = true;
                var vm = this;
                axios.post('/api/project-milestones/'+this.projectId,this.form)
                .then( response => {
                    this.id = response.data.id;
                    this.isProcessing = false;        
                    this.formError = '';                                                
                    swal({
                        title: 'Success!',
                        text: 'Milestone is saved!',
                        type: 'success'
                    }).then( function() {
                        vm.$modal.hide('add-milestone');
                        vm.$emit('updated',response.data);
                    });
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
                var vm = this;
                
                axios.put('/api/project-milestones/'+this.id, this.form)
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
                        swal('Saving Failed!','Form validation failed! ', 'error');
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