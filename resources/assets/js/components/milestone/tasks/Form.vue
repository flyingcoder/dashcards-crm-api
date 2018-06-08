<template>
  <modal name="add-template" class="add-milestone-template" transition="nice-modal-fade" @before-open="beforeOpen">
            <section class="content add-milestone">
            <v-layout row wrap>
              <div class="buzz-modal-header"> {{ title }} </div>
              <div class="buzz-scrollbar" id="buzz-scroll">
            <el-form label-position="top" v-loading="isProcessing" :element-loading-text="loadingText">
              <div class="buzz-modal-content">
                  <el-form-item label="Title" :error="formError.name">
                    <el-input type="text" v-model="form.name" placeholder="Template Title"></el-input>
                  </el-form-item>
                <el-form-item label="Status">
                  <el-select v-model="form.status" placeholder="Select">
                    <el-option
                      v-for="item in options"
                      :key="item.value"
                      :label="item.label"
                      :value="item.value">
                    </el-option>
                  </el-select>
                </el-form-item>
                
                <el-form-item  class="form-buttons">
                  <el-button @click="submit"> {{ action }}</el-button>
                  <el-button @click="$modal.hide('add-template')">Cancel</el-button>
                </el-form-item>
              </div>
            </el-form>
          </div>
        </v-layout>
    </section>
  </modal>
</template>

<script>
var URL = '/api/milestones/'
export default {
  data(){
    return {
      options: [
        { label: 'Active', value: 'active' },
        { label: 'Inactive', value: 'inactive' }
      ],
      form: this.initForm(),
      action: 'Save',
      title: 'Add New Milestone Template',
      isProcessing: false,
      formError: '',
      loadingText: 'Saving ...'
    }
  },
  methods:{
    beforeOpen (event) {
      this.form = this.initForm();
      this.formError = '';
      if(typeof event.params != 'undefined' && event.params.action == 'Update') {
        this.action = 'Update';
        this.title = 'Edit Milestone Template';
        this.form = event.params.data;
      }
    },
    initForm(){
      return {
        name: '',
        status: 'active',
        type: 'App\\Milestone',
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
      axios.post('/api/template',this.form)
      .then( response => {
        this.isProcessing = false;                                    
        swal({
          title: 'Success!',
          text: 'Template is saved!',
          type: 'success'
        }).then( function() {
           vm.$modal.hide('add-template');
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
          text: 'Template is updated!',
          type: 'success'
        }).then( function() {
           vm.$modal.hide('add-template');
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
