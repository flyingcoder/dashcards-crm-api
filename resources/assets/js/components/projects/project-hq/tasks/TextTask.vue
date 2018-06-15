<template>
    <div>
        <div class="modal-options">
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
                <!-- <div class="option-item">
                    <div class="date-option">
                        <img src="/img/icons/modal/date.svg" alt="" class="button-icon">                                    
                        <el-date-picker
                            :clearable="false"
                            v-model="form.due_date"
                            type="date"
                            placeholder="Due Date">
                        </el-date-picker>
                    </div>
                </div> -->
                <div class="option-item attachment pull-right">
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
            <el-form-item class="input-full" label="" prop="name" :error="formError.name">
                <el-input v-model="form.name" placeholder="Untitled Task"></el-input>
            </el-form-item>
            <el-form-item class="input-half">
                <el-select v-model="form.milestone" filterable @change="milestoneChange" placeholder="Select Milestone" style="width: 100%">
                    <el-option
                        v-for="milestone in milestones"
                        :key="milestone.id"
                        :label="milestone.title"
                        :value="milestone.id">
                    </el-option>
                </el-select>
            </el-form-item>
            <el-form-item class="input-half" prop="days" :error="formError.days">
                <el-input v-model="form.days" placeholder="Days"></el-input>
            </el-form-item>
            <el-form-item class="input-half" :error="formError.started_at">
                <el-date-picker @focus="hideMembers" 
                    style="width: 100%"
                    v-model="form.started_at"
                    type="date"
                    value-format="yyyy-MM-dd"                
                    placeholder="Select Start Date"
                    :picker-options="dateOptions">
                </el-date-picker>
            </el-form-item>
            <el-form-item class="input-half" :error="formError.end_at">
                <el-date-picker @focus="hideMembers"
                    style="width: 100%"
                    v-model="form.end_at"
                    type="date"
                    value-format="yyyy-MM-dd"
                    placeholder="Select End Date"
                    :picker-options="dateOptions">
                </el-date-picker>
            </el-form-item>
            <el-form-item>
                <span> Note! If Start and End dates are provided, Milestone duration (days) will be ignored </span>
            </el-form-item>
            <el-form-item class="modal-editor" label="Add Description" v-if="!isProcessing" :error="formError.description">
                <ckeditor id="description" v-model="form.description"></ckeditor>
            </el-form-item>
            <el-form-item label="Add Comment" :error="formError.comment" v-if="action != 'Update'">
                <ckeditor id="comment" v-model="form.comment" :value="form.comment"></ckeditor>
            </el-form-item>
            <el-form-item  class="form-buttons">
                <el-button @click="submitForm('form')">Save</el-button>
                <el-button @click="$modal.hide('add-task')">Cancel</el-button>
            </el-form-item>
        </div>
    </div>
</template>

<script>
  export default {
	    props: ['projectId','parameter'],
    	data: function () {
          var self = this;
        	return { 
            selectMembers: false,
            milestones: '',            
            name: '',
            title: 'Create Task',
            action: 'Save',
            oldName: '',
            form: this.initFormData(),
            formError: '',
            id: 0,
            isProcessing: false,
            attachmentList: false,
            attachmentsLength: 0,
            members: [],
            dateOptions: {
              
            }
        	}
        },
        mounted () {
            this.form = this.initFormData();
            this.action = 'Save'
            this.getMembers();
            this.getMilestones();
            if(typeof parameter != 'undefined' && parameter == 'Update') {
                this.isProcessing = true;
                this.action = 'Update';
                this.header = 'Edit Task';
                axios.get('/api/tasks/' + event.params.data.id)
                .then(response => {
                this.isProcessing = false;     
                    this.id = response.data.id;                 
                    this.form.title = response.data.title;
                    this.form.description = response.data.description
                    this.form.started_at = response.data.started_at
                    this.form.end_at = response.data.end_at
                    this.form.days = response.data.days
                    this.form.members = response.data.assigned.map(function(a){
                    return a.id;
                    })
                })    
            }
        },
        methods: {

            initFormData(){
                 return {
                  title: '',
                  description: '',
                  milestone_id: '',
                  started_at: '',
                  end_at: '',
                  members: [],
                  days: '',
                  comment: '',
                }
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
        	submit(){
                if(this.action == 'Save'){
                    this.save();
                }
                else{
                    this.update();
                }
            },
            save(){
                this.isProcessing = true;
                var vm = this;
                axios.post('/api/tasks', this.form)
                .then( response => {
                  this.isProcessing = false;
                  swal({
                    title: 'Success!',
                    text: 'Task is saved!',
                    type: 'success'
                  }).then( function() {
                    vm.$emit('updated', response.data.task)
                    vm.$modal.hide('new-task-modal-'  + vm.milestone.id);
                  });					
                })
                .catch ( error => {
                    this.isProcessing = false;
                  this.formError = '';
                  if(error.response.status == 422){ 
                    if(error.response.data.message == undefined){
                      
                      this.formError = error.response.data; 
                    }
                    else{
                      this.formError = { end_at: 'The end at must be a date after started at.'};
                    }
                    swal('Saving Failed!','Form validation failed! ', 'error');
                  }
                  else {
                    swal('Saving Failed!','Server Error! ', 'error');  
                  }    
                    })
            },
            update () {
                this.isProcessing = true;
                var vm = this;              
                axios.put('/api/tasks/' + this.id, this.form)
                .then( response => {
                    this.isProcessing = false;
                    swal({
                    title: 'Success!',
                    text: 'Task is updated!',
                    type: 'success'
                  }).then( function() {
                    vm.$emit('updated', response.data.task)
								    vm.$modal.hide('new-task-modal-'  + vm.milestone.id);
                  });
                })
                .catch ( error => {
                    this.isProcessing = false;
                  this.formError = '';
                  if(error.response.status == 422){ 
                    if(error.response.data.message == undefined){
                      
                      this.formError = error.response.data; 
                    }
                    else{
                      this.formError = { end_at: 'The end at must be a date after started at.'};
                    }
                    swal('Saving Failed!','Form validation failed! ', 'error');
                  }
                  else {
                    swal('Saving Failed!','Server Error! ', 'error');  
                  }      
								})
            },
            getMembers(){
                axios.get('/api/projects/' + this.$parent.$parent.$parent.projectId + '/members-all')
                .then( response => {
                    this.members = response.data
                })
            },
            hideMembers(){
                this.selectMembers = false;
                this.$refs.memberSelect.blur()
            },
            getMilestones(){
                axios.get('/api/projects/' + this.$parent.projectId + '/milestones')
                .then( response => {
                    this.milestones = response.data;
                })
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
            beforeImport(file) {
                console.log(file)
                this.files.append('file', file);
                return true;
            },
            milestoneChange(val){
                console.log(val)
            }
		  }
		}
</script>