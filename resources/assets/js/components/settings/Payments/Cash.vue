<template>
    <section class="content cash">
        <div class="content-header">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-title">
                        <h1> Cash - Details </h1>
                    </div>
                </div>
            </div>
        </div>
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
                        <el-form-item label="Payment Instructions Details" prop="paymentInstructionsDetails"> 
                            <el-input type="textarea" v-model="form.paymentInstructionsDetails" :rows="10" resize="none"></el-input>
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
          gatewayActive: '',
          paymentInstructionsDetails: '',
        },
        rules: {
            gatewayActive: [
                { required: true, message: 'Gateway Active is required', trigger: 'change' },
            ],
            paymentInstructionsDetails: [
                { required: true, message: 'Payment Instructions Details is required', trigger: 'change' }
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