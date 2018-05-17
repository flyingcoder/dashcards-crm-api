<template>
    <modal name="add-member" transition="nice-modal-fade" @before-open="beforeOpen">
        <section class="content add-member">
            <v-layout row wrap>
                <div class="buzz-modal-header"> {{ title }} </div>
                <div class="buzz-scrollbar" id="buzz-scroll">
                    <el-form ref="form" name="addMember" status-icon :inline="true" :model="form" :rules="rules" v-loading="isProcessing" style="width: 100%"> 
                        <div class="buzz-modal-content">
                            <el-form-item prop="first_name" class="buzz-input buzz-inline">
                                <el-input type="text" v-model="form.first_name" placeholder="First Name"></el-input>
                            </el-form-item>
                            <el-form-item prop="last_name" class="buzz-input buzz-inline pull-right">
                                <el-input type="text" v-model="form.last_name" placeholder="Last Name"></el-input>
                            </el-form-item>
                            <el-form-item v-if="passwordEnable" prop="group_name" class="buzz-input buzz-inline">
                                <el-select v-model="form.group_name" clearable placeholder="Select Group" @focus="hideMembers">
                                    <el-option 
                                    v-for="c in groups"
                                    :key="c"
                                    :label="c"
                                    :value="c">
                                    </el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item prop="job_title" class="buzz-input buzz-inline pull-right">
                                <el-input type="text" v-model="form.job_title" placeholder="Job Title"></el-input>
                            </el-form-item>
                            <el-form-item prop="email" class="buzz-input buzz-inline">
                                <el-input type="text" v-model="form.email" placeholder="Email"></el-input>
                            </el-form-item>
                            <el-form-item prop="telephone" class="buzz-input buzz-inline pull-right">
                                <el-input type="text" v-model.number="form.telephone" placeholder="Contact No."></el-input>
                            </el-form-item>
                            <el-form-item v-if="passwordEnable" prop="password" class="buzz-input buzz-inline">
                                <el-input type="password" v-model="form.password" placeholder="Password" auto-complete="off"></el-input>
                            </el-form-item>
                            <el-form-item v-if="passwordEnable" prop="checkPass" class="buzz-input buzz-inline pull-right">
                                <el-input type="password" v-model="form.checkPass" placeholder="Confirm" auto-complete="off"></el-input>
                            </el-form-item>
                            <el-form-item  class="form-buttons">
                                 <el-button type="primary" @click="submit('form')"> {{action}} </el-button>
                                <el-button @click="$modal.hide('add-member')">Cancel</el-button>
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
        title: 'Add New Member',
        action: 'Save',
        isProcessing: false,
        errors: {
        },
        passwordEnable: true,
        groups: [
            'Managers',
            'Developers',
            'Engineers',
            'Staff',
            'Members'
        ],
        form: {
          first_name: '',
          last_name: '',
          group_name: '',
          job_title: '',
          email: '',
          telephone: '',
          password: '',
        }
      };
    },
    computed: {
      rules() {
        return {
            first_name: [
                { required: true, message: 'First Name is Required', trigger: 'change' },
            ],
            last_name: [
                { required: true, message: 'Last Name is Required', trigger: 'change' },
            ],
            group_name: [
                { required: this.passwordEnable, message: 'Group is Required', trigger: 'change' },
            ],
            job_title: [
                { required: true, message: 'Job Title is Required', trigger: 'change' },
            ],
            email: [
                { required: true, message: 'Email is Required', trigger: 'change' },
                { type: 'email', message: 'Email Address is Invalid', trigger: ['blur', 'change'] }
            ],
            telephone: [
                { required: true, message: 'Contact No. is Required', trigger: 'change' },
                { required: true, pattern:/^[0-9]+$/, message: 'Contact No. Must be a Number', trigger: 'blur' },
            ],
            password: [
                { required: this.passwordEnable, message: 'Password is Required', trigger: 'change' },
                { validator: this.validatePass, trigger: 'blur' },
            ],
            checkPass: [
                { validator: this.validatePass2, trigger: 'blur' },
            ],
        }
      }
    },
    methods: {
        hideMembers() {

        },
        beforeOpen (event) {
              console.log('before Open');
              if(typeof event.params != 'undefined' && event.params.action == 'Update') {
                  this.passwordEnable = false;
                  this.action = 'Update';
                  this.title = 'Edit Client';
                  this.id = event.params.id;
                  var vm = this;
                  axios.get('api/company/teams/'+this.id)
                      .then( (response) => {
                          this.form = response.data;
                          this.form.group_name = response.data.teams[0].name;
                          console.log(response.data)
                      });
              }
        },
        submit(form) {
            this.$refs[form].validate((valid) => {
              if (valid) {
                 if(this.action == 'Save'){
                    this.save();
                  } else {
                    this.update();
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
              axios.post('/api/company/teams',this.form)
              .then( (response) => {
                    vm.id = response.data.id;               
                    swal('Success!', 'Member is saved!', 'success');
                    this.isProcessing = false;
                    vm.$modal.hide('add-member');
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
        update: function () {
            this.isProcessing = true;
            var vm = this;
            axios.put('/api/company/teams/'+this.id, this.form)
                  .then( (response) => {
                      this.isProcessing = false;
                      swal('Success!', 'Member is updated!', 'success');
                      this.isProcessing = false;
                      vm.$modal.hide('add-member');
                      vm.$emit('refresh');
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
                group_name: '',
                job_title: '',
                email: '',
                telephone: '',
                password: '',
          }
        }
    }
  }
</script>
