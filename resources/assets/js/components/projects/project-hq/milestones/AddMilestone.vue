<template>
    <div>
        <div class="add-button" @click="$modal.show('add-milestone')">
            <span> ADD NEW </span>
            <button>
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                    width="22px" height="21px">
                    <path fill-rule="evenodd"  fill="rgb(255, 255, 255)"
                    d="M20.486,10.861 L12.470,10.861 L12.470,19.861 C12.470,20.302 12.031,20.661 11.490,20.661 C10.949,20.661 10.510,20.302 10.510,19.861 L10.510,10.861 L1.511,10.861 C1.069,10.861 0.710,10.423 0.710,9.882 C0.710,9.340 1.069,8.902 1.511,8.902 L10.510,8.902 L10.510,0.885 C10.510,0.443 10.949,0.085 11.490,0.085 C12.031,0.085 12.470,0.443 12.470,0.885 L12.470,8.902 L20.486,8.902 C20.928,8.902 21.286,9.340 21.286,9.882 C21.286,10.423 20.928,10.861 20.486,10.861 Z"/>
                </svg>
            </button>
        </div>
    
        <modal name="add-milestone" transition="nice-modal-fade" @before-open="beforeOpen">
            <section class="content">
                <div class="buzz-modal-header"> {{ title }} </div>
                <div class="buzz-scrollbar milestone-form" id="buzz-scroll">
                    <el-form :model="milestoneForm" ref="milestoneForm" v-loading="isProcessing">
                        <div class="buzz-modal-content">
                            <el-form-item label="" prop="title" :error="errors.title[0]">
                                <el-input v-model="milestoneForm.title" placeholder="Title"></el-input>
                            </el-form-item>
                            <el-form-item label="" prop="percentage" :error="errors.percentage[0]">
                                <el-input v-model="milestoneForm.percentage" placeholder="Percentage" ></el-input>
                            </el-form-item>
                            <el-form-item label="" prop="started_at" :error="errors.started_at[0]">
                               <el-date-picker
                                    style="width: 100%"
                                    v-model="milestoneForm.started_at"
                                    type="date"
                                    value-format="yyyy-MM-dd"
                                    placeholder="Select Start Date">
                                </el-date-picker>
                            </el-form-item>
                            <el-form-item label="" prop="end_at" :error="errors.end_at[0]">
                                <el-date-picker
                                    style="width: 100%"
                                    v-model="milestoneForm.end_at"
                                    type="date"
                                    value-format="yyyy-MM-dd"
                                    placeholder="Select End Date">
                                </el-date-picker>
                            </el-form-item>
                            <el-form-item label="" prop="days" :error="errors.days[0]">
                                <el-input v-model="milestoneForm.days" placeholder="Days"></el-input>
                                <span> Note! If Start and End dates are provided, Milestone duration (days) will be ignored </span>
                            </el-form-item>
                            <el-form-item  class="form-buttons">
                                <el-button @click="submitForm('milestoneForm')">Save</el-button>
                                <el-button @click="$modal.hide('add-milestone')">Cancel</el-button>
                            </el-form-item>
                        </div>
                    </el-form>
                </div>
            </section>
        </modal>
    </div>
</template>


<script>
    export default {
        props: ['projectId'],
    	data() {
        	return {   
                labelPosition: 'left', 
                title: 'Add New Milestone',
                action: 'Save',
                id: 0,
                oldName: '',
                isProcessing: false,
                milestoneForm: {
                    title: '',
                    percentage: '',
                    started_at: '',
                    end_at: '',
                    days: '',
                },
        		errors: {
        			title: [],
        			percentage: [],
        			started_at: [],
        			end_at: [],
        			days: [],
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
                    this.header = 'Edit Milestone';
                    this.id = event.params.data.id;
                    var vm = this;
                    axios.get('api/milestones/'+this.id)
                        .then( response => {
                            this.milestoneForm = response.data;
                        });
                }
            },
            submit(){
                this.$refs[milestoneForm].validate((valid) => {
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
                axios.post('/api/milestones/new',this.milestoneForm)
                .then( response => {
                    this.isProcessing = false;
                    swal('Success!', 'Milestone is saved!', 'success');
                })
                .catch ( error => {
                    this.isProcessing = false;
                    if(error.response.status == 422){
                    this.errors = error.response.data.errors;
                    }
                })
            },
            update: function () {
                axios.put('/api/milestones/'+this.id+'/edit', this.milestoneForm)
                .then( response => {
                    this.isProcessing = false;
                    swal('Success!', 'Milestone is updated!', 'success');
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