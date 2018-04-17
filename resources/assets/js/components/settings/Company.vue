<template>
    <section class="content company">
        <div class="content-header">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-title">
                        <h1> Company Details </h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <section class="buzz-section">
                <div class="buzz-form">
                    <el-form :model="form" ref="form" :rules="rules" :label-position="labelPosition" label-width="330px">
                        <el-form-item label="Company Name" prop="companyName">
                            <el-input v-model="form.companyName"></el-input>
                        </el-form-item>
                        <el-form-item label="Address" prop="address">
                            <el-input v-model="form.address"></el-input>
                        </el-form-item>
                        <el-form-item label="City" prop="city">
                            <el-input v-model="form.city"></el-input>
                        </el-form-item>
                        <el-form-item label="State" prop="state">
                            <el-input v-model="form.state"></el-input>
                        </el-form-item>
                        <el-form-item label="Zip/Post Code" prop="zipCode">
                            <el-input v-model="form.zipCode"></el-input>
                        </el-form-item>
                        <el-form-item label="Country" prop="country">
                            <el-select v-model="form.country" placeholder="Please select a Country">
                                <el-option label="Canada" value="Canada"></el-option>
                                <el-option label="Philippines" value="Philippines"></el-option>
                            </el-select>
                        </el-form-item>
                        <el-form-item label="Telephone" prop="telephone">
                            <el-input v-model.number="form.telephone"></el-input>
                        </el-form-item>
                        <el-form-item label="System Email" prop="systemEmail">
                            <el-input v-model="form.systemEmail"></el-input>
                        </el-form-item>
                        <el-form-item label="Email (From Name)" prop="emailFromName">
                            <el-input v-model="form.emailFromName"></el-input>
                        </el-form-item>
                        <el-form-item label="Email Signature" prop="emailSignature"> 
                            <quill-editor 
                                class="settings-editor" 
                                v-model="form.emailSignature" 
                                ref="myQuillEditor">
                            </quill-editor>
                            <!-- <el-input type="textarea" v-model="form.emailSignature" :rows="10" resize="none"></el-input> -->
                        </el-form-item>
                        <el-form-item class="buzz-form-button">
                            <el-button @click="submitForm('form')"> Save Changes </el-button>
                        </el-form-item>
                    </el-form>
                </div>
            </section>
        </div>
    </section>
</template>


<script>
  export default {
    data() {
      return {
        labelPosition: 'right',
        form: {
          companyName: '',
          address: '',
          city: '',
          state: '',
          zipCode: '',
          country: '',
          telephone: '',
          systemEmail: '',
          emailFromName: '',
          emailSignature: '',
        },
        rules: {
            companyName: [
                { required: true, message: 'Company Name is required', trigger: 'change' },
            ],
            telephone: [
                { type: 'number', message: 'Telephone must be a number'}
            ],
            systemEmail: [
                { required: true, message: 'System Email is required', trigger: 'change' },
            ],
            emailFromName: [
                { required: true, message: 'Email (From Name) is required', trigger: 'change' },
            ],
            emailSignature: [
                { required: true, message: 'Email Signature is required', trigger: 'change' },
            ],
         },
      };
    },
    methods: {
      submitForm(formName) {
        this.$refs[formName].validate((valid) => {
          if (valid) {
            alert('submit!');
          } else {
            console.log('error submit!!');
            return false;
          }
        });
      },
    }
  }
</script>