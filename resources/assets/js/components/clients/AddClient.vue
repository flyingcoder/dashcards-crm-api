<template>
    <li>
        <div class="add-button">
            <span> ADD NEW </span>
            <button  @click="$modal.show('add-client')">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                    width="20px" height="20px">
                    <path fill-rule="evenodd"  fill="rgb(255, 255, 255)"
                    d="M18.852,10.789 L11.590,10.789 L11.590,19.039 C11.590,19.444 11.193,19.773 10.703,19.773 C10.212,19.773 9.815,19.444 9.815,19.039 L9.815,10.789 L1.663,10.789 C1.262,10.789 0.937,10.387 0.937,9.892 C0.937,9.395 1.262,8.993 1.663,8.993 L9.815,8.993 L9.815,1.645 C9.815,1.240 10.212,0.911 10.703,0.911 C11.193,0.911 11.590,1.240 11.590,1.645 L11.590,8.993 L18.852,8.993 C19.252,8.993 19.577,9.395 19.577,9.892 C19.577,10.387 19.252,10.789 18.852,10.789 Z"/>
                </svg>
            </button>
        </div>

        <modal name="add-client" transition="nice-modal-fade" @before-open="beforeOpen">
            <section class="content">
                <div class="buzz-modal-header"> {{ title }} </div>
                <div class="buzz-scrollbar" id="buzz-scroll">
                <el-form ref="form" :model="form" label-position="top" v-loading="isProcessing" style="width: 100%">                    
                    <div class="buzz-modal-content">
                            <div class="form-content row">
                                <div class="form-group col-md-12"> 
                                    <el-form-item>
                                        <el-input type="text" v-model="form.name" placeholder="Client Name"></el-input>
                                    </el-form-item>
                                </div>
                                <div class="form-group col-md-12"> 
                                    <el-form-item>
                                        <ckeditor 
                                        v-model="form.description" 
                                        :config="config"
                                        @blur="onBlur($event)" 
                                        @focus="onFocus($event)">
                                    </ckeditor>
                                    </el-form-item>
                                </div>
                                <div class="form-group col-md-12">
                                    <ckeditor 
                                        v-model="form.content" 
                                        :config="config"
                                        @blur="onBlur($event)" 
                                        @focus="onFocus($event)">
                                    </ckeditor>
                                    <!-- el-form-item label="Add Comment :">
                                        <textarea rows="4" id="editor"></textarea>
                                    </el-form-item -->
                                </div>
                                <!-- div class="form-group col-md-12 ">
                                    <div class="buzz-modal-footer">
                                        <el-button type="primary" class="send border"> Send </el-button>
                                        <el-button type="primary" class="border"> 
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                width="14px" height="18px">
                                                <path fill-rule="evenodd"
                                                d="M0.599,17.436 L0.599,13.545 L13.453,13.545 L13.453,17.436 L0.599,17.436 ZM12.135,14.842 L1.918,14.842 L1.918,16.139 L12.135,16.139 L12.135,14.842 ZM9.149,9.233 L4.948,9.233 L3.875,12.248 L2.478,12.248 L6.550,0.804 L7.588,0.804 L11.601,12.248 L10.207,12.248 L9.149,9.233 ZM7.063,3.286 L5.409,7.936 L8.694,7.936 L7.063,3.286 Z"/>
                                            </svg>
                                        </el-button>
                                        <el-button type="primary"> 
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                width="14px" height="14px">
                                                <path fill-rule="evenodd"
                                                d="M13.592,5.267 C13.369,5.047 13.009,5.048 12.787,5.269 L5.943,12.120 C5.055,13.004 3.613,13.004 2.723,12.119 C1.834,11.234 1.834,9.798 2.723,8.913 L9.770,1.860 C10.324,1.308 11.226,1.308 11.782,1.861 C12.337,2.415 12.337,3.312 11.782,3.866 L5.944,9.677 C5.944,9.678 5.943,9.678 5.943,9.678 C5.720,9.899 5.361,9.898 5.139,9.677 C4.917,9.456 4.917,9.097 5.139,8.876 L7.957,6.070 C8.179,5.849 8.179,5.490 7.957,5.268 C7.735,5.047 7.374,5.047 7.152,5.268 L4.334,8.074 C3.667,8.738 3.667,9.815 4.334,10.479 C5.001,11.143 6.082,11.143 6.749,10.479 C6.750,10.479 6.751,10.478 6.751,10.477 L12.587,4.667 C13.587,3.671 13.587,2.056 12.587,1.060 C11.586,0.064 9.964,0.064 8.964,1.060 L1.917,8.112 C0.584,9.439 0.584,11.593 1.918,12.921 C3.253,14.249 5.415,14.249 6.749,12.921 L13.594,6.069 C13.816,5.847 13.815,5.488 13.592,5.267 Z"/>
                                            </svg>
                                        </el-button>
                                        <el-button type="primary"> 
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                width="22px" height="16px">
                                                <path fill-rule="evenodd"
                                                d="M20.440,14.255 L4.752,14.255 C4.127,14.255 3.616,13.759 3.616,13.152 L3.616,1.657 C3.616,1.050 4.127,0.554 4.752,0.554 L20.440,0.554 C21.064,0.554 21.575,1.050 21.575,1.657 L21.575,13.152 C21.575,13.759 21.064,14.255 20.440,14.255 ZM20.432,1.780 L4.654,1.780 L4.654,9.581 L8.939,4.837 C9.460,4.260 9.882,4.423 10.366,4.986 L14.762,10.107 L16.978,7.924 C17.446,7.463 17.872,7.465 18.378,7.930 L20.432,9.822 L20.432,1.780 ZM17.178,6.169 C16.301,6.169 15.590,5.478 15.590,4.626 C15.590,3.774 16.301,3.083 17.178,3.083 C18.055,3.083 18.766,3.774 18.766,4.626 C18.766,5.478 18.055,6.169 17.178,6.169 ZM4.752,14.499 L12.954,14.499 L3.155,15.689 C2.535,15.764 1.965,15.333 1.888,14.732 L0.420,3.325 C0.342,2.723 0.786,2.169 1.405,2.094 L3.365,1.856 L3.365,3.092 L1.465,3.322 L2.462,11.063 L3.365,9.769 L3.365,13.152 C3.365,13.523 3.521,13.860 3.772,14.103 C4.023,14.347 4.370,14.499 4.752,14.499 Z"/>
                                            </svg>
                                        </el-button>
                                        <el-button type="primary"> 
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                width="20px" height="20px">
                                                <path fill-rule="evenodd"
                                                d="M18.337,15.054 C17.458,16.560 16.265,17.752 14.759,18.631 C13.253,19.510 11.608,19.949 9.824,19.949 C8.041,19.949 6.396,19.510 4.889,18.631 C3.383,17.752 2.191,16.560 1.312,15.054 C0.433,13.548 -0.007,11.903 -0.007,10.120 C-0.007,8.337 0.433,6.692 1.312,5.186 C2.191,3.680 3.383,2.488 4.890,1.609 C6.396,0.730 8.041,0.291 9.824,0.291 C11.608,0.291 13.253,0.730 14.759,1.609 C16.265,2.488 17.458,3.680 18.337,5.186 C19.216,6.692 19.655,8.337 19.655,10.120 C19.655,11.903 19.216,13.548 18.337,15.054 ZM17.364,6.940 C16.929,5.929 16.346,5.058 15.617,4.329 C14.887,3.599 14.017,3.017 13.005,2.582 C11.994,2.147 10.934,1.929 9.824,1.929 C8.715,1.929 7.655,2.147 6.643,2.582 C5.632,3.017 4.762,3.599 4.032,4.329 C3.302,5.058 2.720,5.929 2.285,6.940 C1.849,7.951 1.632,9.011 1.632,10.120 C1.632,11.229 1.849,12.290 2.285,13.301 C2.720,14.312 3.302,15.182 4.032,15.912 C4.762,16.641 5.632,17.223 6.643,17.658 C7.655,18.093 8.715,18.311 9.824,18.311 C10.934,18.311 11.994,18.094 13.005,17.658 C14.017,17.223 14.887,16.641 15.617,15.912 C16.346,15.182 16.929,14.312 17.364,13.301 C17.799,12.290 18.017,11.229 18.017,10.120 C18.017,9.011 17.799,7.951 17.364,6.940 ZM13.101,8.482 C12.649,8.482 12.263,8.322 11.943,8.002 C11.623,7.682 11.462,7.296 11.462,6.844 C11.462,6.391 11.623,6.005 11.943,5.686 C12.263,5.365 12.649,5.205 13.101,5.205 C13.553,5.205 13.940,5.365 14.260,5.686 C14.580,6.005 14.740,6.391 14.740,6.844 C14.740,7.296 14.580,7.682 14.260,8.002 C13.940,8.322 13.553,8.482 13.101,8.482 ZM12.948,11.925 C13.016,11.703 13.152,11.541 13.357,11.438 C13.562,11.336 13.771,11.319 13.984,11.387 C14.198,11.455 14.355,11.590 14.458,11.790 C14.561,11.990 14.578,12.198 14.509,12.411 C14.193,13.444 13.605,14.275 12.743,14.907 C11.881,15.538 10.908,15.854 9.825,15.854 C8.741,15.854 7.768,15.538 6.906,14.907 C6.044,14.275 5.455,13.443 5.139,12.411 C5.071,12.198 5.088,11.990 5.191,11.790 C5.293,11.590 5.455,11.455 5.677,11.387 C5.890,11.319 6.097,11.336 6.298,11.438 C6.498,11.541 6.633,11.703 6.701,11.925 C6.914,12.608 7.309,13.160 7.885,13.582 C8.461,14.004 9.108,14.216 9.824,14.216 C10.541,14.216 11.188,14.004 11.764,13.582 C12.340,13.160 12.735,12.608 12.948,11.925 ZM6.547,8.482 C6.095,8.482 5.709,8.322 5.389,8.002 C5.069,7.682 4.909,7.296 4.909,6.844 C4.909,6.391 5.069,6.005 5.389,5.686 C5.709,5.365 6.095,5.205 6.547,5.205 C6.999,5.205 7.386,5.365 7.706,5.686 C8.026,6.005 8.186,6.391 8.186,6.844 C8.186,7.296 8.026,7.682 7.706,8.002 C7.386,8.322 6.999,8.482 6.547,8.482 Z"/>
                                            </svg>
                                        </el-button>
                                        <el-button type="primary" class="pull-right"> 
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                width="15px" height="21px">
                                                <path fill-rule="evenodd"
                                                d="M14.315,5.583 L0.664,5.583 C0.292,5.583 -0.009,5.276 -0.009,4.897 L-0.009,2.789 C-0.009,2.410 0.292,2.104 0.664,2.104 L4.351,2.104 L4.351,0.681 C4.351,0.302 4.652,-0.005 5.024,-0.005 L9.955,-0.005 C10.326,-0.005 10.628,0.302 10.628,0.681 L10.628,2.104 L14.315,2.104 C14.687,2.104 14.988,2.410 14.988,2.789 L14.988,4.897 C14.988,5.276 14.687,5.583 14.315,5.583 ZM9.281,1.366 L5.697,1.366 L5.697,2.104 L9.281,2.104 L9.281,1.366 ZM13.495,20.357 C13.480,20.724 13.183,21.013 12.823,21.013 L2.156,21.013 C1.795,21.013 1.499,20.724 1.484,20.357 L0.925,6.953 L14.053,6.953 L13.495,20.357 ZM5.454,9.740 C5.454,9.362 5.153,9.055 4.781,9.055 C4.410,9.055 4.108,9.362 4.108,9.740 L4.108,18.226 C4.108,18.605 4.410,18.912 4.781,18.912 C5.153,18.912 5.454,18.605 5.454,18.226 L5.454,9.740 ZM8.162,9.740 C8.162,9.362 7.861,9.055 7.489,9.055 C7.118,9.055 6.816,9.362 6.816,9.740 L6.816,18.226 C6.816,18.605 7.118,18.912 7.489,18.912 C7.861,18.912 8.162,18.605 8.162,18.226 L8.162,9.740 ZM10.870,9.740 C10.870,9.362 10.569,9.055 10.197,9.055 C9.825,9.055 9.524,9.362 9.524,9.740 L9.524,18.226 C9.524,18.605 9.825,18.912 10.197,18.912 C10.569,18.912 10.870,18.605 10.870,18.226 L10.870,9.740 Z"/>
                                            </svg>
                                        </el-button>
                                    </div>
                                </div -->
                                <div class="form-buttons">
                                        <el-button type="primary" class="buzz-button border" @click="submit"> {{ action }} </el-button>
                                        <el-button type="primary" class="buzz-button border" @click="$modal.hide('add-client')"> Cancel </el-button>
                                </div>
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
                title: 'Add New Client',
                action: 'Save',
                id: 0,
                oldName: '',
                isProcessing: false,
                form: {
                    name: '',
                    description: '',
                    content: '',
                },
        		error: {
        			name: [],
                    description: [],
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
            onBlur (e) {
                console.log(e)
            },
            onFocus (e) {
                console.log(e)
            },
            beforeOpen (event) {
                if(typeof event.params != 'undefined' && event.params.action == 'update') {
                    this.action = 'Update';
                    this.header = 'Edit Client';
                    this.id = event.params.data.id;
                    var vm = this;
                    axios.get('api/clients/'+this.id)
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
                axios.post('/api/clients/new',this.form)
                .then( response => {
                    this.isProcessing = false;
                    swal('Success!', 'Client is saved!', 'success');
                })
                .catch ( error => {
                    this.isProcessing = false;
                    if(error.response.status == 422){
                    this.errors = error.response.data.errors;
                    }
                })
            },
            update: function () {
                axios.put('/api/clients/'+this.id+'/edit', this.form)
                .then( response => {
                    this.isProcessing = false;
                    swal('Success!', 'Client is updated!', 'success');
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