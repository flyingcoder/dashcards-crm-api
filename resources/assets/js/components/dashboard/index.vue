<template>
    <section class="content dashboard">
        <div class="row content-header">
            <div class="col-md-6">
                <div class="page-title">
                    <h1>Dashboard</h1>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                <div class="col-md-4">
                    <div class="dashboard-option">
                        <div class="left">
                            <img src="img/icons/Save.svg" alt="Save">
                        </div>
                        <div class="right">
                            <span> Save </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dashboard-option">
                        <div class="left switch-box">
                            <!-- <label class="switch">
                            <input type="checkbox">
                            <span class="slider round"></span>
                            </label> -->
                            <el-switch v-model="value1"> </el-switch>
                        </div>
                        
                        <div class="right">
                            <span> Log On </span> 
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dashboard-option">
                        <div class="left theme-color"></div>
                        <div class="right theme-name">
                            <span> Theme 
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="19px" height="10px">
                                <path fill-rule="evenodd"  fill="rgb(102, 115, 129)"
                                d="M18.805,0.193 C18.543,-0.065 18.117,-0.065 17.856,0.193 L9.509,8.422 L1.145,0.193 C0.884,-0.065 0.458,-0.065 0.196,0.193 C-0.066,0.451 -0.066,0.869 0.196,1.127 L9.018,9.807 C9.149,9.935 9.312,10.000 9.492,10.000 C9.656,10.000 9.836,9.935 9.967,9.807 L18.788,1.127 C19.067,0.869 19.067,0.451 18.805,0.193 Z"/>
                            </svg> 
                            </span>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <div class="row buzz-tiles">
            <div class="col-md-6">
                <div class="row">
                <div class="col-md-4" v-if="!isNaN(myProjects)">
                    <div class="buzz-tile-content">
                    <div class="buzz-tile-icon orange-1">
                        <img src="img/sidebar/projects.svg">
                    </div>
                    <div class="buzz-tile-name">
                        <div class="buzz-tile-number"> {{ myProjects }} </div>
                        <div class="buzz-tile-title"> My Projects </div>
                    </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="buzz-tile-content">
                    <div class="buzz-tile-icon green-2">
                        <img src="img/sidebar/templates.svg">
                    </div>
                    <div class="buzz-tile-name">
                        <div class="buzz-tile-number"> {{ myTasksCount }} </div>
                        <div class="buzz-tile-title"> My Open Task </div>
                    </div>
                    </div>
                </div>
                <div class="col-md-4" v-if="!isNaN(myCalendar)">
                    <div class="buzz-tile-content">
                    <div class="buzz-tile-icon purple-3">
                        <img src="img/sidebar/calendar.svg">
                    </div>
                    <div class="buzz-tile-name">
                        <div class="buzz-tile-number"> {{ myCalendar }} </div>
                        <div class="buzz-tile-title">My Calendar </div>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-4" v-if="!isNaN(myTimer)">
                        <div class="buzz-tile-content">
                            <div class="buzz-tile-icon green-4">
                                <img src="img/sidebar/timer.svg">
                            </div>
                            <div class="buzz-tile-name">
                                <div class="buzz-tile-number"> {{ myTimer }} </div>
                                <div class="buzz-tile-title"> My Timer </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4" v-if="!isNaN(inbound)">
                        <div class="buzz-tile-content">
                        <div class="buzz-tile-icon blue-5">
                        <img src="img/sidebar/templates.svg">
                        </div>
                        <div class="buzz-tile-name">
                            <div class="buzz-tile-number"> {{ inbound }} </div>
                            <div class="buzz-tile-title"> Inbound </div>
                        </div>
                        </div>
                    </div>
                    <div class="col-md-4" v-if="!isNaN(outbound)">
                        <div class="buzz-tile-content">
                        <div class="buzz-tile-icon orange-6">
                            <img src="img/sidebar/calendar.svg">
                        </div>
                        <div class="buzz-tile-name">
                            <div class="buzz-tile-number"> {{ outbound }} </div>
                            <div class="buzz-tile-title"> Outbound </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
            <!-- Tasks -->
                <div class="buzz-box db-tasks" v-if="isEnable('tasks')">
                    <div class="box-head">
                        <h1>Tasks</h1>
                        <div class="box-options tasks-view">
                            <box-options></box-options>
                        </div>
                    </div>
                    <tasks></tasks>
                    <div class="box-footer">
                        <a href=""> View More </a>
                    </div>
                </div>
            <!-- Client -->
                <div class="buzz-box" v-if="isEnable('client')">
                    <div class="box-head">
                        <h1>Clients</h1>
                        <box-options></box-options>
                    </div>
                    <client></client>
                    <div class="box-footer">
                        <a href=""> View More </a>
                    </div>
                </div>
            <!-- Payment -->
                <div class="buzz-box" v-if="isEnable('payment')">
                    <div class="box-head"  >
                        <h1>Payment</h1>
                        <box-options></box-options>
                    </div>
                    <payment></payment>
                    <div class="box-footer">
                        <a href=""> View More </a>
                    </div>
                </div>
            <!-- Calendar -->
                <div class="buzz-box"  v-if="isEnable('calendar')">
                    <div class="box-head">
                        <h1>Calendar</h1>
                        <box-options></box-options>
                    </div>
                    <calendar></calendar>
                    <div class="box-footer">
                        <a href=""> View More </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
            <!-- Timeline -->
                 <div class="buzz-box"  v-if="isEnable('timeline')">
                    <div class="box-head">
                        <h1>Timeline</h1>
                        <box-options></box-options>
                    </div>
                    <timeline></timeline>
                    <div class="box-footer">
                        <a href=""> View More </a>
                    </div>
                </div>
            <!-- Timer -->
                <div class="buzz-box"  v-if="isEnable('timer')">
                    <div class="box-head" >
                        <h1>Timer</h1>
                        <box-options></box-options>
                    </div>
                    <!-- <timer></timer> -->
                    <div class="box-footer">
                        <a href=""> View More </a>
                    </div>
                </div>
            <!-- Invoice -->
                <div class="buzz-box"  v-if="isEnable('invoice')">
                    <div class="box-head" >
                        <h1>Invoice</h1>
                        <box-options></box-options>
                    </div>
                    <invoice></invoice>
                    <div class="box-footer">
                        <a href=""> View More </a>
                    </div>
                </div>
            <!-- Passbox -->    
                <div class="buzz-box" v-if="isEnable('passbox')">
                    <div class="box-head">
                        <h1>Pass Box</h1>
                        <box-options></box-options>
                    </div>
                    <passbox></passbox>
                    <div class="box-footer">
                        <a href=""> View More </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import BoxOptions from './box-options.vue';
    import Tasks from './tasks/Index.vue';
    import Client from './client/Index.vue';
    import Payment from './payment/Index.vue';
    import Calendar from './calendar/calendar.vue';
    import Timeline from './timeline/Index.vue';
    import Timer from './timer/Index.vue';
    import Invoice from './invoice/Index.vue';
    import Passbox from './passbox/passbox-login.vue';

    export default {
        components: {
            'box-options': BoxOptions,
            'tasks': Tasks,
            'client': Client,
            'payment': Payment,
            'calendar': Calendar,
            'timeline': Timeline,
            'timer': Timer,
            'invoice': Invoice,
            'passbox': Passbox,
        },

        data(){
            return {
                myProjects: 0,
                myTasksCount: 0,
                myCalendar: 0,
                myTimer: 0,
                inbound: 0,
                outbound: 0,
                value1: true,
                defaultItems: []
            }
        },
            mounted(){
                this.getCounts();
                this.getDefault();
            },
            methods:{
                isEnable(slug){
                    let dashitem = _.find(this.defaultItems, { 'slug' : slug});
                    if(dashitem){
                        return true;
                    }
                    else {
                        return false;
                    }
                    
                },
                getDefault(){
                    axios.get('/api/dashboard/default/dashitems')
                    .then( response => {
                        this.defaultItems = response.data;
                    })
                },
                getCounts(){
                    axios.get('/api/dashboard/counts')
                    .then( response => {
                        this.myProjects = response.data.projects;
                        this.myTasks = response.data.tasks;
                        this.myCalendar = response.data.calendars;
                        this.myTimer = response.data.timer;
                        this.inbound = response.data.inbound;
                        this.outbound = response.data.outbound;
                    })
                    // .catch( error => {
                    //     // if(error.response.status == 500 || error.response.status == 404){

                    //     // }
                    // });
                },
            }
    }
</script>