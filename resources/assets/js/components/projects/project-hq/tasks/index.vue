<template>
    <section class="content hq-tasks">
        <div class="row">
            <div class="col-md-6">
                <tasks :project-id="$parent.projectId" v-on:clickTask="clickTask"></tasks>
            </div>
            <div class="col-md-6 ">
                <task-info></task-info>
            </div>
        </div>
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
                taksId : 0,
            }
        },
        methods:{
            clickTask(val){
                this.taksId = val.id;
            }
        }
    
    }
</script>