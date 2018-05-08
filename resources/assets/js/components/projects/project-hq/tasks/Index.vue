<template>
    <section class="content hq-tasks">
        <v-layout row wrap>
            <div class="col-md-6">
                <tasks :project-id="$parent.projectId" v-on:clickTask="clickTask"></tasks>
            </div>
            <div class="col-md-6 ">
                <task-info :task="task"></task-info>
            </div>
        </v-layout>
        <add-task></add-task>
    </section>
</template>

<script>
    import Tasks from './Tasks.vue'
    import TaskInfo from './TaskInfo.vue'
    import AddTask from './AddTask.vue'
    export default {
        components: {
            'tasks': Tasks ,
            'task-info': TaskInfo,
            'add-task': AddTask,
        },
        data(){
            return {
                task : [],
            }
        },
        methods:{
            clickTask(val){
                this.task = val.task;
            },
            
        }
    
    }
</script>