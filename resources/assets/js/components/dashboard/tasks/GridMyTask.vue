<template>
    <div class="tab-content grid">
        <task-status v-on:filterTasks="filterTasks" :task-option="taskOption" :all="taskCount.all"
        :behind="taskCount.behind" :pending="taskCount.pending" :completed="taskCount.completed"></task-status>
        <div class="tab-grid" id="buzz-scroll">
            <button class="task-box">
                <div class="title">
                    Website Redesign Concept
                </div>
                <div class="time">
                    assigned 3hrs ago
                </div>
                <div class="status completed">
                    Completed
                </div>
            </button>
            <button class="task-box">
                <div class="title">
                    Website Redesign Concept
                </div>
                <div class="time">
                    assigned 3hrs ago
                </div>
                <div class="status pending">
                    Pending
                </div>
            </button>
            <button class="task-box">
                <div class="title">
                    Website Redesign Concept
                </div>
                <div class="time">
                    assigned 3hrs ago
                </div>
                <div class="status behind">
                    Behind
                </div>
            </button>
            <button class="task-box">
                <div class="title">
                    Website Redesign Concept
                </div>
                <div class="time">
                    assigned 3hrs ago
                </div>
                <div class="status pending">
                    Pending
                </div>
            </button>
            <button class="task-box">
                <div class="title">
                    Website Redesign Concept
                </div>
                <div class="time">
                    assigned 3hrs ago
                </div>
                <div class="status completed">
                    Completed
                </div>
            </button>
            <button class="task-box">
                <div class="title">
                    Website Redesign Concept
                </div>
                <div class="time">
                    assigned 3hrs ago
                </div>
                <div class="status pending">
                    Pending
                </div>
            </button>
            <button class="task-box">
                <div class="title">
                    Website Redesign Concept
                </div>
                <div class="time">
                    assigned 3hrs ago
                </div>
                <div class="status behind">
                    Behind
                </div>
            </button>
            <button class="task-box">
                <div class="title">
                    Website Redesign Concept
                </div>
                <div class="time">
                    assigned 3hrs ago
                </div>
                <div class="status pending">
                    Pending
                </div>
            </button>
        </div>
    </div>
</template>

<script>
  import TaskStatus from './TaskStatus.vue';

    export default {
         components: {
            'task-status': TaskStatus,
        },
        data(){
            return {
                filteredTasks:[],
                myTasks: [],
                taskCount:{
                    all: 0,
                    completed: 0,
                    pending: 0,
                    behind: 0
                },
                taskOption: 'all'
            }
        },
        mounted(){
            this.getMyTasks();
        },
        methods:{
            getMyTasks(){
                axios.get('/api/user/tasks')
                .then( response => {
                    this.myTasks = response.data;
                    this.filterTasks('all');
                })
                .catch( error => {
                    if(error.response.status == 500 || error.response.status == 404){

                    }
                });
            },
            filterTasks( option){
                this.taskOption = option;
                if(option == 'all'){
                    this.filteredTasks = this.myTasks;
                }
                else {
                    this.filteredTasks = _.filter(this.myTasks, { status: option });
                }
                
                this.taskCount.all = this.myTasks.length;
                this.taskCount.completed = _.filter(this.myTasks,{ status: 'completed'}).length;
                this.taskCount.pending = _.filter(this.myTasks, { status: 'pending'}).length;
                this.taskCount.behind = _.filter(this.myTasks, { status: 'behind'}).length;
            }
        }
    }
</script>