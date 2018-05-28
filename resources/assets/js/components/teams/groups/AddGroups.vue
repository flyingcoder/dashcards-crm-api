<template>
    <modal name="add-groups" transition="nice-modal-fade" @before-open="beforeOpen">
        <section class="content add-group">
            <v-layout row wrap>
                <div class="buzz-modal-header"> {{ title }} </div>
                <div class="buzz-scrollbar" id="buzz-scroll">
                    <el-form ref="form" name="addGroup" status-icon :model="form" :rules="rules" v-loading="isProcessing" style="width: 100%">                    
                        <div class="buzz-modal-content">
                            <el-form-item prop="name" class="buzz-input">
                                <el-input type="text" v-model="form.name" placeholder="Group Name"></el-input>
                            </el-form-item>
                            <el-form-item  class="form-buttons">
                                <el-button type="primary" @click="submit('form')"> {{action}} </el-button>
                                <el-button @click="$modal.hide('add-groups')">Cancel</el-button>
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
    data() {
      return {
        title: 'Add New Group',
        action: 'Save',
        isProcessing: false,
        errors: {

        },
        form: {
          name: '',
        },
        rules: {
            name: [
                { required: true, message: 'Group Name is Required', trigger: 'change' },
            ],
        },
      };
    },
    methods: {
        beforeOpen (event) {
            if(typeof event.params != 'undefined' && event.params.action == 'Update') {
                var vm = this;
                this.action = 'Update';
                this.title = 'Edit Group';
                this.id = event.params.data.id;
                axios.get('/api/groups/'+this.id)
                    .then( (response) => {
                        this.form = response.data;
                    });
            }
        },
        submit(form) {
            this.$refs[form].validate((valid) => {
                if (valid) {
                    if(this.action == 'Save'){
                            this.save();
                            console.log('save!!');
                        }
                        else{
                            this.update();
                            console.log('update!!');
                        }
                    } else {
                    console.log('error submit!!');
                    return false;
                }
            });
        },
        save: function () {
            this.isProcessing = true;
            var vm = this;
            axios.post('/api/groups/',this.form)
            .then( (response) => {
                vm.id = response.data.id;               
                swal('Success!', 'Group is saved!', 'success');
                this.isProcessing = false;
                vm.$modal.hide('add-groups');
                vm.$emit('refresh');
                vm.resetForm();
                console.log(vm.id);
            }, (error) => {
                this.isProcessing = false;
                if(error.response.status == 422){
                    this.errors = error.response.data.errors;
                    for( var value in error.response.data.errors) {
                        console.log(value)
                        if(value == 'email'){
                        swal('Saving Failed!', error.response.data.errors.email[0], 'error');
                        }
                    }
                } else {
                    swal('Saving Failed!', error.response.data, 'error');
                } 
            });
        },
        update: function () {
            this.isProcessing = true;
            var vm = this;
            axios.put('/api/groups/'+this.id, this.form)
            .then( (response) => {
                swal('Success!', 'Group is updated!', 'success');
                this.isProcessing = false;
                vm.$modal.hide('add-groups');
                vm.$emit('refresh');
                vm.resetForm();
            }, (error) => {
                this.isProcessing = false;
                if(error.response.status == 422){
                    this.errors = error.response.data.errors;
                    for( var value in error.response.data.errors) {
                        console.log(value)
                        if(value == 'email'){
                        swal('Saving Failed!', error.response.data.errors.email[0], 'error');
                        }
                    }
                } else {
                    swal('Saving Failed!', error.response.data, 'error');
                } 
            });
        },
        resetForm() {
            this.form = {
                group_name: '',
            }
        },
    }
  }
</script>