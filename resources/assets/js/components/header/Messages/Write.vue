<template>
    <div class="write-message">
        <el-form :model="form" :rules="rules" ref="form" label-width="120px" class="demo-form">
            <div class="new-message-head">
                <h4>New Message</h4>
            </div>
            <div class="new-message-form">
                <el-input v-model="form.to">
                    <template slot="prepend">To</template>
                    <el-button slot="append"> Cc </el-button>
                    <el-button slot="append"> Bcc </el-button>
                </el-input>
                <el-input v-model="form.subject">
                    <template slot="prepend">Subject</template>
                </el-input>
                <el-form-item class="modal-editor">
                    <ckeditor v-model="form.description"></ckeditor>
                </el-form-item>
            </div>
        </el-form>
    </div>
</template>

<script>
  export default {
    data() {
      return {
        form: {
          to: '',
          subject: '',
          description: '',
          isProcessing: false,
          formError: '',
        },
        error: {
        			to: [],
        			subject: [],
                    description: [],
        		},
        rules: {
          name: [
            { required: true, message: 'Please input Activity name', trigger: 'blur' },
            { min: 3, max: 5, message: 'Length should be 3 to 5', trigger: 'blur' }
          ],
        }
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
      resetForm(formName) {
        this.$refs[formName].resetFields();
      }
    }
  }
</script>