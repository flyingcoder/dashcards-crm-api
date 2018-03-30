<template>
    <div class="tab-content grid">
        <task-status v-on:filterTasks="filterTasks" :task-option="taskOption" :all="taskCount.all"
        :behind="taskCount.behind" :pending="taskCount.pending" :completed="taskCount.completed"></task-status>
        <div class="tab-grid all" id="buzz-scroll">
            <button class="task-box">
                <div class="title">
                    Website Redesign Concept
                </div>
                <div class="image">
                    <img src="img/temporary/user1.png" alt="user">
                </div>
                <div class="time">
                    assigned to 
                    <div class="name"> Alan Podemskie </div> 
                    <div class="date"> 3hrs ago </div>
                </div>
                <div class="status completed">
                    Completed
                </div>
            </button>
            <button class="task-box">
                <div class="title">
                    Website Redesign Concept
                </div>
                <div class="image">
                    <img src="img/temporary/user2.png" alt="user">
                </div>
                <div class="time">
                    assigned to 
                    <div class="name"> Alan Podemskie </div> 
                    <div class="date"> 3hrs ago </div>
                </div>
                <div class="status pending">
                    Pending
                </div>
            </button>
            <button class="task-box">
                <div class="title">
                    Website Redesign Concept
                </div>
                <div class="image">
                    <img src="img/temporary/user3.png" alt="user">
                </div>
                <div class="time">
                    assigned to 
                    <div class="name"> Alan Podemskie </div> 
                    <div class="date"> 3hrs ago </div>
                </div>
                <div class="status behind">
                    Behind
                </div>
            </button>
            <button class="task-box">
                <div class="title">
                    Website Redesign Concept
                </div>
                <div class="image">
                    <img src="img/temporary/user2.png" alt="user">
                </div>
                <div class="time">
                    assigned to 
                    <div class="name"> Alan Podemskie </div> 
                    <div class="date"> 3hrs ago </div>
                </div>
                <div class="status pending">
                    Pending
                </div>
            </button>
            <button class="task-box">
                <div class="title">
                    Website Redesign Concept
                </div>
                <div class="image">
                    <img src="img/temporary/user1.png" alt="user">
                </div>
                <div class="time">
                    assigned to 
                    <div class="name"> Alan Podemskie </div> 
                    <div class="date"> 3hrs ago </div>
                </div>
                <div class="status completed">
                    Completed
                </div>
            </button>
            <button class="task-box">
                <div class="title">
                    Website Redesign Concept
                </div>
                <div class="image">
                    <img src="img/temporary/user2.png" alt="user">
                </div>
                <div class="time">
                    assigned to 
                    <div class="name"> Alan Podemskie </div> 
                    <div class="date"> 3hrs ago </div>
                </div>
                <div class="status pending">
                    Pending
                </div>
            </button>
            <button class="task-box">
                <div class="title">
                    Website Redesign Concept
                </div>
                <div class="image">
                    <img src="img/temporary/user3.png" alt="user">
                </div>
                <div class="time">
                    assigned to 
                    <div class="name"> Alan Podemskie </div> 
                    <div class="date"> 3hrs ago </div>
                </div>
                <div class="status behind">
                    Behind
                </div>
            </button>
            <button class="task-box">
                <div class="title">
                    Website Redesign Concept
                </div>
                <div class="image">
                    <img src="img/temporary/user2.png" alt="user">
                </div>
                <div class="time">
                    assigned to 
                    <div class="name"> Alan Podemskie </div> 
                    <div class="date"> 3hrs ago </div>
                </div>
                <div class="status pending">
                    Pending
                </div>
            </button>
            <button class="task-box">
                <div class="title">
                    Website Redesign Concept
                </div>
                <div class="image">
                    <img src="img/temporary/user1.png" alt="user">
                </div>
                <div class="time">
                    assigned to 
                    <div class="name"> Alan Podemskie </div> 
                    <div class="date"> 3hrs ago </div>
                </div>
                <div class="status completed">
                    Completed
                </div>
            </button>
            <button class="task-box">
                <div class="title">
                    Website Redesign Concept
                </div>
                <div class="image">
                    <img src="img/temporary/user2.png" alt="user">
                </div>
                <div class="time">
                    assigned to 
                    <div class="name"> Alan Podemskie </div> 
                    <div class="date"> 3hrs ago </div>
                </div>
                <div class="status pending">
                    Pending
                </div>
            </button>
            <button class="task-box">
                <div class="title">
                    Website Redesign Concept
                </div>
                <div class="image">
                    <img src="img/temporary/user3.png" alt="user">
                </div>
                <div class="time">
                    assigned to 
                    <div class="name"> Alan Podemskie </div> 
                    <div class="date"> 3hrs ago </div>
                </div>
                <div class="status behind">
                    Behind
                </div>
            </button>
            <button class="task-box">
                <div class="title">
                    Website Redesign Concept
                </div>
                <div class="image">
                    <img src="img/temporary/user2.png" alt="user">
                </div>
                <div class="time">
                    assigned to 
                    <div class="name"> Alan Podemskie </div> 
                    <div class="date"> 3hrs ago </div>
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
                allTasks: [],
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
            this.getAllTasks();
        },
        methods:{
            getAllTasks(){
                axios.get('/api/tasks')
                .then( response => {
                    this.allTasks = response.data;
                    this.filterTasks('all');
                })
                .catch( error => {
                    if(error.response.status == 500 || error.response.status == 404){

                    }
                });
            },
            filterTasks(option){
                this.taskOption = option;
                if(option == 'all'){
                    this.filteredTasks = this.allTasks.data;
                }
                else {
                    this.filteredTasks = _.filter(this.allTasks.data, { status: option });
                }
                this.taskCount.all = this.allTasks.data.length;
                this.taskCount.completed = _.filter(this.allTasks.data, { status: 'completed'}).length;
                this.taskCount.pending = _.filter(this.allTasks.data, { status: 'pending'}).length;
                this.taskCount.behind = _.filter(this.allTasks.data, { status: 'behind'}).length;

            }
        }
    }
</script>