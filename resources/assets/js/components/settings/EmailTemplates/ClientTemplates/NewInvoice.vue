<template>
    <div class="buzz-form">
        <el-form :model="form" ref="form">
            <el-form-item label="" prop="newInvoice">
                <el-input v-model="form.newInvoice" placeholder="New Invoice"></el-input>
            </el-form-item>
            <el-form-item label="" prop="editor"> 
                <el-input type="textarea" v-model="form.editor" :rows="33" resize="none"></el-input>
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
          newInvoice: '',
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