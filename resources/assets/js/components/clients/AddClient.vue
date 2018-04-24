<template>
    <modal name="add-client" transition="nice-modal-fade" @before-open="beforeOpen">
        <section class="content add-client">
            <div class="buzz-modal-header"> {{ title }} </div>
            <div class="buzz-scrollbar" id="buzz-scroll">
                <el-form ref="form" :inline="true" :model="form" :rules="rules" label-position="top" v-loading="isProcessing" style="width: 100%">                    
                    <div class="buzz-modal-content">
                        <el-form-item prop="firstname" class="buzz-input buzz-inline">
                            <el-input type="text" v-model="form.firstname" placeholder="First Name"></el-input>
                        </el-form-item>
                        <el-form-item prop="lastname" class="buzz-input buzz-inline pull-right">
                            <el-input type="text" v-model="form.lastname" placeholder="Last Name"></el-input>
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
                        <el-form-item class="buzz-input buzz-inline pull-right">
                            <el-radio-group v-model="form.status" size="medium">
                            <el-radio border label="Active"></el-radio>
                            <el-radio border label="Inactive"></el-radio>
                            </el-radio-group>
                        </el-form-item>
                        <el-form-item prop="pass" class="buzz-input buzz-inline">
                            <el-input type="password" v-model="form.pass" placeholder="Password" auto-complete="off"></el-input>
                        </el-form-item>
                        <el-form-item prop="checkPass" class="buzz-input buzz-inline pull-right">
                            <el-input type="password" v-model="form.checkPass" placeholder="Confirm" auto-complete="off"></el-input>
                        </el-form-item>
                        <el-form-item  class="form-buttons">
                            <el-button type="primary" @click="submit('form')">Save</el-button>
                            <el-button @click="$modal.hide('add-client')">Cancel</el-button>
                        </el-form-item>
                    </div>
                </el-form>
            </div>
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
        } else if (value !== this.form.pass) {
          callback(new Error('Password don\'t match!'));
        } else {
          callback();
        }
      };
      return {
        title: 'Add New Client',
        action: 'Save',
        isProcessing: false,
        form: {
            firstname: '',
            lastname: '',
            company_name: '',
            telephone: '',
            email: '',
            pass: '',
            status: '',
        },
         rules: {
            firstname: [
                { required: true, message: 'First Name is Required', trigger: 'change' },
            ],
            lastname: [
                { required: true, message: 'Last Name is Required', trigger: 'change' },
            ],
            company_name: [
                { required: true, message: 'Company Name is Required', trigger: 'change' },
            ],
            telephone: [
                { required: true, message: 'Contact No. is Required', trigger: 'change' },
                { type: 'number', message: 'Contact No. is Invalid', trigger: 'change' },
                { min: 11, max: 11, message: 'Length', trigger: 'blur' }
            ],
            email: [
                { required: true, message: 'Email is Required', trigger: 'change' },
                { type: 'email', message: 'Email Address is Invalid', trigger: ['blur', 'change'] }
            ],
            pass: [
                { required: true, message: 'Password is Required', trigger: 'change' },
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
              console.info('before Opent');
              if(typeof event.params != 'undefined' && event.params.action == 'Update') {
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
        submit(form) {
            this.$refs[form].validate((valid) => {
            if (valid) {
                alert('submit!');
            } else {
                console.log('error submit!!');
                return false;
            }
            });
        },
      resetForm(formName) {
        this.$refs[formName].resetFields();
      }
    }
  }
</script>
