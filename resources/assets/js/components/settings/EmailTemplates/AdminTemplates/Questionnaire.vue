<template>
    <div class="buzz-form">
        <el-form :model="form" ref="form">
            <el-form-item label="" prop="questionnaire">
                <el-input v-model="form.questionnaire" placeholder="Questionnaire"></el-input>
            </el-form-item>
            <el-form-item label="" prop="editor"> 
              <quill-editor 
                  class="email-editor" 
                  v-model="form.editor" 
                  ref="myQuillEditor">
              </quill-editor>
                <!-- <el-input type="textarea" v-model="form.editor" :rows="33" resize="none"></el-input> -->
            </el-form-item>
            <el-form-item class="buzz-form-button">
                <el-button @click="submitForm('form')"> Save Changes </el-button>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
  export default {
    data() {
      return {
        form: {
          questionnaire: '',
          editor: '',
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