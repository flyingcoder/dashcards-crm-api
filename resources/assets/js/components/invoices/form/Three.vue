<template>
    <div class="tab-pane fade tab-table" id="form-3"> 
        <div class="form-progress">
            <el-tooltip content="Additional Info" placement="top">
                <div class="form-prog circle active-circle"> </div>
            </el-tooltip>
            <div class="form-prog progress active-progress"> </div>
            <el-tooltip content="Basic Info 1" placement="top">
                <div class="form-prog circle active-circle"> </div>
            </el-tooltip>
            <div class="form-prog progress active-progress"> </div>
            <el-tooltip content="Basic Info 2" placement="top">
                <div class="form-prog circle active-circle"> </div>
            </el-tooltip>
        </div>
        <div class="form-info">
            <h1>Basic Info (2)</h1>
            <p>This is a dummy text for detailed information about this page</p>
        </div>
        <el-form :model="form" status-icon :rules="rules" ref="form" :label-position="labelPosition">
            <el-form-item label="Date Billed" prop="dateFrom">
                <el-date-picker
                    v-model="form.dateFrom"
                    type="date"
                    placeholder="Date From">
                </el-date-picker>
            </el-form-item>
            <el-form-item label="Date Billed" prop="dateTo">
                <el-date-picker
                    v-model="form.dateTo"
                    type="date"
                    placeholder="Date To">
                </el-date-picker>
            </el-form-item>
            <el-form-item label="Tax" prop="tax">
                <el-input type="tax" v-model="form.tax"></el-input>
            </el-form-item>
            <el-form-item label="Note" prop="note">
                <el-input type="note" v-model="form.note"></el-input>
            </el-form-item>
            <el-form-item>
                <ul class="buzz-form-buttons">
                    <li> 
                        <a href="#form-2" data-toggle="tab"> 
                            <div class="prev"> Previous </div> 
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
          dateFrom: '',
          dateTo: '',
          tax: '',
          note: '',
        },
        rules: {
          dateFrom: [
            { required: true, message: 'Please select a Date', trigger: 'change' }
          ],
          dateTo: [
            { required: true, message: 'Please select a Date', trigger: 'change' }
          ],
          tax: [
            { required: true, message: 'Please input Tax', trigger: 'change' }
          ],
          note: [
            { required: true, message: 'Please enter a Note', trigger: 'change' }
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