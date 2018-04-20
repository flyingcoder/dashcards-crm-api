<template>
    <div class="passbox-content passbox-edit">
        <div class="passbox-text">
            <span> PASS  </span>
             <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                width="45px" height="47px">
                <path fill-rule="evenodd"  fill="rgb(59, 88, 158)"
                d="M5.045,14.181 L5.967,14.181 L5.967,5.453 C5.967,3.952 6.646,2.588 7.739,1.600 C8.832,0.612 10.342,-0.001 12.003,-0.001 L32.369,-0.001 C34.030,-0.001 35.539,0.612 36.633,1.600 C37.726,2.588 38.405,3.952 38.405,5.453 L38.405,14.181 L39.786,14.181 C42.293,14.181 44.345,16.035 44.345,18.301 L44.345,42.878 C44.345,45.144 42.293,46.998 39.786,46.998 L5.045,46.998 C2.538,46.998 0.486,45.144 0.486,42.878 L0.486,18.301 C0.486,16.035 2.538,14.181 5.045,14.181 L5.045,14.181 ZM8.587,14.181 L35.785,14.181 L35.785,5.453 C35.785,4.605 35.400,3.834 34.781,3.274 C34.161,2.714 33.307,2.366 32.369,2.366 L12.003,2.366 C11.065,2.366 10.211,2.714 9.591,3.274 C8.972,3.834 8.587,4.605 8.587,5.453 L8.587,14.181 L8.587,14.181 ZM22.415,30.243 C20.191,30.243 18.387,31.873 18.387,33.883 C18.387,35.249 19.219,36.439 20.450,37.062 L20.450,40.785 C20.450,41.761 21.335,42.560 22.415,42.560 L22.415,42.560 C23.496,42.560 24.380,41.761 24.380,40.785 L24.380,37.062 C25.611,36.439 26.444,35.249 26.444,33.883 C26.444,31.873 24.640,30.243 22.415,30.243 Z"/>
            </svg>
            <span> BOX </span> 
        </div> 
        <div class="passbox-edit-form">
            <!-- <form>
                <div class="form-group buzz-group">
                    <label for="url">
                       URL
                    </label>
                    <input type="url" class="form-control" id="URL" />
                </div>
                <div class="form-group buzz-group">
                    <label for="name">
                       Name
                    </label>
                    <input type="text" class="form-control" id="name" />
                </div>
                <div class="form-group buzz-group buzz-addon select">
                    <label for="category">
                       Category
                    </label>
                    <input type="text" class="form-control" id="category" />
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                        width="25px" height="12px">
                        <path fill-rule="evenodd"  fill="rgb(179, 182, 195)"
                        d="M23.963,1.153 C23.642,0.888 23.120,0.888 22.799,1.153 L12.571,9.638 L2.322,1.153 C2.001,0.888 1.479,0.888 1.159,1.153 C0.838,1.419 0.838,1.851 1.159,2.117 L11.969,11.066 C12.129,11.199 12.330,11.265 12.551,11.265 C12.751,11.265 12.972,11.199 13.132,11.066 L23.943,2.117 C24.283,1.851 24.283,1.419 23.963,1.153 Z"/>
                    </svg>
                </div>
                <div class="form-group buzz-group">
                    <label for="username">
                       Username
                    </label>
                    <input type="text" class="form-control" id="username" />
                </div>
                <div class="form-group buzz-group buzz-addon">
                    <label for="password">
                       Password
                    </label>
                    <input type="password" class="form-control" id="password" />
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                        width="42px" height="24px">
                        <path fill-rule="evenodd"  fill="rgb(179, 182, 195)"
                        d="M0.917,10.850 C0.971,10.785 19.577,-11.915 40.367,10.897 L41.369,11.996 L40.422,13.138 C40.364,13.208 20.469,37.698 0.893,13.006 L0.026,11.913 L0.917,10.850 L0.917,10.850 ZM20.330,7.048 C17.596,7.048 15.379,9.401 15.379,12.304 C15.379,15.208 17.596,17.561 20.330,17.561 C23.064,17.561 25.281,15.208 25.281,12.304 C25.281,9.401 23.064,7.048 20.330,7.048 L20.330,7.048 ZM28.068,5.666 C29.450,7.480 30.278,9.790 30.278,12.304 C30.278,14.633 29.568,16.786 28.366,18.532 C32.458,16.564 35.570,13.656 37.079,12.076 C33.987,8.926 30.961,6.886 28.068,5.666 L28.068,5.666 ZM12.459,18.764 C11.157,16.978 10.382,14.737 10.382,12.304 C10.382,10.028 11.060,7.920 12.214,6.195 C8.399,8.009 5.570,10.588 4.187,12.008 C6.970,15.269 9.752,17.428 12.459,18.764 L12.459,18.764 ZM25.713,6.588 C24.336,5.126 22.432,4.221 20.330,4.221 C18.228,4.221 16.325,5.126 14.947,6.588 C13.569,8.051 12.717,10.072 12.717,12.304 C12.717,14.537 13.569,16.558 14.947,18.020 C16.325,19.483 18.228,20.388 20.330,20.388 C22.432,20.388 24.336,19.483 25.713,18.020 C27.091,16.558 27.943,14.537 27.943,12.304 C27.943,10.072 27.091,8.051 25.713,6.588 Z"/>
                    </svg>
                </div>
                <div class="form-group buzz-group">
                    <label for="notes">
                       Notes
                    </label>
                    <input type="text" class="form-control" id="notes" />
                </div>
                <div class="buzz-form-buttons">
                    <ul>
                        <li class="Cancel"> 
                            <button type="cencel"> <span> Cancel </span> </button> 
                        </li>
                        <li class="save"> 
                            <button type="submit"> <span> Save </span> </button> 
                        </li>
                    </ul>
                </div>
            </form> -->
            <el-form :model="passboxForm" :rules="rules" ref="passboxForm" :label-position="labelPosition" label-width="100px">
                <el-form-item label="URL" prop="url">
                    <el-input v-model="passboxForm.url"></el-input>
                </el-form-item>
                <el-form-item label="Name" prop="name">
                    <el-input v-model="passboxForm.name"></el-input>
                </el-form-item>
                <el-form-item label="Category" prop="category">
                    <el-select v-model="passboxForm.category" placeholder="Select category">
                    <el-option label="Zone one" value="shanghai"></el-option>
                    <el-option label="Zone two" value="beijing"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="Username" prop="username">
                    <el-input v-model="passboxForm.username"></el-input>
                </el-form-item>
                <el-form-item label="Password" prop="password">
                    <el-input v-model="passboxForm.password"></el-input>
                </el-form-item>
                <el-form-item label="Notes" prop="notes">
                    <el-input v-model="passboxForm.notes"></el-input>
                </el-form-item>
                <el-form-item class="buzz-form-buttons">
                    <el-button @click="resetForm('passboxForm')">Cancel</el-button>
                    <el-button @click="submitForm('passboxForm')">Save</el-button>
                </el-form-item>
            </el-form>
        </div>
    </div> 
</template>


<script>
  export default {
    data() {
      return {
        labelPosition: 'left',
        passboxForm: {
          url: '',
          name: '',
          category: '',
          username: '',
          password: '',
          notes: '',
        },
        rules: {
          url: [
            { required: true, message: 'Please input URL', trigger: 'change' },
          ],
          name: [
            { required: true, message: 'Please input Name', trigger: 'change' },
          ],
          category: [
            { required: true, message: 'Please select Category', trigger: 'change' },
          ],
          username: [
            { required: true, message: 'Please input Username', trigger: 'change' },
          ],
          password: [
            { required: true, message: 'Please input Password', trigger: 'change' },
          ],
          notes: [
            { required: true, message: 'Please input Notes', trigger: 'change' },
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