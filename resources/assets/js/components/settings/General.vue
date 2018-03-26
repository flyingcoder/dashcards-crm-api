<template>
    <section class="content general">
        <div class="content-header">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-title">
                        <h1> General Settings </h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <section class="buzz-section">
                <div class="buzz-form">
                    <el-form :model="form" ref="form" :rules="rules" :label-position="labelPosition" label-width="330px">
                        <el-form-item label="Dashboard Title" prop="dashboardTitle">
                            <el-input v-model="form.dashboardTitle"></el-input>
                        </el-form-item>
                        <el-form-item label="Language" prop="language">
                            <el-select v-model="form.language" placeholder="Please select a language">
                                <el-option label="English" value="English"></el-option>
                                <el-option label="Chinese" value="Chinese"></el-option>
                            </el-select>
                        </el-form-item>
                        <el-form-item label="Theme" prop="theme">
                            <el-select v-model="form.theme" placeholder="Please select a theme">
                                <el-option label="Default" value="Default"></el-option>
                                <el-option label="Theme 2" value="Theme 2"></el-option>
                            </el-select>
                        </el-form-item>
                        <el-form-item label="Date Format" prop="dateFormat">
                            <el-select v-model="form.dateFormat" placeholder="Please date format">
                                <el-option label="D-M-Y" value="D-M-Y"></el-option>
                                <el-option label="D/M/Y" value="D/M/Y"></el-option>
                            </el-select>
                        </el-form-item>
                        <el-form-item label="General Page Limits" prop="generalPageLimit">
                            <el-input v-model.number="form.generalPageLimit" auto-complete="off"></el-input>
                        </el-form-item>
                        <el-form-item label="Messages Page Limits" prop="messagesPageLimit">
                            <el-input v-model.number="form.messagesPageLimit" auto-complete="off"></el-input>
                        </el-form-item>
                        <el-form-item label="Timeline Display Limits" prop="timelineDisplayLimits">
                            <el-input v-model.number="form.timelineDisplayLimits" auto-complete="off"></el-input>
                        </el-form-item>
                        <el-form-item label="Currency" prop="currency">
                            <el-select v-model="form.currency" placeholder="Please select currency">
                            <el-option label="USD" value="USD"></el-option>
                            <el-option label="PHP" value="PHP"></el-option>
                            </el-select>
                        </el-form-item>
                        <el-form-item label="Currency Symbol" prop="currencySymbol">
                            <el-select v-model="form.currencySymbol" placeholder="Please select currency symbol">
                            <el-option label="$" value="$"></el-option>
                            <el-option label="@" value="@"></el-option>
                            </el-select>
                        </el-form-item>
                        <el-form-item label="Show Information Tips" prop="infoTips">
                            <el-select v-model="form.infoTips">
                            <el-option label="Yes" value="Yes"></el-option>
                            <el-option label="No" value="No"></el-option>
                            </el-select>
                        </el-form-item>
                        <el-form-item label="Allow Client Registration" prop="clientRegistration">
                            <el-select v-model="form.clientRegistration">
                            <el-option label="Yes" value="Yes"></el-option>
                            <el-option label="No" value="No"></el-option>
                            </el-select>
                        </el-form-item>
                        <el-form-item label="Notifications - Display Duration (milliseconds)" prop="notificationDuration" class="line-2">
                            <el-input v-model.number="form.notificationDuration"></el-input>
                        </el-form-item>
                        <el-form-item label="Product Purchase Code (license number)" prop="purchaseCode" class="line-2">
                            <el-input v-model="form.purchaseCode" readonly></el-input>
                        </el-form-item>
                        <el-form-item class="buzz-form-button">
                            <el-button> Reset Defaul Settings</el-button>
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
          dashboardTitle: '',
          language: '',
          theme: '',
          dateFormat: '',
          generalPageLimit: '',
          messagesPageLimit: '',
          timelineDisplayLimits: '',
          currency: '',
          currencySymbol: '',
          infoTips: '',
          clientRegistration: '',
          notificationDuration: '',
          purchaseCode: 'a3c139ff-a449-41eb982ea08bfeb',
        },
        rules: {
            dashboardTitle: [
                { required: true, message: 'Please enter Dashboard Title', trigger: 'blur' },
            ],
            language: [
                { required: true, message: 'Please select Language', trigger: 'change' }
            ],
            theme: [
                { required: true, message: 'Please select Theme', trigger: 'change' }
            ],
            dateFormat: [
                { required: true, message: 'Please select Date Format', trigger: 'change' }
            ],
            generalPageLimit: [
                { required: true, message: 'Please enter General Page Limits'},
                { type: 'number', message: 'General Page Limits must be a number'}
            ],
            messagesPageLimit: [
                { required: true, message: 'Please enter Messages Page Limits'},
                { type: 'number', message: 'Messages Page Limits must be a number'}
            ],
            timelineDisplayLimits: [
                { required: true, message: 'Please enter Timeline Page Limits'},
                { type: 'number', message: 'Timeline Page Limits must be a number'}
            ],
            currency: [
                { required: true, message: 'Please select Currency', trigger: 'change' }
            ],
            currencySymbol: [
                { required: true, message: 'Please enter Currency Symbol', trigger: 'change' }
            ],
            infoTips: [
                { required: true, message: 'Show Info Tips is required', trigger: 'change' }
            ],
            clientRegistration: [
                { required: true, message: 'Allow Client Registration is required', trigger: 'change' }
            ],
            notificationDuration: [
                { required: true, message: 'Please enter Notification Display Duration', trigger: 'change' },
                { type: 'number', message: 'Notification Display Duration must be a number'}
            ],
            purchaseCode: [
                { required: true, message: 'Product Purchase Code is Required', trigger: 'change' }
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