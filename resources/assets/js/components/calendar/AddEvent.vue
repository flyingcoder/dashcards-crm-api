<template>
    <li>
        <div class="add-button">
            <span> ADD NEW </span>
            <button  @click="$modal.show('add-event')">
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
                                     <el-dropdown trigger="click" placement="bottom" class="member-option">
                                        <el-button size="small" class="el-dropdown-link"> 
                                            <img src="/img/icons/modal/members.png" alt="" class="button-icon">   
                                            Members 
                                        </el-button>
                                        <el-dropdown-menu slot="dropdown" class="member-option-dropdown">
                                           <el-select
                                                v-model="form.members"
                                                multiple
                                                filterable
                                                allow-create
                                                default-first-option
                                                placeholder="Choose Members">
                                                <el-option
                                                v-for="item in members"
                                                :key="item.value"
                                                :label="item.label"
                                                :value="item.value">
                                                </el-option>
                                            </el-select>
                                        </el-dropdown-menu>
                                    </el-dropdown>
                                </div>
                                <div class="option-item">
                                    <div class="date-option">
                                        <img src="img/icons/modal/date.svg" alt="" class="button-icon">                                    
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
                                <el-select v-model="form.category" clearable placeholder="Select Category">
                                    <el-option
                                    v-for="item in category"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value">
                                    </el-option>
                                </el-select>
                                <button  @click="$modal.show('custom-event')">
                                    Custom Event
                                </button>
                            </el-form-item>
                            <el-form-item label="Add Description">
                                <quill-editor v-model="form.description" ref="myQuillEditor">
                                </quill-editor>
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
    </li>
</template>

<script>
    export default {
    	data: function () {
         
        	return {    
                alarmToggle: false,
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
                    }, {
                    value: 'Klent',
                    label: 'Klent'
                    }, {
                    value: 'Brian',
                    label: 'Brian'
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