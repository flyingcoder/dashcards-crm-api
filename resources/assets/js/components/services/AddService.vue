<template>
    <li class="add-button">
        <span> ADD NEW </span>
        <button  @click="$modal.show('add-service')">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                width="20px" height="20px">
                <path fill-rule="evenodd"  fill="rgb(255, 255, 255)"
                d="M18.852,10.789 L11.590,10.789 L11.590,19.039 C11.590,19.444 11.193,19.773 10.703,19.773 C10.212,19.773 9.815,19.444 9.815,19.039 L9.815,10.789 L1.663,10.789 C1.262,10.789 0.937,10.387 0.937,9.892 C0.937,9.395 1.262,8.993 1.663,8.993 L9.815,8.993 L9.815,1.645 C9.815,1.240 10.212,0.911 10.703,0.911 C11.193,0.911 11.590,1.240 11.590,1.645 L11.590,8.993 L18.852,8.993 C19.252,8.993 19.577,9.395 19.577,9.892 C19.577,10.387 19.252,10.789 18.852,10.789 Z"/>
            </svg>
        </button>

        <modal name="add-service" transition="nice-modal-fade" @before-open="beforeOpen">
             <section class="content">
                    <div class="buzz-modal-header"> {{ title }} </div>
                    <div class="buzz-scrollbar" id="buzz-scroll">
                      <el-form ref="form" :model="form" label-position="top" v-loading="isProcessing" style="width: 100%">                    
                          <div class="buzz-modal-content">
                              <el-form-item>
                                  <el-input type="text" v-model="form.name" placeholder="Service Name"></el-input>
                              </el-form-item>
                              <div class="form-buttons">
                                  <el-button type="primary" class="buzz-button border" @click="submit"> {{ action }} </el-button>
                                  <el-button type="primary" class="buzz-button border" @click="$modal.hide('add-service')"> Cancel </el-button>
                              </div>
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
                title: 'Add New Service',
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
                config: {
                    toolbar: [
                      [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript' ]
                    ],
                    
                    height: 500
                }
        	}
        },

        methods: {
            beforeOpen (event) {
                if(typeof event.params != 'undefined' && event.params.action == 'update') {
                    this.action = 'Update';
                    this.header = 'Edit Service';
                    this.id = event.params.data.id;
                    var vm = this;
                    axios.get('api/service/'+this.id)
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
                axios.post('/api/services',this.form)
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