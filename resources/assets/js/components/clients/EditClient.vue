<template>
    <modal name="edit-client" transition="nice-modal-fade" @before-open="beforeOpen">
        <section class="content add-client">
            <v-layout row wrap>
                <div class="buzz-modal-header"> {{ title }} </div>
                <div class="buzz-scrollbar" id="buzz-scroll">
                    <el-form ref="form" status-icon :inline="true" :model="form" :rules="rules" v-loading="isProcessing" style="width: 100%">                    
                        <div class="buzz-modal-content">
                            <el-form-item prop="first_name" class="buzz-input buzz-inline">
                                <el-input type="text" v-model="form.first_name" placeholder="First Name"></el-input>
                            </el-form-item>
                            <el-form-item prop="last_name" class="buzz-input buzz-inline pull-right">
                                <el-input type="text" v-model="form.last_name" placeholder="Last Name"></el-input>
                            </el-form-item>
                            <el-form-item prop="company_name" class="buzz-input buzz-inline">
                                <el-input type="text" v-model="form.company_name" placeholder="Company Name"></el-input>
                            </el-form-item>
                            <el-form-item prop="telephone" class="buzz-input buzz-inline pull-right">
                                <el-input type="text" v-model="form.telephone" placeholder="Contact No."></el-input>
                            </el-form-item>
                            <el-form-item prop="email" class="buzz-input buzz-inline">
                                <el-input type="text" v-model="form.email" placeholder="Email"></el-input>
                            </el-form-item>
                            <el-form-item prop="status" class="buzz-input buzz-inline pull-right">
                                <el-radio-group v-model="form.status" size="medium">
                                    <el-radio border label="Active"></el-radio>
                                    <el-radio border label="Inactive"></el-radio>
                                </el-radio-group>
                            </el-form-item>
                            <el-form-item prop="password" class="buzz-input buzz-inline">
                                <el-input type="password" v-model="form.password" placeholder="Password" auto-complete="off"></el-input>
                            </el-form-item>
                            <el-form-item prop="checkPass" class="buzz-input buzz-inline pull-right">
                                <el-input type="password" v-model="form.checkPass" placeholder="Confirm" auto-complete="off"></el-input>
                            </el-form-item>
                            <el-form-item  class="form-buttons">
                                <el-button type="primary" @click="submit('form')"> {{action}} </el-button>
                                <el-button @click="$modal.hide('edit-client')">Cancel</el-button>
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
      var validatePass = (rule, value, callback) => {
        if (value === '') {
          callback(new Error('Please input the password'));
        } else {
          if (this.form.checkPass !== '') {
            this.$refs.form.validateField('checkPass');
          }
          callback();
        }
      };
      var validatePass2 = (rule, value, callback) => {
        if (value === '') {
          callback(new Error('Please input the password again'));
        } else if (value !== this.form.password) {
          callback(new Error('Password don\'t match!'));
        } else {
          callback();
        }
      };
      return {
        title: 'Edit Client',
        action: 'Update',
        isProcessing: false,
        id: 0,
        form: {
            first_name: '',
            last_name: '',
            company_name: '',
            telephone: '',
            email: '',
            password: '',
            status: '',
            image_url: '',
        },
        rules: {
            first_name: [
                { required: true, message: 'First Name is Required', trigger: 'change' },
            ],
            last_name: [
                { required: true, message: 'Last Name is Required', trigger: 'change' },
            ],
            company_name: [
                { required: true, message: 'Company Name is Required', trigger: 'change' },
            ],
            telephone: [
                { required: true, message: 'Contact No. is Required', trigger: 'change' },
                { required: true, pattern:/^[\+\d]+(?:[\d-.\s()]*)$/, message: 'Not a valid number.', trigger: 'blur' },
                //{ min: 6, max: 11, message: 'Invalid Contact Number', trigger: 'blur' },
            ],
            email: [
                { required: true, message: 'Email is Required', trigger: 'change' },
                { type: 'email', message: 'Email Address is Invalid', trigger: ['blur', 'change'] },
            ],
            password: [
                { validator: validatePass, trigger: 'blur' }
            ],
            checkPass: [
                { validator: validatePass2, trigger: 'blur' }
            ],
            status: [
                { required: true, message: 'Status is Required', trigger: 'change' },
            ],
        },
      };
    },
    methods: {
        beforeOpen (event) {
              console.info('before Open');
              this.form = event.params.data;
              this.id = event.params.data.id;
              /*
              if(typeof event.params != 'undefined' && event.params.action == 'Update') {
                  this.action = 'Update';
                  this.header = 'Edit Client';
                  this.id = event.params.data.id;
                  var vm = this;
                  axios.get('api/clients/'+this.id)
                      .then( response => {
                          this.form = response.data;
                          this.form.company_name = response.data.meta.company_name.value
                          this.form.status = response.data.meta.status.value
                      });
              }*/
        },
        handleAdd (file) {
          this.form.image_url = file;
        },
        submit(form) {
            this.$refs[form].validate((valid) => {
            if (valid) {
                this.update();
            } else {
                console.log('error submit!!');
                return false;
            }
            });
        }, 
        update: function () {
            this.isProcessing = true;
            var vm = this;
            axios.put('/api/clients/'+this.id, this.form)
            .then( (response) => {
                swal('Success!', 'Client is updated!', 'success');
                vm.isProcessing = false;
                vm.$modal.hide('edit-client');
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
              first_name: '',
              last_name: '',
              company_name: '',
              telephone: '',
              email: '',
              password: '',
              status: '',
          }
        }
    },
    watch: {
        'form.telephone' : function(val, oldval) {
            val = val.toString();
            this.form.telephone = val.replace(/(\d{3})(\d{3})(\d{4})/, "+1-$1-$2-$3");
        }
    }
  }
</script>
