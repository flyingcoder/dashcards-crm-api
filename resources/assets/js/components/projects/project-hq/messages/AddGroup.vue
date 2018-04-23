<template>
 <el-form :model="form" :rules="rules" ref="form" label-position="top" v-loading="isProcessing" style="width: 100%">
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
</el-form>
    <!-- <div id="selectMembers" ref="selectMembers" class="defaultMembers" v-bind:class="{ selectMembers: selectMembers }">
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
    </div> -->
</template>


<script>

    export default {
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