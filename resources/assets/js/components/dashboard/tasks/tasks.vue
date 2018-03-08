<template>
    <div class="box-content db-tasks">
        <div class="box-tabs" id="task-tabs">
        <ul class="nav nav-tabs">
            <li>
            <a href="#my-task" data-toggle="tab" @click="filterTasks('my', 'all')">My Task</a>
            </li>
            <li class="active">
            <a href="#all-task" data-toggle="tab" @click="filterTasks('all', 'all')">All Task</a>
            </li>
        </ul>
        <div class="tasks-option">
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
        <div class="tab-content">
            <div class="tab-pane fade tab-table" id="my-task">
                <table>
                    <thead>
                        <tr>
                            <th> </th>
                            <th> Assignee </th>
                            <th> Project </th>
                            <th> Status </th>
                        </tr>
                    </thead>
                    <tbody>
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
                            <td> <img :src="'img/temporary/' + t.assignee.image"> </td>
                            <td> 
                                <span class="buzz-overflow task-project"> {{ t.project }} </span>
                                <span class="assigned-project"> assigned to {{ t.assigned_to }} . {{ t.assign_date }} </span>
                            </td>
                            <td> 
                            <span class="status"> {{ t.status }} </span>
                            <div class="progress" :class="t.status"> </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade tab-table active show in" id="all-task">
                <table>
                    <thead>
                        <tr>
                            <th> </th>
                            <th> Assignee </th>
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
                            <td> <img :src="'img/temporary/' + t.assignee.image"> </td>
                            <td> 
                                <span class="buzz-overflow task-project"> {{ t.project }} </span>
                                <span class="assigned-project"> assigned to {{ t.assigned_to }} . {{ t.assign_date }} </span>
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
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                filteredTasks:[],
                myTasks: [
                            {
                                assignee: {
                                    image: 'user2.png'
                                },
                                project: 'Make a wireframe for a warasadsadwaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
                                assigned_to: 'Brian Howard',
                                assign_date: '2018-02-22 13:04:18',
                                status: 'behind',
                                user: {
                                    id: 1
                                }
                            },
                            {
                                assignee: {
                                    image: 'user3.png'
                                },
                                project: 'Social media marketing',
                                assigned_to: 'Jimmy Alister',
                                assign_date: '2018-02-22 13:04:18',
                                status: 'pending',
                                user: {
                                    id: 1
                                }
                            },
                            {
                                assignee: {
                                    image: 'user1.png'
                                },
                                project: 'Social media marketing',
                                assigned_to: 'Jimmy Alister',
                                assign_date: '2018-02-22 13:04:18',
                                status: 'pending',
                                user: {
                                    id: 1
                                }
                            },
                    ],
                allTasks: [
                        {
                            assignee: {
                                image: 'user1.png'
                            },
                            project: 'consectetur adipiscing elit',
                            assigned_to: 'Sumail Hassan',
                            assign_date: '2018-02-21 13:04:18',
                            status: 'behind',
                            user: {
                                id: 4
                            }
                        },
                        {
                            assignee: {
                                image: 'user1.png'
                            },
                            project: 'sed do eiusmod tempor',
                            assigned_to: 'Amer Al-Barqawi',
                            assign_date: '2018-02-21 13:04:18',
                            status: 'pending',
                            user: {
                                id: 5
                            }
                        },
                        {
                            assignee: {
                                image: 'user1.png'
                            },
                            project: 'incididunt ut labore',
                            assigned_to: 'Saahil Arora',
                            assign_date: '2018-02-21 13:04:18',
                            status: 'completed',
                            user: {
                                id: 6
                            }
                        },
                        {
                            assignee: {
                                image: 'user1.png'
                            },
                            project: 'et dolore magna aliqua',
                            assigned_to: 'Clinton Loomis',
                            assign_date: '2018-02-21 13:04:18',
                            status: 'behind',
                            user: {
                                id: 7
                            }
                        },
                        {
                            assignee: {
                                image: 'user1.png'
                            },
                            project: 'Ut enim ad minim',
                            assigned_to: 'Jacky Mao',
                            assign_date: '2018-02-21 13:04:18',
                            status: 'pending',
                            user: {
                                id: 8
                            }
                        },
                        {
                            assignee: {
                                image: 'user1.png'
                            },
                            project: 'quis nostrud exercitation',
                            assigned_to: 'Abed Yusop',
                            assign_date: '2018-02-21 13:04:18',
                            status: 'completed',
                            user: {
                                id: 9
                            }
                        },
                        {
                            assignee: {
                                image: 'user1.png'
                            },
                            project: 'ullamco laboris nisi ut',
                            assigned_to: 'Omar Aliwi',
                            assign_date: '2018-02-21 13:04:18',
                            status: 'behind',
                            user: {
                                id: 10
                            }
                        },
                        {
                            assignee: {
                                image: 'user1.png'
                            },
                            project: 'aliquip ex ea commodo consequat',
                            assigned_to: 'Clement Ivanov',
                            assign_date: '2018-02-21 13:04:18',
                            status: 'pending',
                            user: {
                                id: 11
                            }
                        },
                        {
                            assignee: {
                                image: 'user1.png'
                            },
                            project: 'Duis aute irure dolor in reprehenderit',
                            assigned_to: 'Dani Ishutin',
                            assign_date: '2018-02-21 13:04:18',
                            status: 'completed',
                            user: {
                                id: 12
                            }
                        },
                        {
                            assignee: {
                                image: 'user3.png'
                            },
                            project: 'in voluptate velit esse',
                            assigned_to: 'Marcel David',
                            assign_date: '2018-02-21 13:04:18',
                            status: 'behind',
                            user: {
                                id: 13
                            }
                        },
                        {
                            assignee: {
                                image: 'user1.png'
                            },
                            project: 'cillum dolore eu fugiat',
                            assigned_to: 'Gabriel Toledo',
                            assign_date: '2018-02-21 13:04:18',
                            status: 'pending',
                            user: {
                                id: 14
                            }
                        },
                        {
                            assignee: {
                                image: 'user1.png'
                            },
                            project: 'nulla pariatur',
                            assigned_to: 'Fernando Alvarenga',
                            assign_date: '2018-02-21 13:04:18',
                            status: 'completed',
                            user: {
                                id: 15
                            }
                        },
                        {
                            assignee: {
                                image: 'user1.png'
                            },
                            project: 'Excepteur sint occaecat',
                            assigned_to: 'Epitácio de Melo',
                            assign_date: '2018-02-21 13:04:18',
                            status: 'behind',
                            user: {
                                id: 16
                            }
                        },
                        {
                            assignee: {
                                image: 'user3.png'
                            },
                            project: 'cupidatat non proident',
                            assigned_to: 'João Vasconcellos',
                            assign_date: '2018-02-21 13:04:18',
                            status: 'pending',
                            user: {
                                id: 17
                            }
                        },
                        {
                            assignee: {
                                image: 'user2.png'
                            },
                            project: 'sunt in culpa qui',
                            assigned_to: 'João Vasconcellos',
                            assign_date: '2018-02-21 13:04:18',
                            status: 'pending',
                            user: {
                                id: 18
                            }
                        },
                        {
                            assignee: {
                                image: 'user3.png'
                            },
                            project: 'officia deserunt',
                            assigned_to: 'Olof Kajbjer',
                            assign_date: '2018-02-21 13:04:18',
                            status: 'behind',
                            user: {
                                id: 20
                            }
                        },
                        {
                            assignee: {
                                image: 'user1.png'
                            },
                            project: 'officia deserunt',
                            assigned_to: 'Olof Kajbjer',
                            assign_date: '2018-02-21 13:04:18',
                            status: 'behind',
                            user: {
                                id: 20
                            }
                        },
                    ],
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