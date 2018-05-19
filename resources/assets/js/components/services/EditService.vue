<template>
  <modal name="edit-service" transition="nice-modal-fade" @before-open="beforeOpen">
            <section class="content add-milestone">
            <v-layout row wrap>
              <div class="buzz-modal-header"> {{ title }} </div>
              <div class="buzz-scrollbar" id="buzz-scroll">
            <el-form label-position="top" v-loading="isProcessing" :element-loading-text="loadingText">
              <div class="buzz-modal-content">
                  <el-form-item :error="formError.name[0]">
                    <el-input type="text" v-model="form.name" placeholder="Service Name"></el-input>
                  </el-form-item>
                
                <el-form-item  class="form-buttons">
                  <el-button @click="update"> {{ action }}</el-button>
                  <el-button @click="$modal.hide('edit-service')">Cancel</el-button>
                </el-form-item>
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
      form: {
        name: ''
      },
      action: 'Save',
      title: 'Edit Service',
      isProcessing: true,
      formError: {
        name: [],
      },
      loadingText: 'Fetching Data ...',
    }
  },
  methods:{
    beforeOpen (event) {
      this.action = 'Update';
      this.title = 'Edit Service';     
      axios.get('/api/services/' + event.params.data.id)
      .then(response => {
        this.isProcessing = false;     
        this.form.name = response.data.name;
        this.form.id = response.data.id;
      })
    },
    update(){
      this.loadingText = 'Updating ..'
      this.isProcessing = true;
      var vm = this;
      axios.put('/api/services/' + this.form.id, this.form)
      .then( response => {
        this.isProcessing = false;                                    
        swal({
          title: 'Success!',
          text: 'Service is updated!',
          type: 'success'
        }).then( function() {
           vm.$modal.hide('edit-service');
            vm.$emit('updated',response.data);
        });
      })
      .catch ( error => {
          this.isProcessing = false;
          this.formError = { name: [] };
          if(error.response.status == 422){
            this.formError = error.response.data.errors;
            swal('Saving Failed!','Form validation failed! ', 'error');
          }
          else {
            swal('Saving Failed!','Server Error! ', 'error');  
          }
      });
    },
  }
}
</script>

<style>

</style>
