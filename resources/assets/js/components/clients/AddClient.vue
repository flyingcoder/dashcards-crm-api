<template>
    <modal name="add-client" transition="nice-modal-fade" @before-open="beforeOpen">
        <section class="content">
            <div class="buzz-modal-header"> {{ title }} </div>
            <div class="buzz-scrollbar" id="buzz-scroll">
                <el-form ref="form" :model="form" :rules="rules" label-position="top" v-loading="isProcessing" style="width: 100%">                    
                    <div class="buzz-modal-content">
                        <el-form-item label="Full Name" prop="name">
                            <el-input type="text" v-model="form.name" placeholder="Full Name"></el-input>
                        </el-form-item>
                        <el-form-item label="Company Name" prop="company_name">
                            <el-input type="text" v-model="form.company_name" placeholder="Company Name"></el-input>
                        </el-form-item>
                        <el-form-item label="Contact Number" prop="contact_no">
                            <el-input type="text" v-model="form.telephone" placeholder="Contact Number"></el-input>
                        </el-form-item>
                        <el-form-item label="Email" prop="email">
                            <el-input type="text" v-model="form.email" placeholder="Email"></el-input>
                        </el-form-item>
                        <el-form-item label="Password" prop="pass">
                            <el-input type="password" v-model="form.password" auto-complete="off"></el-input>
                        </el-form-item>
                        <el-form-item label="Confirm" prop="checkPass">
                            <el-input type="password" v-model="form.checkPass" auto-complete="off"></el-input>
                        </el-form-item>
                        <el-form-item label="Client Status">
                            <el-select v-model="form.status" clearable placeholder="Client Status">
                                <el-option
                                v-for="item in status"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                                </el-option>
                            </el-select>
                        </el-form-item>
                        <el-form-item  class="form-buttons">
                            <el-button type="primary" @click="submit">Save</el-button>
                            <!-- <el-button @click="resetForm('form')">Reset</el-button> -->
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
    	data: function () {
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
                callback(new Error('Two inputs don\'t match!'));
                } else {
                callback();
                }
            };
        	return {    
                title: 'Add New Client',
                action: 'Save',
                id: 0,
                oldName: '',
                isProcessing: false,
                form: {
                    name: '',
                    company_name: '',
                    telephone: '',
                    email: '',
                    password: '',
                    status: '',
                },
        		error: {
        			name: [],
        			company_name: [],
        			telephone: [],
                    email: [],
                    password: [],
                    status: [],
        		},
                status: [{
                    value: 'Active',
                    label: 'Active'
                    }, {
                    value: 'Inactive',
                    label: 'Inactive'
                }],
                rules: {
                    name: [
                        { required: true, message: 'Please input Client Name', trigger: 'change' },
                    ],
                    company_name: [
                        { required: true, message: 'Please input Company Name', trigger: 'change' },
                    ],
                    telephone: [
                        { required: true, message: 'Please input Contact Number', trigger: 'change' },
                    ],
                    email: [
                        { required: true, message: 'Please input Email', trigger: 'change' },
                    ],
                    password: [
                        { validator: validatePass, trigger: 'blur' }
                    ],
                    checkPass: [
                        { validator: validatePass2, trigger: 'blur' }
                    ],
                    status: [
                        { required: true, message: 'Please Select Status', trigger: 'change' },
                    ],
                },
                config: {
                    toolbar: [
                      [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript' ]
                    ],
                    
                    height: 500
                }
        	}
        },

        methods: {
             submitForm(form) {
                this.$refs[form].validate((valid) => {
                if (valid) {
                    alert('submit!');
                } else {
                    console.log('error submit!!');
                    return false;
                }
                });
            },
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
              axios.post('/api/clients/',this.form)
              .then( response => {
                  this.isProcessing = false;
                  swal('Success!', 'Project is saved!', 'success');
                  this.$parent.getAllClients();
                  this.$modal.hide('add-client')
              })
              .catch ( error => {
                  this.isProcessing = false;
                  if(error.response.status == 422){
                  this.errors = error.response.data.errors;
                  }
              })
          },
          update: function () {
              axios.put('/api/clients/'+this.id, this.form)
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