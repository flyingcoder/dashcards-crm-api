<template>
    <li>
        <div class="add-button">
            <span> ADD NEW </span>
            <button  @click="$modal.show('add-service')">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                    width="20px" height="20px">
                    <path fill-rule="evenodd"  fill="rgb(255, 255, 255)"
                    d="M18.852,10.789 L11.590,10.789 L11.590,19.039 C11.590,19.444 11.193,19.773 10.703,19.773 C10.212,19.773 9.815,19.444 9.815,19.039 L9.815,10.789 L1.663,10.789 C1.262,10.789 0.937,10.387 0.937,9.892 C0.937,9.395 1.262,8.993 1.663,8.993 L9.815,8.993 L9.815,1.645 C9.815,1.240 10.212,0.911 10.703,0.911 C11.193,0.911 11.590,1.240 11.590,1.645 L11.590,8.993 L18.852,8.993 C19.252,8.993 19.577,9.395 19.577,9.892 C19.577,10.387 19.252,10.789 18.852,10.789 Z"/>
                </svg>
            </button>
        </div>

        <modal name="add-service" transition="nice-modal-fade" @before-open="beforeOpen">
            <section class="content">
                <div class="buzz-modal-header"> {{ title }} </div>
                <div class="buzz-scrollbar" id="buzz-scroll">
                   <el-form :model="servicesForm" ref="servicesForm">
                        <div class="buzz-modal-content services-form">
                            <el-form-item prop="service"
                                :rules="[
                                    { required: true, message: 'Please input Service', trigger: 'blur' },
                                ]"
                                >
                                <el-input v-model="servicesForm.name" placeholder="Untitled Service"></el-input>
                                <el-button @click="addfield" class="additional-field">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <path fill-rule="evenodd"
                                        d="M36.579,19.954 L22.146,19.954 L22.146,36.118 C22.146,36.912 21.356,37.556 20.382,37.556 C19.408,37.556 18.618,36.912 18.618,36.118 L18.618,19.954 L2.414,19.954 C1.619,19.954 0.973,19.167 0.973,18.196 C0.973,17.223 1.619,16.436 2.414,16.436 L18.618,16.436 L18.618,2.038 C18.618,1.244 19.408,0.600 20.382,0.600 C21.356,0.600 22.146,1.244 22.146,2.038 L22.146,16.436 L36.579,16.436 C37.375,16.436 38.020,17.223 38.020,18.196 C38.020,19.167 37.375,19.954 36.579,19.954 Z"/>
                                    </svg>
                                </el-button>
                            </el-form-item>
                            <el-form-item v-for="(field, index) in servicesForm.fields" :key="field.key"
                                :prop="'fields.' + index + '.value'"
                                :rules="{
                                    required: true, message: 'Please input Service', trigger: 'blur'
                                }"
                                >
                                <el-input v-model="field.value" placeholder="Untitled Service"></el-input><el-button @click.prevent="removefield(field)">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="27px" height="39px">
                                        <path fill-rule="evenodd"
                                        d="M24.849,10.106 L1.181,10.106 C0.536,10.106 0.014,9.551 0.014,8.867 L0.014,5.052 C0.014,4.368 0.536,3.813 1.181,3.813 L7.574,3.813 L7.574,1.239 C7.574,0.554 8.096,-0.002 8.741,-0.002 L17.290,-0.002 C17.934,-0.002 18.456,0.554 18.456,1.239 L18.456,3.813 L24.849,3.813 C25.494,3.813 26.016,4.368 26.016,5.052 L26.016,8.867 C26.016,9.551 25.494,10.106 24.849,10.106 ZM16.122,2.478 L9.908,2.478 L9.908,3.813 L16.122,3.813 L16.122,2.478 ZM23.428,36.834 C23.402,37.498 22.887,38.021 22.262,38.021 L3.768,38.021 C3.143,38.021 2.628,37.498 2.602,36.835 L1.634,12.586 L24.396,12.586 L23.428,36.834 ZM9.487,17.628 C9.487,16.943 8.964,16.388 8.320,16.388 C7.675,16.388 7.153,16.943 7.153,17.628 L7.153,32.979 C7.153,33.664 7.675,34.219 8.320,34.219 C8.964,34.219 9.487,33.664 9.487,32.979 L9.487,17.628 ZM14.182,17.628 C14.182,16.943 13.659,16.388 13.015,16.388 C12.370,16.388 11.848,16.943 11.848,17.628 L11.848,32.979 C11.848,33.664 12.370,34.219 13.015,34.219 C13.659,34.219 14.182,33.664 14.182,32.979 L14.182,17.628 ZM18.877,17.628 C18.877,16.943 18.354,16.388 17.710,16.388 C17.065,16.388 16.543,16.943 16.543,17.628 L16.543,32.979 C16.543,33.664 17.065,34.219 17.710,34.219 C18.354,34.219 18.877,33.664 18.877,32.979 L18.877,17.628 Z"/>
                                    </svg>
                                </el-button>
                            </el-form-item>
                            <el-form-item  class="form-buttons">
                                <el-button @click="submit">Save</el-button>
                                <!-- <el-button @click="resetForm('servicesForm')">Reset</el-button> -->
                                <el-button @click="$modal.hide('add-service')">Cancel</el-button>
                            </el-form-item>
                        </div>
                    </el-form>
                </div>
            </section>
        </modal>
    </li>
</template>

<script>
    export default {
    	data: function () {
        	return {   
                title: 'Add New Services',
                action: 'Save',
                id: 0,
                oldName: '',
                isProcessing: false,
                servicesForm: {
                    fields: [{
                    key: 1,
                    value: ''
                    }],
                    name: ''
                },
        		error: {
        			name: [],
                    description: [],
                    due_date: [],
                    content: [],
        		},
                config: {
                    toolbar: [
                      [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript' ]
                    ],
                    
                    height: 500
                }
        	}
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
            },
            removefield(item) {
                var index = this.servicesForm.fields.indexOf(item);
                if (index !== -1) {
                this.servicesForm.fields.splice(index, 1);
                }
            },
            addfield() {
                this.servicesForm.fields.push({
                key: Date.now(),
                value: ''
                });
            },
            onBlur (e) {
                console.log(e)
            },
            onFocus (e) {
                console.log(e)
            },
            beforeOpen (event) {
                if(typeof event.params != 'undefined' && event.params.action == 'update') {
                    this.action = 'Update';
                    this.title = 'Edit Service';
                    this.id = event.params.data;
                    var vm = this;
                    axios.get('api/services/'+this.id)
                        .then( response => {
                            this.servicesForm = response.data;
                        });
                }
            },
            submit(){
                if(this.action == 'Save'){
                    this.save();
                }
                else {
                    this.update();
                }
            },
            save: function () {
                this.isProcessing = true;
                axios.post('/api/services/',this.servicesForm)
                .then( response => {
                    this.isProcessing = false;
                    swal('Success!', 'Service is saved!', 'success');
                })
                .catch ( error => {
                    this.isProcessing = false;
                    if(error.response.status == 422){
                    this.errors = error.response.data.errors;
                    }
                })
            },
            update: function () {
                axios.put('/api/services/'+this.id+'/edit', this.form)
                .then( response => {
                    this.isProcessing = false;
                    swal('Success!', 'Service is updated!', 'success');
                })
                .catch ( error => {
                    if(error.response.status == 422){
                    this.errors = error.response.data.errors;
                    }
                    this.isProcessing = false;        
                })
            }
        },
        mounted() {
            
        }
    }
</script>