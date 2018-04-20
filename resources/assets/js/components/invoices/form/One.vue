<template>
    <div class="tab-pane fade tab-table active in" id="form-1">
        <div class="form-progress">
            <el-tooltip content="Additional Info" placement="top" class="active">
                <div class="form-prog circle active-circle"> </div>
            </el-tooltip>
            <div class="form-prog progress"> </div>
            <el-tooltip content="Basic Info 1" placement="top">
                <div class="form-prog circle"> </div>
            </el-tooltip>
            <div class="form-prog progress"> </div>
            <el-tooltip content="Basic Info 2" placement="top">
                <div class="form-prog circle"> </div>
            </el-tooltip>
        </div>
        <div class="form-info">
            <h1>Additional Info</h1>
            <p>This is a dummy text for detailed information about this page</p>
        </div>
        <el-form :model="form" status-icon :rules="rules" ref="form" :label-position="labelPosition">
            <el-form-item label="Title" prop="title">
                <el-input type="title" v-model="form.title"></el-input>
            </el-form-item>
            <el-form-item label="Description" prop="description">
                <el-input type="description" v-model="form.description"></el-input>
            </el-form-item>
            <el-form-item label="Rate" prop="rate">
                <el-input type="rate" v-model="form.rate"></el-input>
            </el-form-item>
             <el-form-item label="Quantity" prop="quantity">
                <el-select v-model="form.quantity" placeholder="Quantity">
                <el-option label="1" value="1"></el-option>
                <el-option label="2" value="2"></el-option>
                </el-select>
            </el-form-item>
            <el-form-item>
                <ul class="buzz-form-buttons">
                    <li> 
                        <a href="#form-2" data-toggle="tab"> 
                            <div class="next"> Next </div> 
                        </a> 
                    </li>
                    <li class="save"> 
                         <el-button @click="submitForm('form')"> <span> Submit </span> </el-button>
                    </li>
                </ul>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
    export default {
    data() {
      return {
        labelPosition: 'left',
        form: {
          title: '',
          description: '',
          rate: '',
          quantity: '',
        },
        rules: {
          title: [
            { required: true, message: 'Please input Title', trigger: 'change' }
          ],
          description: [
            { required: true, message: 'Please input Description', trigger: 'change' }
          ],
          rate: [
            { required: true, message: 'Please input Rate', trigger: 'change' }
          ],
          quantity: [
            { required: true, message: 'Please select Quantity', trigger: 'change' }
          ]
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
    }
  }
</script>