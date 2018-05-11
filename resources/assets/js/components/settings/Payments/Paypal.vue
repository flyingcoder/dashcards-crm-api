<template>
    <section class="content paypal">
        <v-layout row wrap>
            <div class="content-header col-md-12">
                <div class="page-title">
                    <h1> Paypal Details </h1>
                </div>
            </div>
            <div class="col-md-12">
                <div class="content-body">
                    <section class="buzz-section">
                        <div class="buzz-form">
                            <el-form :model="form" ref="form" :rules="rules" :label-position="labelPosition" label-width="330px">
                                <el-form-item label="Gateway Active" prop="gatewayActive">
                                    <el-select v-model="form.gatewayActive">
                                        <el-option label="Yes" value="Yes"></el-option>
                                        <el-option label="No" value="No"></el-option>
                                    </el-select>
                                </el-form-item>
                                <el-form-item label="Paypal Email Address" prop="paypalEmailAddress">
                                    <el-input v-model="form.paypalEmailAddress" auto-complete="off"></el-input>
                                </el-form-item>
                                <el-form-item label="Currency" prop="currency">
                                    <el-select v-model="form.currency">
                                    <el-option label="USD" value="USD"></el-option>
                                    <el-option label="PHP" value="PHP"></el-option>
                                    </el-select>
                                </el-form-item>
                                <el-form-item label="Paypal IPN" prop="paypalIPN">
                                    <el-input v-model="form.paypalIPN" auto-complete="off"></el-input>
                                </el-form-item>
                                <el-form-item class="buzz-form-button">
                                    <el-button @click="submitForm('form')"> Save Changes </el-button>
                                </el-form-item>
                            </el-form>
                        </div>
                    </section>
                </div>
            </div>
         </v-layout>
    </section>
</template>

<script>
  export default {
    data() {
      return {
        labelPosition: 'right',
        form: {
          gatewayActive: '',
          paypalEmailAddress: '',
          currency: '',
          paypalIPN: '',
        },
        rules: {
            gatewayActive: [
                { required: true, message: 'Gateway Active is required', trigger: 'change' },
            ],
            paypalEmailAddress: [
                { required: true, message: 'Paypal Email Address is required', trigger: 'change' }
            ],
            currency: [
                { required: true, message: 'Currency is required', trigger: 'change' }
            ],
            paypalIPN: [
                { required: true, message: 'Paypal IPN is required', trigger: 'change' }
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