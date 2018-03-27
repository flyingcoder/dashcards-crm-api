<template>
  
</template>

<script>
export default {
    	data: function () {
        	return {    
                title: 'Add New Client',
                action: 'Save',
                id: 0,
                oldName: '',
                isProcessing: false,
                form: {
                    name: '',
                },
        		error: {
        			name: [],
        		},  
        	}
        },

        methods: {
          beforeOpen (event) {
              if(typeof event.params != 'undefined' && event.params.action == 'update') {
                  this.action = 'Update';
                  this.header = 'Edit Client';
                  this.id = event.params.data.id;
                  var vm = this;
                  axios.get('api/clients/'+this.id)
                      .then( response => {
                          this.form = response.data;
                      });
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
              axios.post('/api/clients/new',this.form)
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
              axios.put('/api/clients/'+this.id+'/edit', this.form)
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

<style>

</style>
