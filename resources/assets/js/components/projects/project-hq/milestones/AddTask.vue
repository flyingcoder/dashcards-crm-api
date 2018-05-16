<template>
        <modal :name="'new-task-modal-' + milestone.id"
               transition="nice-modal-fade"
               @before-open="beforeOpen"
        >
            <section class="content">
              <v-layout row wrap>
                <div class="buzz-modal-header"> {{ title }}  </div>
                <div class="buzz-scrollbar" id="buzz-scroll">
                  <el-form ref="form" :model="form" label-position="top" v-loading="isProcessing">
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
                                        :value="m.id" :label="m.first_name + ' ' + m.last_name">
                                            <span class="user-image"> <img :src="'/' + m.image_url"/> </span>
                                            <div class="user-name"> {{ m.first_name + ' ' + m.last_name }} </div>
                                        </el-option>
                                    </div>
                                </el-select>
                            </div>
                        </div>
                        </el-form-item>
                        </div>
                        <div class="buzz-modal-content">
                          <el-form-item :error="formError.title">
                            <el-input v-model="form.title" @focus="hideMembers"  placeholder="Untitled Task" ></el-input>
                          </el-form-item>

                          <el-form-item >
                            <el-input v-model="milestone.title"  v-if="action == 'Save'" disabled @focus="hideMembers"></el-input>
                          </el-form-item>

                          <el-form-item :error="formError.started_at">
                            <el-date-picker @focus="hideMembers" 
                              style="width: 100%"
                              v-model="form.started_at"
                              type="date"
                              value-format="yyyy-MM-dd"                
                              placeholder="Select Start Date"
                              :picker-options="dateOptions">
                            </el-date-picker>
                          </el-form-item>

                          <el-form-item :error="formError.end_at">
                            <el-date-picker @focus="hideMembers"
                              style="width: 100%"
                              v-model="form.end_at"
                              type="date"
                              value-format="yyyy-MM-dd"
                              placeholder="Select End Date"
                              :picker-options="dateOptions">
                            </el-date-picker>
                          </el-form-item>
                          <el-form-item prop="days" :error="formError.days">
                              <el-input v-model="form.days" placeholder="Days"></el-input>
                          </el-form-item>
                          <el-form-item>
                              <span> Note! If Start and End dates are provided, Milestone duration (days) will be ignored </span>
                          </el-form-item>

                          <el-form-item class="modal-editor" v-if="!isProcessing" label="Add Description" :error="formError.description">
                              <ckeditor id="description" v-model="form.description"></ckeditor>
                          </el-form-item>
                          <el-form-item label="Add Comment" v-if="action == 'Save'" :error="formError.comment">
                              <ckeditor id="comment" v-model="form.comment" :value="form.comment"></ckeditor>
                          </el-form-item>
                          <el-form-item  class="form-buttons" >
                            <el-button @click="submit"> {{ action }}</el-button>
                            <el-button @click="$modal.hide('new-task-modal-' + milestone.id)">Cancel</el-button>
                          </el-form-item>
                        </div>
                       

                      <!-- <el-button type="primary" @click="submit" class="pull-right">Create</el-button> -->
                  </el-form>
                </div>
              </v-layout>
            </section>
        </modal>
</template>

<script>

    export default {
			props: ['milestone','projectId'],
    	data: function () {
          var self = this;
        	return { 
            selectMembers: false,               
        		name: '',
						title: 'Create Task',
						action: 'Save',
						oldName: '',
        		form: this.initFormData(),
            formError: '',
            id: 0,
      			isProcessing: false,
            members: [],
            dateOptions: {
              disabledDate(time) {
                return moment(time).format('YYYY-MM-DD') < self.milestone.started_at || moment(time).format('YYYY-MM-DD') > self.milestone.end_at
              },
            }
        	}
        },
        methods: {
            beforeOpen (event) {
                this.form = this.initFormData();
                this.form.milestone_id = this.milestone.id;
					      this.title = 'Create Milestone';
                this.action = 'Save'
                this.getMembers();
                if(typeof event.params != 'undefined' && event.params.action == 'Update') {
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
            initFormData(){
                 return {
                  title: '',
                  description: '',
                  milestone_id: this.milestone.id,
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
		  }
		}
</script>