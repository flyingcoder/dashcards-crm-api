<template>
    <modal name="custom-event" transition="nice-modal-fade" @before-open="beforeOpen">
        <section class="content">
            <div class="buzz-modal-header"> Customize Event </div>
            <div class="buzz-scrollbar" id="buzz-scroll">
                <el-form :model="form" :rules="rules" ref="form" label-position="top" v-loading="isProcessing" style="width: 100%">
                    <div class="buzz-modal-content">
                        <img src="/img/temporary/construction.jpg" alt="icon" width="560px">
                        <h3 class="text-center"> Coming Soon!!</h3>
                        <el-form-item  class="form-buttons">
                            <el-button @click="submitForm('form')">Save</el-button>
                            <el-button @click="$modal.hide('custom-event')">Cancel</el-button>
                        </el-form-item>
                    </div>
                </el-form>
            </div>
        </section>
    </modal>
</template>

<script>
    export default {
    	data: function () {
        	return {    
                title: 'Add New Event',
                action: 'Save',
                id: 0,
                oldName: '',
                isProcessing: false,
                form: {
                    name: '',
                },
                error: {
                    name: [],
                },
                rules: {
                    name: [
                        { required: true, message: 'Please input Event Name', trigger: 'change' },
                    ],
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
            onBlur (e) {
                console.log(e)
            },
            onFocus (e) {
                console.log(e)
            },
            beforeOpen (event) {
                if(typeof event.params != 'undefined' && event.params.action == 'update') {
                    this.action = 'Update';
                    this.header = 'Edit Event';
                    this.id = event.params.data.id;
                    var vm = this;
                    axios.get('api/events/'+this.id)
                        .then( response => {
                            this.form = response.data;
                        });
                }
            },
            submit(){
                this.$refs[form].validate((valid) => {
                    if (valid) {
                        alert('submit!');
                        if(this.action == 'Save'){
                            this.save();
                        }
                        else {
                            this.update();
                        }
                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                });
            },
            save: function () {
                this.isProcessing = true;
                axios.post('/api/events/new',this.form)
                .then( response => {
                    this.isProcessing = false;
                    swal('Success!', 'Event is saved!', 'success');
                })
                .catch ( error => {
                    this.isProcessing = false;
                    if(error.response.status == 422){
                    this.errors = error.response.data.errors;
                    }
                })
            },
            update: function () {
                axios.put('/api/events/'+this.id+'/edit', this.form)
                .then( response => {
                    this.isProcessing = false;
                    swal('Success!', 'Event is updated!', 'success');
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