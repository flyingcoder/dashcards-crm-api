<template>
    <li>
        <div class="add-button" @click="$modal.show('add-event')">
            <span> ADD NEW </span>
            <button>
               <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                width="20px" height="20px">
                <path fill-rule="evenodd"  fill="rgb(255, 255, 255)"
                d="M18.852,10.789 L11.590,10.789 L11.590,19.039 C11.590,19.444 11.193,19.773 10.703,19.773 C10.212,19.773 9.815,19.444 9.815,19.039 L9.815,10.789 L1.663,10.789 C1.262,10.789 0.937,10.387 0.937,9.892 C0.937,9.395 1.262,8.993 1.663,8.993 L9.815,8.993 L9.815,1.645 C9.815,1.240 10.212,0.911 10.703,0.911 C11.193,0.911 11.590,1.240 11.590,1.645 L11.590,8.993 L18.852,8.993 C19.252,8.993 19.577,9.395 19.577,9.892 C19.577,10.387 19.252,10.789 18.852,10.789 Z"/>
            </svg>
            </button>
        </div>

        <modal name="add-event" transition="nice-modal-fade" @before-open="beforeOpen">
            <section class="content">
                <div class="buzz-modal-header"> {{ title }} </div>
                <div class="buzz-scrollbar" id="buzz-scroll">
                    <el-form :model="form" :rules="rules" ref="form" label-position="top" v-loading="isProcessing" style="width: 100%">
                        <div class="modal-options">
                            <el-form-item  class="option">
                                <div class="option-item"> 
                                    <div class="member-option" v-on:click="selectMembers = !selectMembers" @click="trigger">
                                        <el-button size="small" class="el-dropdown-link" id="member-option"> 
                                            <img src="/img/icons/modal/members.png" alt="" class="button-icon">   
                                            Members 
                                        </el-button>
                                        <el-badge :value="5" :max="99" class="member-counter"></el-badge>
                                    </div>
                                    <div id="selectMembers" ref="selectMembers" class="defaultMembers" v-bind:class="{ selectMembers: selectMembers }">
                                        <el-select class="selectMembers__content"
                                        v-model="form.members" 
                                        multiple 
                                        collapse-tags
                                        filterable 
                                        default-first-option  
                                        placeholder="Choose a Member">
                                            <div class="selectMembers__dropdown" v-bind:class="{ active: selectMembers }">
                                                <el-option class="member-items" v-on:click.self="doThat" v-for="item in members" :key="item.value" :value="item.value">
                                                    <span class="user-image"> <img :src="item.image"/> </span>
                                                    <div class="user-name"> {{ item.label }} </div>
                                                </el-option>
                                            </div>
                                        </el-select>
                                    </div>
                                </div>
                                <div class="option-item">
                                    <div class="date-option">
                                        <img src="img/icons/modal/date.svg" class="button-icon">                                    
                                        <el-date-picker
                                            :clearable="false"
                                            v-model="form.date"
                                            type="date"
                                            placeholder="Date">
                                        </el-date-picker>
                                    </div>
                                </div>
                                <div class="option-item">
                                    <el-dropdown trigger="click" placement="bottom" class="time-option">
                                        <el-button size="small" class="el-dropdown-link"> 
                                            <img src="img/icons/modal/clock.svg" alt="" class="button-icon">   
                                            Time 
                                        </el-button>
                                        <el-dropdown-menu slot="dropdown" class="time-option-dropdown">
                                            <div class="text">
                                                <span class="hour">Hour</span>
                                                <span class="min">Min</span> 
                                            </div>
                                            <el-form-item>
                                                <el-select v-model="form.hours" placeholder="01">
                                                    <el-option
                                                    v-for="item in hours"
                                                    :key="item.value"
                                                    :label="item.label"
                                                    :value="item.value">
                                                    </el-option>
                                                </el-select>
                                                <el-select v-model="form.mins" placeholder="01">
                                                    <el-option
                                                    v-for="item in mins"
                                                    :key="item.value"
                                                    :label="item.label"
                                                    :value="item.value">
                                                    </el-option>
                                                </el-select>
                                                <el-select v-model="form.convention" placeholder="AM">
                                                    <el-option
                                                    v-for="item in convention"
                                                    :key="item.value"
                                                    :label="item.label"
                                                    :value="item.value">
                                                    </el-option>
                                                </el-select>
                                            </el-form-item>
                                            <div class="add-alarm"
                                                v-bind:class="{ showForm: alarmToggle }"
                                                v-on:click="alarmToggle = !alarmToggle"
                                                >
                                                <el-button class="alarm-btn"> Add Alarm </el-button>

                                                <div class="set-alarm">
                                                    <h3> Set Alarm</h3>
                                                    <el-form-item>
                                                        <el-select v-model="form.alarmHour" placeholder="00">
                                                            <el-option
                                                            v-for="item in alarmHour"
                                                            :key="item.value"
                                                            :label="item.label"
                                                            :value="item.value">
                                                            </el-option>
                                                        </el-select>
                                                        <el-select v-model="form.alarmMins" placeholder="01">
                                                            <el-option
                                                            v-for="item in alarmMins"
                                                            :key="item.value"
                                                            :label="item.label"
                                                            :value="item.value">
                                                            </el-option>
                                                        </el-select>
                                                    </el-form-item>
                                                    <el-form-item>
                                                        <el-button class="save-alarm"> Save Alarm </el-button>
                                                    </el-form-item>
                                                </div>
                                            </div>
                                        </el-dropdown-menu>
                                    </el-dropdown>
                                </div>
                            </el-form-item>
                        </div>
                        <div class="buzz-modal-content">
                            <el-form-item prop="name">
                                <el-input type="text" v-model="form.name" placeholder="Untitled Event"></el-input>
                            </el-form-item>
                            <el-form-item>
                                <el-select class="category" v-model="form.category" clearable placeholder="Select Category">
                                    <el-option
                                    v-for="item in category"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value">
                                    </el-option>
                                </el-select>
                                <el-button class="custom-event" @click="$modal.show('custom-event')"> Custom Event </el-button>
                            </el-form-item>
                            <el-form-item label="Add Description">
                                <quill-editor 
                                    class="add-description" 
                                    v-bind:class="{ showEditor: descriptionEditor }" 
                                    v-model="form.description" 
                                    ref="myQuillEditor">
                                </quill-editor>
                                <div class="field-options">
                                    <el-button class="border" v-on:click="descriptionEditor = !descriptionEditor"> 
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                            width="14px" height="18px">
                                            <path fill-rule="evenodd"
                                            d="M0.599,17.193 L0.599,13.304 L13.453,13.304 L13.453,17.193 L0.599,17.193 ZM12.135,14.600 L1.918,14.600 L1.918,15.896 L12.135,15.896 L12.135,14.600 ZM9.149,8.993 L4.948,8.993 L3.875,12.007 L2.478,12.007 L6.550,0.569 L7.588,0.569 L11.601,12.007 L10.207,12.007 L9.149,8.993 ZM7.063,3.049 L5.409,7.697 L8.694,7.697 L7.063,3.049 Z"/>
                                         </svg> 
                                    </el-button>
                                    <el-button> 
                                        <el-upload
                                            class=""
                                            ref="upload"
                                            action=""
                                            :auto-upload="false">
                                            <el-button slot="trigger">
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                    width="14px" height="14px">
                                                    <path fill-rule="evenodd"
                                                    d="M13.592,5.029 C13.369,4.808 13.009,4.809 12.787,5.031 L5.943,11.879 C5.055,12.762 3.613,12.762 2.723,11.877 C1.834,10.992 1.834,9.557 2.723,8.673 L9.770,1.624 C10.324,1.072 11.226,1.072 11.782,1.625 C12.337,2.178 12.337,3.075 11.782,3.628 L5.944,9.437 C5.944,9.437 5.943,9.438 5.943,9.438 C5.720,9.658 5.361,9.658 5.139,9.437 C4.917,9.216 4.917,8.857 5.139,8.636 L7.957,5.831 C8.179,5.610 8.179,5.252 7.957,5.030 C7.735,4.809 7.374,4.809 7.152,5.030 L4.334,7.835 C3.667,8.498 3.667,9.574 4.334,10.238 C5.001,10.902 6.082,10.902 6.749,10.238 C6.750,10.238 6.751,10.237 6.751,10.236 L12.587,4.429 C13.587,3.433 13.587,1.819 12.587,0.823 C11.586,-0.172 9.964,-0.172 8.964,0.823 L1.917,7.872 C0.584,9.199 0.584,11.351 1.918,12.679 C3.253,14.006 5.415,14.006 6.749,12.679 L13.594,5.830 C13.816,5.608 13.815,5.250 13.592,5.029 Z"/>
                                                </svg>
                                            </el-button>
                                            <!-- <el-button style="margin-left: 10px;" size="small" type="success" @click="submitUpload">upload to server</el-button> -->
                                            <!-- <div class="el-upload__tip" slot="tip">jpg/png files with a size less than 500kb</div> -->
                                        </el-upload>
                                    </el-button>
                                    <el-button> 
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                            width="22px" height="16px">
                                            <path fill-rule="evenodd"
                                            d="M20.440,14.013 L4.752,14.013 C4.127,14.013 3.616,13.517 3.616,12.911 L3.616,1.421 C3.616,0.814 4.127,0.319 4.752,0.319 L20.440,0.319 C21.064,0.319 21.575,0.814 21.575,1.421 L21.575,12.911 C21.575,13.517 21.064,14.013 20.440,14.013 ZM20.432,1.544 L4.654,1.544 L4.654,9.341 L8.939,4.599 C9.460,4.023 9.882,4.185 10.366,4.748 L14.762,9.866 L16.978,7.685 C17.446,7.224 17.872,7.226 18.378,7.691 L20.432,9.582 L20.432,1.544 ZM17.178,5.930 C16.301,5.930 15.590,5.240 15.590,4.388 C15.590,3.537 16.301,2.847 17.178,2.847 C18.055,2.847 18.766,3.537 18.766,4.388 C18.766,5.240 18.055,5.930 17.178,5.930 ZM4.752,14.256 L12.954,14.256 L3.155,15.446 C2.535,15.521 1.965,15.090 1.888,14.489 L0.420,3.088 C0.342,2.486 0.786,1.933 1.405,1.858 L3.365,1.620 L3.365,2.855 L1.465,3.085 L2.462,10.822 L3.365,9.529 L3.365,12.911 C3.365,13.281 3.521,13.617 3.772,13.861 C4.023,14.105 4.370,14.256 4.752,14.256 Z"/>
                                        </svg>
                                    </el-button>
                                    <el-button> 
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                            width="20px" height="20px">
                                            <path fill-rule="evenodd"
                                            d="M18.337,14.812 C17.458,16.317 16.265,17.509 14.759,18.388 C13.253,19.266 11.608,19.705 9.824,19.705 C8.041,19.705 6.396,19.266 4.889,18.388 C3.383,17.509 2.191,16.317 1.312,14.812 C0.433,13.307 -0.007,11.663 -0.007,9.881 C-0.007,8.099 0.433,6.455 1.312,4.949 C2.191,3.444 3.383,2.253 4.890,1.374 C6.396,0.496 8.041,0.057 9.824,0.057 C11.608,0.057 13.253,0.496 14.759,1.374 C16.265,2.253 17.458,3.444 18.337,4.949 C19.216,6.455 19.655,8.099 19.655,9.881 C19.655,11.663 19.216,13.307 18.337,14.812 ZM17.364,6.702 C16.929,5.691 16.346,4.822 15.617,4.092 C14.887,3.363 14.017,2.781 13.005,2.346 C11.994,1.911 10.934,1.694 9.824,1.694 C8.715,1.694 7.655,1.912 6.643,2.346 C5.632,2.781 4.762,3.363 4.032,4.092 C3.302,4.822 2.720,5.692 2.285,6.702 C1.849,7.713 1.632,8.772 1.632,9.881 C1.632,10.990 1.849,12.049 2.285,13.060 C2.720,14.070 3.302,14.940 4.032,15.669 C4.762,16.398 5.632,16.980 6.643,17.415 C7.655,17.850 8.715,18.068 9.824,18.068 C10.934,18.068 11.994,17.850 13.005,17.415 C14.017,16.980 14.887,16.398 15.617,15.669 C16.346,14.940 16.929,14.070 17.364,13.060 C17.799,12.049 18.017,10.990 18.017,9.881 C18.017,8.772 17.799,7.713 17.364,6.702 ZM13.101,8.244 C12.649,8.244 12.263,8.084 11.943,7.764 C11.623,7.444 11.462,7.058 11.462,6.606 C11.462,6.154 11.623,5.768 11.943,5.448 C12.263,5.129 12.649,4.969 13.101,4.969 C13.553,4.969 13.940,5.129 14.260,5.448 C14.580,5.768 14.740,6.154 14.740,6.606 C14.740,7.058 14.580,7.444 14.260,7.764 C13.940,8.083 13.553,8.244 13.101,8.244 ZM12.948,11.685 C13.016,11.463 13.152,11.301 13.357,11.198 C13.562,11.096 13.771,11.079 13.984,11.147 C14.198,11.216 14.355,11.350 14.458,11.550 C14.561,11.750 14.578,11.958 14.509,12.170 C14.193,13.203 13.605,14.034 12.743,14.665 C11.881,15.296 10.908,15.612 9.825,15.612 C8.741,15.612 7.768,15.296 6.906,14.665 C6.044,14.034 5.455,13.202 5.139,12.170 C5.071,11.958 5.088,11.750 5.191,11.550 C5.293,11.350 5.455,11.216 5.677,11.147 C5.890,11.079 6.097,11.096 6.298,11.198 C6.498,11.301 6.633,11.463 6.701,11.685 C6.914,12.367 7.309,12.919 7.885,13.341 C8.461,13.763 9.108,13.975 9.824,13.975 C10.541,13.975 11.188,13.763 11.764,13.341 C12.340,12.919 12.735,12.367 12.948,11.685 ZM6.547,8.244 C6.095,8.244 5.709,8.084 5.389,7.764 C5.069,7.444 4.909,7.058 4.909,6.606 C4.909,6.154 5.069,5.768 5.389,5.448 C5.709,5.129 6.095,4.969 6.547,4.969 C6.999,4.969 7.386,5.129 7.706,5.448 C8.026,5.768 8.186,6.154 8.186,6.606 C8.186,7.058 8.026,7.444 7.706,7.764 C7.386,8.083 6.999,8.244 6.547,8.244 Z"/>
                                        </svg>
                                    </el-button>
                                </div>
                            </el-form-item>
                            <el-form-item  class="form-buttons">
                                <el-button @click="submitForm('form')">Save</el-button>
                                <el-button @click="$modal.hide('add-event')">Cancel</el-button>
                            </el-form-item>
                        </div>
                    </el-form>
                </div>
            </section>
        </modal>
        <add-custom></add-custom>
    </li>
</template>

<script>
    import AddCustomEvent from './AddCustomEvent.vue';

    export default {
        components: {
            'add-custom': AddCustomEvent,
        },
    	data: function () {
        	return {    
                selectMembers: false,
                alarmToggle: false,
                descriptionEditor: false,
                title: 'Add New Event',
                action: 'Save',
                id: 0,
                oldName: '',
                isProcessing: false,
                form: {
                    members: '',
                    date: '',
                    time: '',
                    hours: '',
                    mins: '',
                    convention: '',
                    alarmHour: '',
                    alarmMins: '',
                    name: '',
                    category: '',
                    description: '',
                },
                error: {
                    members: [],
                    date: [],
                    time: [],
                    hours: [],
                    mins: [],
                    convention: [],
                    alarmHour: [],
                    alarmMins: [],
        			name: [],
                    category: [],
                    description: [],
                },
                rules: {
                    name: [
                        { required: true, message: 'Please input Event Name', trigger: 'change' },
                    ],
                },
                members: [{
                    value: 'Ross Mosqueda',
                    label: 'Ross Mosqueda',
                    image: 'img/members/alfred.png'
                    }, {
                    value: 'Klent',
                    label: 'Klent',
                    image: 'img/members/alfred.png'
                    }, {
                    value: 'Brian',
                    label: 'Brian',
                    image: 'img/members/alfred.png'
                }],
                hours: [{
                    value: '01',
                    label: '01'
                    }, {
                    value: '02',
                    label: '02'
                    }, {
                    value: '03',
                    label: '03'
                    }, {
                    value: '04',
                    label: '04'
                    }, {
                    value: '05',
                    label: '05'
                    }, {
                    value: '06',
                    label: '06'
                    },  {
                    value: '07',
                    label: '07'
                    }, {
                    value: '08',
                    label: '08'
                    }, {
                    value: '09',
                    label: '09'
                    }, {
                    value: '10',
                    label: '10'
                    }, {
                    value: '11',
                    label: '11'
                    }, {
                    value: '12',
                    label: '12'
                }],
                mins: [{
                    value: '01',
                    label: '01'
                    }, {
                    value: '02',
                    label: '02'
                    }, {
                    value: '03',
                    label: '03'
                    }, {
                    value: '04',
                    label: '04'
                    }, {
                    value: '05',
                    label: '05'
                    },
                ],
                convention: [{
                    value: 'AM',
                    label: 'AM'
                    }, {
                    value: 'PM',
                    label: 'PM'
                    }
                ],
                alarmHour: [{
                    value: '01',
                    label: '01'
                    }, {
                    value: '02',
                    label: '02'
                    }, {
                    value: '03',
                    label: '03'
                    }, {
                    value: '04',
                    label: '04'
                    }, {
                    value: '05',
                    label: '05'
                    }, {
                    value: '06',
                    label: '06'
                    },  {
                    value: '07',
                    label: '07'
                    }, {
                    value: '08',
                    label: '08'
                    }, {
                    value: '09',
                    label: '09'
                    }, {
                    value: '10',
                    label: '10'
                    }, {
                    value: '11',
                    label: '11'
                    }, {
                    value: '12',
                    label: '12'
                }],
                alarmMins: [{
                    value: '01',
                    label: '01'
                    }, {
                    value: '02',
                    label: '02'
                    }, {
                    value: '03',
                    label: '03'
                    }, {
                    value: '04',
                    label: '04'
                    }, {
                    value: '05',
                    label: '05'
                    },
                ],
                category: [{
                    value: 'Option1',
                    label: 'Option1'
                    }, {
                    value: 'Option2',
                    label: 'Option2'
                    }, {
                    value: 'Option3',
                    label: 'Option3'
                    }, {
                    value: 'Option4',
                    label: 'Option4'
                    }, {
                    value: 'Option5',
                    label: 'Option5'
                }],
                config: {
                    toolbar: [
                      [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript' ]
                    ],
                    height: 500
                }
        	}
        },
        methods: {
            renderHeader(h,{column,$index}){
                return h('img', { attrs: { src: '../../../img/icons/menu.svg'}  });
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
                    this.title = 'Edit Event';
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
            },
            trigger () {
                this.$refs.selectMembers.elem.click()
            }
        },
        mounted() {
            
        }
    }
</script>