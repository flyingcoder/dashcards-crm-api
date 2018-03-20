<template>
    <div class="tasks-status">
        <div class="option-list" 
        :class="taskOption == 'all' ? 'active' : ''" 
        @click="filterTasks(taskFilter, 'all')">
        <a> All
            <label> <span> {{ taskCount.all }} </span></label>
        </a>
        </div>
        <div class="option-list" 
        :class="taskOption == 'completed' ? 'active' : ''"
        @click="filterTasks(taskFilter,'completed')">
        <a> Completed
            <label> <span> {{ taskCount.completed }} </span></label>
        </a>
        </div>
        <div class="option-list" 
        :class="taskOption == 'pending' ? 'active' : ''" 
        @click="filterTasks(taskFilter, 'pending')">
        <a> Pending
            <label> <span> {{ taskCount.pending }} </span></label>
        </a>
        </div>
        <div class="option-list" 
        :class="taskOption == 'behind' ? 'active' : ''" 
        @click="filterTasks(taskFilter, 'behind')">
        <a> Behind
            <label> <span> {{ taskCount.behind }} </span></label>
        </a>
        </div>
    </div>
</template>


<script>
    export default {
        data(){
            return {
                filteredTasks:[],
                myTasks: [],
                allTasks: [],
                taskOption: 'all',
                taskFilter: 'my',
                taskCount:{
                    all: 0,
                    completed: 0,
                    pending: 0,
                    behind: 0
                }
            }
        },
        mounted(){
            this.getMyTasks();
            this.getAllTasks();
            this.filterTasks('my' , 'all');
        },
        methods:{
            getMyTasks(){
                axios.get('/api/user/tasks')
                .then( response => {
                    this.myTasks = response.data;
                })
                .catch( error => {
                    if(error.response.status == 500 || error.response.status == 404){

                    }
                });
            },
            getAllTasks(){
                axios.get('/api/tasks')
                .then( response => {
                    this.allTasks = response.data;
                })
                .catch( error => {
                    if(error.response.status == 500 || error.response.status == 404){

                    }
                });
            },
            filterTasks(filter, option){
                if(filter == 'my'){
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
                else{
                    if(option == 'all'){
                        this.filteredTasks = this.allTasks;
                    }
                    else {
                        this.filteredTasks = _.filter(this.allTasks, { status: option });
                    }
                    this.filteredTasks = _.filter(this.allTasks, { status: option });
                    this.taskCount.all = this.allTasks.length;
                    this.taskCount.completed = _.filter(this.allTasks, { status: 'completed'}).length;
                    this.taskCount.pending = _.filter(this.allTasks, { status: 'pending'}).length;
                    this.taskCount.behind = _.filter(this.allTasks, { status: 'behind'}).length;
                }
            }
        }
    }
</script>