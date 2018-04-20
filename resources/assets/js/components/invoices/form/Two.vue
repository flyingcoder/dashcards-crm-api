<template>
    <div class="tab-pane fade tab-table" id="form-2">
        <div class="form-progress">
            <el-tooltip content="Additional Info" placement="top">
                <div class="form-prog circle active-circle"> </div>
            </el-tooltip>
            <div class="form-prog progress active-progress"> </div>
            <el-tooltip content="Basic Info 1" placement="top">
                <div class="form-prog circle active-circle"> </div>
            </el-tooltip>
            <div class="form-prog progress"> </div>
            <el-tooltip content="Basic Info 2" placement="top">
                <div class="form-prog circle"> </div>
            </el-tooltip>
        </div>
        <div class="form-info">
            <h1>Basic Info (1)</h1>
            <p>This is a dummy text for detailed information about this page</p>
        </div>
        <el-form :model="form" status-icon :rules="rules" ref="form" :label-position="labelPosition">
            <el-form-item label="Name" prop="name">
                <el-input type="name" v-model="form.name"></el-input>
            </el-form-item>
            <el-form-item label="Lastname" prop="lastname">
                <el-input type="lastname" v-model="form.lastname"></el-input>
            </el-form-item>
            <el-form-item label="Address" prop="address">
                <el-input type="address" v-model="form.address"></el-input>
            </el-form-item>
            <el-form-item label="City" prop="city">
                <el-input type="city" v-model="form.city"></el-input>
            </el-form-item>
            <el-form-item label="State" prop="state">
                <el-input type="state" v-model="form.state"></el-input>
            </el-form-item>
            <el-form-item label="Zip Code" prop="zipcode">
                <el-input type="zipcode" v-model="form.zipcode"></el-input>
            </el-form-item>
            <el-form-item label="Phone No." prop="phone">
                <el-input type="phone" v-model="form.phone"></el-input>
            </el-form-item>
            <el-form-item label="Email" prop="email">
                <el-input type="email" v-model="form.email"></el-input>
            </el-form-item>
            <el-form-item>
                <ul class="buzz-form-buttons">
                    <li> 
                        <a href="#form-1" data-toggle="tab"> 
                            <div class="prev"> Previous </div> 
                        </a> 
                    </li>
                    <li> 
                        <a href="#form-3" data-toggle="tab"> 
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
          name: '',
          lastname: '',
          address: '',
          city: '',
          state: '',
          zipcode: '',
          phone: '',
          email: '',
        },
        rules: {
          name: [
            { required: true, message: 'Please enter your Name', trigger: 'change' }
          ],
          lastname: [
            { required: true, message: 'Please enter your Last Name', trigger: 'change' }
          ],
          address: [
            { required: true, message: 'Please enter your Address', trigger: 'change' }
          ],
          city: [
            { required: true, message: 'Please enter your City', trigger: 'change' }
          ],
          state: [
            { required: true, message: 'Please enter your State', trigger: 'change' }
          ],
          zipcode: [
            { required: true, message: 'Please enter your Zip Code', trigger: 'change' }
          ],
          phone: [
            { required: true, message: 'Please enter your Phone', trigger: 'change' }
          ],
          email: [
            { required: true, message: 'Please enter your Email', trigger: 'change' }
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
    }
  }
</script>