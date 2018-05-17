<template>
    <li>
        <div class="add-button" @click="$modal.show('add-service')">
            <span> ADD NEW </span>
            <button>
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
                   <el-form :model="form" loading ref="form" @submit.prevent.native="handleInputConfirm">
                        <div class="buzz-modal-content services-form">
                            <el-form-item v-loading="isProcessing" :error="error.name[0]">
                                <el-input v-model="service.name" placeholder="Untitled Service"></el-input>
                            </el-form-item>
                            <ul>
                                <li v-for="(s, index) in form" :key="s.key">
                                    <span> {{ s.name  }} </span>
                                    <el-button @click="removeService(index)">
                                        <svg viewBox="0 0 250 250">
                                            <path class="delete" d="M61 83l129 0c6,0 11,5 10,10l-3 146c-1,6 -5,11 -11,11l-121 0c-6,0 -11,-5 -11,-11l-4 -146c0,-5 5,-10 11,-10zm37 -83l54 0c5,0 9,2 12,5l0 0c3,3 4,7 4,11l0 10 33 0c6,0 11,4 11,10l0 23c0,6 -5,11 -11,11l-152 0c-6,0 -11,-5 -11,-11l0 -23c0,-6 5,-10 11,-10l33 0 0 -10c0,-4 2,-8 5,-11 3,-3 7,-5 11,-5zm1 26l53 0 0 -9 -53 0 0 9zm-5 83l0 0c5,0 9,4 9,10l0 95c0,5 -4,9 -9,9l0 0c-6,0 -10,-4 -10,-9l0 -95c0,-6 4,-10 10,-10zm64 0l0 0c5,0 9,4 9,10l0 95c0,5 -4,9 -9,9l0 0c-5,0 -10,-4 -10,-9l0 -95c0,-6 5,-10 10,-10zm-32 0l0 0c5,0 9,4 9,10l0 95c0,5 -4,9 -9,9l0 0c-5,0 -10,-4 -10,-9l0 -95c0,-6 5,-10 10,-10z"/>
                                        </svg>
                                    </el-button>
                                </li>
                            </ul>
                            <el-form-item  class="form-buttons">
                                <el-button :disabled="form.length == 0" @click="submit">Save</el-button>
                                <!-- <el-button @click="resetForm('form')">Reset</el-button> -->
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
                service: {
                    name: ''
                },
                isProcessing: false,
                form: [],
                error: {
                    name: []
                },
                
        	}
        },
        methods: {
            handleInputConfirm(){
                this.isProcessing = true
                axios.post('/api/services/validate', this.service)
                .then(response => {
                    this.isProcessing = false;
                    this.form.push({ key: this.form.length + 1, name: this.service.name });
                    this.service.name = "";
                })
                .catch( error => {
                    this.error = error.response.data.errors;
                    this.isProcessing = false;                  
                })
                
            },
            removeService(index){
                this.form.splice(index, 1);
            },
            beforeOpen (event) {
                error = { name : [] }
                form = [];
                if(typeof event.params != 'undefined' && event.params.action == 'update') {
                    this.action = 'Update';
                    this.title = 'Edit Service';
                    this.id = event.params.data;
                    var vm = this;
                    axios.get('api/services/'+this.id)
                        .then( response => {
                            this.form = response.data;
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
                axios.post('/api/services/',this.form)
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