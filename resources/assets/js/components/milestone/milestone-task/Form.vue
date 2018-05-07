<template>
  <modal name="add-mlt-milestone" transition="nice-modal-fade" @before-open="beforeOpen">
            <section class="content add-project">
            <v-layout row wrap>
              <div class="buzz-modal-header"> {{ title }} </div>
              <div class="buzz-scrollbar" id="buzz-scroll">
            <el-form label-position="top" v-loading="isProcessing" :element-loading-text="loadingText">
              <div class="buzz-modal-content">
                <el-form-item>
                  <el-form-item label="Title" :error="formError.title">
                    <el-input type="text" v-model="form.title" placeholder="Template Title"></el-input>
                  </el-form-item>
                </el-form-item>
                <el-form-item>
                  <el-form-item label="Days" :error="formError.title">
                    <el-input-number type="text" v-model="form.title" placeholder="Template Title"></el-input-number>
                  </el-form-item>
                </el-form-item>
                <el-table
                  :data="tasks"
                  style="width: 100%">
                  <el-table-column
                    prop="title"
                    label="Title"
                    >
                  </el-table-column>
                  <el-table-column
                    prop="description"
                    label="Description"
                  >
                  </el-table-column>
                  <el-table-column
                    prop="days"
                    label="Days">
                  </el-table-column>
                </el-table>
                <el-button type="text" @click="dialogVisible = true">Add Task</el-button>
                
                <el-form-item  class="form-buttons">
                  <el-button @click="submit"> {{ action }}</el-button>
                  <el-button @click="$modal.hide('add-mlt-milestone')">Cancel</el-button>
                </el-form-item>
              </div>
            </el-form>
            <el-dialog
              title="Tips"
              :visible.sync="dialogVisible"
              width="30%"
              :before-close="handleClose">
              <span>This is a message</span>
              <span slot="footer" class="dialog-footer">
                <el-button @click="dialogVisible = false">Cancel</el-button>
                <el-button type="primary" @click="dialogVisible = false">Confirm</el-button>
              </span>
            </el-dialog>
          </div>
        </v-layout>
    </section>
  </modal>
</template>

<script>
var URL = '/api/milestones/mlt-milestone/'
export default {
  data(){
    return {
      options: [
        { label: 'Active', value: 1 },
        { label: 'Inactive', value: 0 }
      ],
      form: this.initForm(),
      action: 'Save',
      title: 'Add New Milestone',
      isProcessing: false,
      formError: '',
      loadingText: 'Saving ...',
      dialogVisible: false,
      tasks: [],
    }
  },
  methods:{
    beforeOpen (event) {
      this.form = this.initForm();
      if(typeof event.params != 'undefined' && event.params.action == 'Update') {
        this.action = 'Update';
        this.title = 'Edit Milestone';
        this.form = event.params.data;
      }
    },
    initForm(){
      return {
        title: '',
        is_active: 1,
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
    save(){
      this.isProcessing = true;
      var vm = this;
      axios.post(URL,this.form)
      .then( response => {
        this.isProcessing = false;                                    
        swal({
          title: 'Success!',
          text: 'Project is saved!',
          type: 'success'
        }).then( function() {
           vm.$modal.hide('add-mlt-milestone');
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
      axios.put(URL + this.form.id,this.form)
      .then( response => {
        this.isProcessing = false;                                    
        swal({
          title: 'Success!',
          text: 'Project is updated!',
          type: 'success'
        }).then( function() {
           vm.$modal.hide('add-mlt-milestone');
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
