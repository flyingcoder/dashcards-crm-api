<template>
  <modal name="add-tasks" transition="nice-modal-fade" @before-open="beforeOpen">
    <section class="content add-milestone">
      <v-layout row wrap>
        <div class="buzz-modal-header">{{title}}</div>
        <div class="buzz-scrollbar" id="buzz-scroll">
      <el-form label-position="top" v-loading="isProcessing" :element-loading-text="loadingText">
        <div class="buzz-modal-content">
        
            <el-form-item label="Title">
              <el-input type="text" v-model="formTask.title" placeholder="Task Title"></el-input>
            </el-form-item>
            <el-form-item label="Days">
              <el-input-number type="text" v-model="formTask.days"></el-input-number>
            </el-form-item>
            <el-form-item class="modal-editor" label="Description">
                <ckeditor id="description" ref="ckeditor" v-model="formTask.description"></ckeditor>
            </el-form-item>
            <el-button type="text" @click="dialogVisible = false">Cancel</el-button>
            <el-button type="text" @click="submit">Submit</el-button>
        </div>
      </el-form>
        </div>
      </v-layout>
    </section>
  </modal>
</template>

<script>
export default {
  data(){
    return {
      options: [
        { label: 'Active', value: 'active' },
        { label: 'Inactive', value: 'inactive' }
      ],
      milestoneID: 0,
      formTask: this.initForm(),
      action: 'Save',
      title: 'Add New Tasks',
      isProcessing: false,
      formError: '',
      loadingText: 'Saving ...'
    }
  },
  methods:{
    beforeOpen (event) {
      this.formTask = this.initForm();
      this.formError = '';
      this.milestoneID = event.params.id;
      if(typeof event.params != 'undefined' && event.params.action == 'Update') {
        this.action = 'Update';
        this.title = 'Edit Task';
        this.formTask = event.params.data;
      }
    },
    initForm(){
      return {
        title: '',
        description: '',
        days: 1
      }
    },
    submit(){
      if(this.action == 'Save'){
          this.addTask();
      } else {
          this.update();
      }
    },
    addTask(){
      this.isProcessing = true;
      var vm = this;
      axios.post('/api/milestone/'+this.milestoneID+'/tasks', this.formTask)
      .then( response => {
        this.isProcessing = false;                                    
        swal({
          title: 'Success!',
          text: 'Template is saved!',
          type: 'success'
        }).then( function() {
           vm.$modal.hide('add-tasks');
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
    update(){
      this.isProcessing = true;
      var vm = this;
      axios.put('/api/tasks/'+this.formTask.id , this.formTask)
      .then( response => {
        this.isProcessing = false;                                    
        swal({
          title: 'Success!',
          text: 'Template is updated!',
          type: 'success'
        }).then( function() {
           vm.$modal.hide('add-tasks');
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
    }
  }
}
</script>

<style>

</style>
