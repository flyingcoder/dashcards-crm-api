<template>
    <div>
        <div class="add-button" @click="$modal.show('add-task')">
            <span> ADD NEW </span>
            <button>
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                    width="22px" height="21px">
                    <path fill-rule="evenodd"  fill="rgb(255, 255, 255)"
                    d="M20.486,10.861 L12.470,10.861 L12.470,19.861 C12.470,20.302 12.031,20.661 11.490,20.661 C10.949,20.661 10.510,20.302 10.510,19.861 L10.510,10.861 L1.511,10.861 C1.069,10.861 0.710,10.423 0.710,9.882 C0.710,9.340 1.069,8.902 1.511,8.902 L10.510,8.902 L10.510,0.885 C10.510,0.443 10.949,0.085 11.490,0.085 C12.031,0.085 12.470,0.443 12.470,0.885 L12.470,8.902 L20.486,8.902 C20.928,8.902 21.286,9.340 21.286,9.882 C21.286,10.423 20.928,10.861 20.486,10.861 Z"/>
                </svg>
            </button>
        </div>
    
        <modal name="add-task" transition="nice-modal-fade" @before-open="beforeOpen">
            <section class="content add-task">
                <v-layout row wrap>
                    <div class="buzz-modal-header"> {{ title }} </div>
                    <div class="buzz-scrollbar task-form" id="buzz-scroll">
                        <el-form :model="form" ref="form" v-loading="isProcessing">
                            <el-tabs type="card">
                                <el-tab-pane>
                                    <div slot="label" class="text-option task-option">
                                        <div class="left">
                                            <img src="/img/icons/task/text.png" alt="icon">
                                        </div>
                                        <div class="right">
                                            Text
                                        </div>
                                    </div>
                                    <text-task :projectId="projectId"></text-task>
                                </el-tab-pane>
                                <el-tab-pane label="Video">
                                    <div slot="label" class="video-option task-option">
                                        <div class="left">
                                            <img src="/img/icons/task/video.svg" alt="icon">
                                        </div>
                                        <div class="right">
                                            Video
                                        </div>
                                    </div>
                                    <video-task></video-task>
                                </el-tab-pane>
                            </el-tabs>
                        </el-form>
                    </div>
                </v-layout>
            </section>
        </modal>
    </div>
</template>

<script>
    import TextTask from './TextTask.vue';
    import VideoTask from './VideoTask.vue';
    import UnderConstruction from '../../../UnderConstruction.vue';

    export default {
        props: ['projectId'],
        components: {
          'text-task': TextTask,
          'video-task': VideoTask,
          'under-construction': UnderConstruction,
         },
    	data() {
        	return { 
                descriptionEditor: false,
                commentEditor: false,
                labelPosition: 'left', 
                title: 'Add New Task',
                action: 'Save',
                id: 0,
                oldName: '',
                task: '',
                isProcessing: false,
                form: {
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
            construction: function () {
                swal({
                    title: 'Coming Soon!',
                    imageUrl: '/img/temporary/construction.jpg',
                    imageWidth: 400,
                    imageHeight: 200,
                    imageAlt: 'Custom image',
                    animation: false
                })  
            },
            beforeOpen (event) {
                if(typeof event.params != 'undefined' && event.params.action == 'update') {
                    this.action = 'Update';
                    this.header = 'Edit Task';
                    var vm = this;
                    axios.get('api/tasks/'+event.params.data.id)
                        .then( response => {
                            this.task = response.data;
                        });
                }
            },
            submit(){
                this.$refs[form].validate((valid) => {
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
                axios.post('/api/tasks/new',this.form)
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
                axios.put('/api/tasks/'+this.id+'/edit', this.form)
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
            }
        },
        mounted() {
            
        }
    }
</script>