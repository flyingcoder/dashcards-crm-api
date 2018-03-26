<template>
    <section class="content hq-tasks">
        <div class="row">
            <div class="col-md-6">
                <tasks :project-id="$parent.projectId" v-on:clickTask="clickTask"></tasks>
            </div>
            <div class="col-md-6 ">
                <comments></comments>
            </div>
        </div>
    </section>
</template>
<script>
import Tasks from './Tasks.vue'
import Comments from './Comments.vue'
export default {
    components: {
        Tasks: 'tasks',
        Comments: 'comments'
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