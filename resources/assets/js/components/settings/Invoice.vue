<template>
    <section class="content invoice-settings">
        <div class="content-header">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-title">
                        <h1> Invoice Settings </h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <section class="buzz-section">
                <div class="buzz-form">
                    <el-form :model="form" ref="form" :rules="rules" :label-position="labelPosition" label-width="330px">
                        <el-form-item label="Allow Partial Payments" prop="allowPartialPayments">
                            <el-select v-model="form.allowPartialPayments">
                                <el-option label="Yes" value="Yes"></el-option>
                                <el-option label="No" value="No"></el-option>
                            </el-select>
                        </el-form-item>
                        <el-form-item label="Email Overdue Reminder" prop="emailOverdueReminder">
                            <el-select v-model="form.emailOverdueReminder">
                                <el-option label="Yes" value="Yes"></el-option>
                                <el-option label="No" value="No"></el-option>
                            </el-select>
                        </el-form-item>
                        <el-form-item label="Invoice Notes" prop="invoiceNotes"> 
                            <el-input type="textarea" v-model="form.invoiceNotes" :rows="10" resize="none"></el-input>
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
          allowPartialPayments: '',
          emailOverdueReminder: '',
          invoiceNotes: '',
        },
        rules: {
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