<template>
    <div class="project-status">
        <div class="col-md-3 option-list" 
            :class="taskOption == 'all' ? 'active' : ''" 
            @click="filterTasks('all')">
            <div class="option-counter"> 
                <span> All </span> 
                <el-badge :value="52" :max="99" class="item"></el-badge>
                <!-- <label> <span> {{ all }} </span></label> -->
            </div>
        </div>
        <div class="col-md-3 option-list" 
            :class="taskOption == 'completed' ? 'active' : ''"
            @click="filterTasks('completed')">
            <div class="option-counter">  
                <span> Completed </span> 
                <el-badge :value="10" :max="99" class="item"></el-badge>
                <!-- <label> <span> {{ completed }} </span></label> -->
            </div>
        </div>
        <div class="col-md-3 option-list" 
            :class="taskOption == 'pending' ? 'active' : ''" 
            @click="filterTasks('pending')">
            <div class="option-counter"> 
                <span> Pending </span>  
                <el-badge :value="20" :max="99" class="item"></el-badge>
                <!-- <label> <span> {{ pending }} </span></label> -->
            </div>
        </div>
        <div class="col-md-3 option-list" 
            :class="taskOption == 'behind' ? 'active' : ''" 
            @click="filterTasks('behind')">
            <div class="option-counter"> 
                <span> Behind </span> 
                <el-badge :value="22" :max="99" class="item"></el-badge>
                <!-- <label> <span> {{ behind }} </span></label> -->
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:[ 'all','completed','pending','behind','taskOption' ],
        methods: {
            filterTasks(filter) {
                this.$emit('filterTasks',filter);
            }
        }
    }
</script>