<template>
    <div class="tab-content list">
        <task-status v-on:filterTasks="filterTasks" :task-option="taskOption" :all="taskCount.all"
        :behind="taskCount.behind" :pending="taskCount.pending" :completed="taskCount.completed"></task-status>
        <div class="tab-table">
            <table>
                <thead>
                    <tr>
                        <th> </th>
                        <th class="assignee"> Assignee </th>
                        <th> Project </th>
                        <th> Status </th>
                    </tr>
                </thead>
                <tbody class="buzz-scrollbar" id="buzz-scroll">
                    <tr  v-for="t in filteredTasks" :key="t.id">
                        <td> 
                            <div class="hover-display"> 
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                width="18px" height="35px">
                                <path fill-rule="evenodd"  fill="rgb(218, 225, 231)"
                                d="M15.000,21.000 C13.343,21.000 12.000,19.657 12.000,18.000 C12.000,16.343 13.343,15.000 15.000,15.000 C16.657,15.000 18.000,16.343 18.000,18.000 C18.000,19.657 16.657,21.000 15.000,21.000 ZM15.000,6.000 C13.343,6.000 12.000,4.657 12.000,3.000 C12.000,1.343 13.343,-0.000 15.000,-0.000 C16.657,-0.000 18.000,1.343 18.000,3.000 C18.000,4.657 16.657,6.000 15.000,6.000 ZM3.000,35.000 C1.343,35.000 -0.000,33.657 -0.000,32.000 C-0.000,30.343 1.343,29.000 3.000,29.000 C4.657,29.000 6.000,30.343 6.000,32.000 C6.000,33.657 4.657,35.000 3.000,35.000 ZM3.000,21.000 C1.343,21.000 -0.000,19.657 -0.000,18.000 C-0.000,16.343 1.343,15.000 3.000,15.000 C4.657,15.000 6.000,16.343 6.000,18.000 C6.000,19.657 4.657,21.000 3.000,21.000 ZM3.000,6.000 C1.343,6.000 -0.000,4.657 -0.000,3.000 C-0.000,1.343 1.343,-0.000 3.000,-0.000 C4.657,-0.000 6.000,1.343 6.000,3.000 C6.000,4.657 4.657,6.000 3.000,6.000 ZM15.000,29.000 C16.657,29.000 18.000,30.343 18.000,32.000 C18.000,33.657 16.657,35.000 15.000,35.000 C13.343,35.000 12.000,33.657 12.000,32.000 C12.000,30.343 13.343,29.000 15.000,29.000 Z"/>
                                </svg> 
                            </div> 
                        </td>
                        <!-- <td> <img :src="'/' + t.assignee[0].image_url"> </td> -->
                        <td class="assignee"> <img src="/img/temporary/user1.png"> </td>
                        <td class="projects">
                            <div class="buzz-overflow task-project"> {{ t.title }} </div>
                            <span class="assigned-project"> assigned to 
                                {{
                                    t.assigned | concat
                                }}. {{ t.created_at | momentAgo}} </span>
                        </td>
                        <td> 
                        <span class="status"> {{ t.status }} </span>
                        <div class="progress" :class="t.status"> </div>
                        </td>
                    </tr>
                </tbody>
            </table>
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
                    this.filteredTasks = _.filter(this.allTasks, { status: option });
                }
                this.taskCount.all = this.allTasks.data.length;
                this.taskCount.completed = _.filter(this.allTasks.data, { status: 'completed'}).length;
                this.taskCount.pending = _.filter(this.allTasks.data, { status: 'pending'}).length;
                this.taskCount.behind = _.filter(this.allTasks.data, { status: 'behind'}).length;

            }
        },
        filters: {
            concat: function (value) {
                console.log(value.length != 0);
                if( value.length != 0 )
                    return value[0].first_name + " " +value[0].last_name;
                else
                    return 'no one';
            }
        }
    }
</script>