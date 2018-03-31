<template>
    <div>
        <div class="buzz-modal-option">
            <el-form-item  class="option">
                <el-button class="option-item"> <img src="/img/icons/modal/members.png" alt="">  Members </el-button>
                <el-button class="option-item">
                    <div class="date-project">
                            <img src="/img/icons/modal/date.svg" alt="" class="button-icon">                                    
                        <el-date-picker
                            :clearable="false"
                            v-model="taskForm.due_date"
                            type="date"
                            placeholder="Due Date">
                        </el-date-picker>
                    </div>
                </el-button>
                <el-button class="option-item"> <img src="/img/icons/modal/attachment.svg" alt=""> Attachment </el-button>
            </el-form-item>
        </div>
        <div class="buzz-modal-content">
            <el-form-item label="" prop="name" :error="errors.name[0]">
                <el-input v-model="taskForm.name" placeholder="Untitled Task"></el-input>
            </el-form-item>
            <el-form-item>
                <el-select v-model="taskForm.milestone" filterable placeholder="Select Milestone" style="width: 100%">
                    <el-option
                        v-for="milestone in milestones"
                        :key="milestone.id"
                        :label="milestone.title"
                        :value="milestone.id">
                    </el-option>
                </el-select>
                <!-- <el-select v-model="taskForm.milestone" clearable placeholder="Select Milestone">
                    <el-option
                    v-for="item in milestone_options"
                    :key="item.value"
                    :label="item.label"
                    :value="item.value">
                    </el-option>
                </el-select> -->
            </el-form-item>
            <el-form-item label="">
                <quill-editor v-model="taskForm.description" ref="myQuillEditor">
                </quill-editor>
            </el-form-item>
            <el-form-item label="">
                <quill-editor v-model="taskForm.comment" ref="myQuillEditor">
                </quill-editor>
            </el-form-item>
            <el-form-item  class="form-buttons">
                <el-button @click="submitForm('taskForm')">Save</el-button>
                <el-button @click="$modal.hide('add-task')">Cancel</el-button>
            </el-form-item>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['projectId'],
    	data() {
        	return {   
                labelPosition: 'left', 
                title: 'Add New Task',
                action: 'Save',
                id: 0,
                oldName: '',
                isProcessing: false,
                taskForm: {
                    name: '',
                    milestone: '',
                    description: '',
                    comment: '',
                },
                milestones: [],
                milestone_options: [{
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
        		errors: {
        			name: [],
        			milestone: [],
        			description: [],
        			comment: [],
        		},
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
                    this.header = 'Edit Task';
                    this.id = event.params.data.id;
                    var vm = this;
                    axios.get('api/tasks/'+this.id)
                        .then( response => {
                            this.taskForm = response.data;
                        });
                }
            },
            submit(){
                this.$refs[taskForm].validate((valid) => {
                    if (valid) {
                        alert('submit!');
                    } else {
                        console.log('error submit!!');
                        return false;
                    }
                });
            },
            save: function () {
                this.isProcessing = true;
                axios.post('/api/tasks/new',this.taskForm)
                .then( response => {
                    this.isProcessing = false;
                    swal('Success!', 'Task is saved!', 'success');
                })
                .catch ( error => {
                    this.isProcessing = false;
                    if(error.response.status == 422){
                    this.errors = error.response.data.errors;
                    }
                })
            },
            update: function () {
                axios.put('/api/tasks/'+this.id+'/edit', this.taskForm)
                .then( response => {
                    this.isProcessing = false;
                    swal('Success!', 'Task is updated!', 'success');
                })
                .catch ( error => {
                    if(error.response.status == 422){
                    this.errors = error.response.data.errors;
                    }
                    this.isProcessing = false;        
                })
            },
            getMilestone(){
                axios.get('/api/milestones/select/'+  this.$parent.projectId)
                .then( response => {
                    this.milestones = response.data;
                })
            }
        },
        mounted() {
            
        }
    }
</script>