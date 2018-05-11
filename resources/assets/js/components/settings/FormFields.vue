 <template>
    <section class="content form-fields">
        <v-layout row wrap>
            <div class="content-header col-md-12">
                <div class="page-title">
                    <h1> Additional Form Fields </h1>
                </div>
            </div>
            <div class="col-md-12">
                <div class="content-body">
                    <el-form :model="form" ref="form">
                        <section class="buzz-section client">
                            <div class="page-title">
                                <h2> Client Sign Up Form </h2>
                            </div>
                                <table>
                                    <thead>
                                        <tr>
                                            <th> Label </th>
                                            <th> Required </th>
                                            <th> Status </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td> 
                                                <el-input placeholder="Please input" v-model="form.clientLabel1"></el-input>
                                            </td>
                                            <td>
                                                <el-select v-model="form.clientRequired1">
                                                    <el-option label="No" value="No"></el-option>
                                                    <el-option label="Yes" value="Yes"></el-option>
                                                </el-select>
                                            </td>
                                            <td>
                                                <el-select v-model="form.clientStatus1">
                                                    <el-option label="Enabled" value="Enabled"></el-option>
                                                    <el-option label="Disabled" value="Disabled"></el-option>
                                                </el-select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> 
                                                <el-input placeholder="Please input" v-model="form.clientLabel2"></el-input>
                                            </td>
                                            <td>
                                                <el-select v-model="form.clientRequired2">
                                                    <el-option label="No" value="No"></el-option>
                                                    <el-option label="Yes" value="Yes"></el-option>
                                                </el-select>
                                            </td>
                                            <td>
                                                <el-select v-model="form.clientStatus2">
                                                    <el-option label="Enabled" value="Enabled"></el-option>
                                                    <el-option label="Disabled" value="Disabled"></el-option>
                                                </el-select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> 
                                                <el-input placeholder="Please input" v-model="form.clientLabel3"></el-input>
                                            </td>
                                            <td>
                                                <el-select v-model="form.clientRequired3">
                                                    <el-option label="No" value="No"></el-option>
                                                    <el-option label="Yes" value="Yes"></el-option>
                                                </el-select>
                                            </td>
                                            <td>
                                                <el-select v-model="form.clientStatus3">
                                                    <el-option label="Enabled" value="Enabled"></el-option>
                                                    <el-option label="Disabled" value="Disabled"></el-option>
                                                </el-select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                        </section>
                        <section class="buzz-section project">
                            <div class="page-title">
                                <h2> Project Details Form </h2>
                                <table>
                                    <thead>
                                        <tr>
                                            <th> Label </th>
                                            <th> Required </th>
                                            <th> Status </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td> 
                                                <el-input placeholder="Please input" v-model="form.projectLabel1"></el-input>
                                            </td>
                                            <td>
                                                <el-select v-model="form.projectRequired1">
                                                    <el-option label="No" value="No"></el-option>
                                                    <el-option label="Yes" value="Yes"></el-option>
                                                </el-select>
                                            </td>
                                            <td>
                                                <el-select v-model="form.projectStatus1">
                                                    <el-option label="Enabled" value="Enabled"></el-option>
                                                    <el-option label="Disabled" value="Disabled"></el-option>
                                                </el-select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> 
                                                <el-input placeholder="Please input" v-model="form.projectLabel2"></el-input>
                                            </td>
                                            <td>
                                                <el-select v-model="form.projectRequired2">
                                                    <el-option label="No" value="No"></el-option>
                                                    <el-option label="Yes" value="Yes"></el-option>
                                                </el-select>
                                            </td>
                                            <td>
                                                <el-select v-model="form.projectStatus2">
                                                    <el-option label="Enabled" value="Enabled"></el-option>
                                                    <el-option label="Disabled" value="Disabled"></el-option>
                                                </el-select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> 
                                                <el-input placeholder="Please input" v-model="form.projectLabel3"></el-input>
                                            </td>
                                            <td>
                                                <el-select v-model="form.projectRequired3">
                                                    <el-option label="No" value="No"></el-option>
                                                    <el-option label="Yes" value="Yes"></el-option>
                                                </el-select>
                                            </td>
                                            <td>
                                                <el-select v-model="form.projectStatus3">
                                                    <el-option label="Enabled" value="Enabled"></el-option>
                                                    <el-option label="Disabled" value="Disabled"></el-option>
                                                </el-select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> 
                                                <el-input placeholder="Please input" v-model="form.projectLabel4"></el-input>
                                            </td>
                                            <td>
                                                <el-select v-model="form.projectRequired4">
                                                    <el-option label="No" value="No"></el-option>
                                                    <el-option label="Yes" value="Yes"></el-option>
                                                </el-select>
                                            </td>
                                            <td>
                                                <el-select v-model="form.projectStatus4">
                                                    <el-option label="Enabled" value="Enabled"></el-option>
                                                    <el-option label="Disabled" value="Disabled"></el-option>
                                                </el-select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> 
                                                <el-input placeholder="Please input" v-model="form.projectLabel5"></el-input>
                                            </td>
                                            <td>
                                                <el-select v-model="form.projectRequired5">
                                                    <el-option label="No" value="No"></el-option>
                                                    <el-option label="Yes" value="Yes"></el-option>
                                                </el-select>
                                            </td>
                                            <td>
                                                <el-select v-model="form.projectStatus5">
                                                    <el-option label="Enabled" value="Enabled"></el-option>
                                                    <el-option label="Disabled" value="Disabled"></el-option>
                                                </el-select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    </el-form>
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
          clientLabel1: 'Sample Text',
          clientRequired1: 'No',
          clientStatus1: 'Disabled',

          clientLabel2: 'Sample Text 2',
          clientRequired2: 'No',
          clientStatus2: 'Disabled',

          clientLabel3: 'Sample Text 3',
          clientRequired3: 'No',
          clientStatus3: 'Disabled',

          projectLabel1: 'CLIENT PORTAL',
          projectRequired1: 'No',
          projectStatus1: 'Enabled',

          projectLabel2: 'Domain',
          projectRequired2: 'No',
          projectStatus2: 'Enabled',

          projectLabel3: 'Pass',
          projectRequired3: 'No',
          projectStatus3: 'Enabled',

          projectLabel4: 'Sample 1',
          projectRequired4: 'No',
          projectStatus4: 'Enabled',

          projectLabel5: 'Sample 2',
          projectRequired5: 'No',
          projectStatus5: 'Enabled',
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