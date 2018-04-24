<template>
  <div class="buzz-box">
    <div class="box-content">
        <div class="box-tabs" id="task-tabs">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#all-task" data-toggle="tab" @click="filterTasks('all', 'all')"> All Task </a>
                </li>
                <li>
                    <a href="#my-task" data-toggle="tab" @click="filterTasks('my', 'all')"> My Task </a>
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
                <div class="tab-pane fade tab-table active in" id="all-task">
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
                            <tr  v-for="t in filteredTasks" :key="t.id" @click="clickTask(t)">
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
                                    <span class="assigned-project"> assigned to {{
                                        t.assigned == null ? '' :
                                        t.assigned[0].first_name + ' ' + t.assigned[0].last_name 
                                        }}. {{ t.created_at | momentAgo}} </span>
                                </td>
                                <td> 
                                <span class="status"> {{ t.status }} </span>
                                <div class="progress" :class="t.status"> </div>
                                </td>
                            </tr>
                            <tr  v-for="t in filteredTasks" :key="t.id" @click="clickTask(t)">
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
                                    <span class="assigned-project"> assigned to {{
                                        t.assigned == null ? '' :
                                        t.assigned[0].first_name + ' ' + t.assigned[0].last_name 
                                        }}. {{ t.created_at | momentAgo}} </span>
                                </td>
                                <td> 
                                <span class="status"> {{ t.status }} </span>
                                <div class="progress" :class="t.status"> </div>
                                </td>
                            </tr>
                            <tr class="box-footer">
                                <a href="#"> View More </a>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade tab-table" id="my-task">
                    <table>
                        <thead>
                            <tr>
                                <th> </th>
                                <th> Project </th>
                                <th> Status </th>
                            </tr>
                        </thead>
                        <tbody class="buzz-scrollbar" id="buzz-scroll">
                            <tr  v-for="t in filteredTasks" :key="t.id" @click="clickTask(t)">
                                <td> 
                                    <div class="hover-display"> 
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="18px" height="35px">
                                        <path fill-rule="evenodd"  fill="rgb(218, 225, 231)"
                                        d="M15.000,21.000 C13.343,21.000 12.000,19.657 12.000,18.000 C12.000,16.343 13.343,15.000 15.000,15.000 C16.657,15.000 18.000,16.343 18.000,18.000 C18.000,19.657 16.657,21.000 15.000,21.000 ZM15.000,6.000 C13.343,6.000 12.000,4.657 12.000,3.000 C12.000,1.343 13.343,-0.000 15.000,-0.000 C16.657,-0.000 18.000,1.343 18.000,3.000 C18.000,4.657 16.657,6.000 15.000,6.000 ZM3.000,35.000 C1.343,35.000 -0.000,33.657 -0.000,32.000 C-0.000,30.343 1.343,29.000 3.000,29.000 C4.657,29.000 6.000,30.343 6.000,32.000 C6.000,33.657 4.657,35.000 3.000,35.000 ZM3.000,21.000 C1.343,21.000 -0.000,19.657 -0.000,18.000 C-0.000,16.343 1.343,15.000 3.000,15.000 C4.657,15.000 6.000,16.343 6.000,18.000 C6.000,19.657 4.657,21.000 3.000,21.000 ZM3.000,6.000 C1.343,6.000 -0.000,4.657 -0.000,3.000 C-0.000,1.343 1.343,-0.000 3.000,-0.000 C4.657,-0.000 6.000,1.343 6.000,3.000 C6.000,4.657 4.657,6.000 3.000,6.000 ZM15.000,29.000 C16.657,29.000 18.000,30.343 18.000,32.000 C18.000,33.657 16.657,35.000 15.000,35.000 C13.343,35.000 12.000,33.657 12.000,32.000 C12.000,30.343 13.343,29.000 15.000,29.000 Z"/>
                                        </svg> 
                                    </div> 
                                </td>
                                <td class="projects"> 
                                    <div class="buzz-overflow task-project"> {{ t.title }} </div>
                                    <span class="assigned-project"> assigned to {{
                                        t.assigned == null ? '' :
                                        t.assigned[0].first_name + ' ' + t.assigned[0].last_name 
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
        </div>
    </div>
</div>
</template>

<script>
export default {
  props: ['projectId'],
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
  },
  methods:{
      getAllTasks(){
          axios.get('/api/projects/' + this.projectId + '/tasks')
          .then( response => {
            this.allTasks = response.data.data;   
            this.filterTasks('all' , 'all');   
          })
          .catch( error => {
              if(error.response.status == 500 || error.response.status == 404){

              }
          });
      },
      
      getMyTasks(){
          axios.get('/api/projects/' + this.projectId + '/tasks/mine')
          .then( response => {
            this.myTasks = response.data.data;              
              
          })
          .catch( error => {
              if(error.response.status == 500 || error.response.status == 404){

              }
          });
      },
      filterTasks(filter, option){
          this.taskOption = option;
          this.taskFilter = filter;
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
              this.taskCount.all = this.allTasks.length;
              this.taskCount.completed = _.filter(this.allTasks, { status: 'completed'}).length;
              this.taskCount.pending = _.filter(this.allTasks, { status: 'pending'}).length;
              this.taskCount.behind = _.filter(this.allTasks, { status: 'behind'}).length;
          }
      },
      clickTask(val){
          this.$emit('clickTask', {
            task: val
            });
      }
  }
}
</script>