<template>
    <div class="tasks-status">
        <div class="option-list" 
        :class="taskOption == 'all' ? 'active' : ''" 
        @click="filterTasks('all')">
        <a> All
            <label> <span> {{ all }} </span></label>
        </a>
        </div>
        <div class="option-list" 
        :class="taskOption == 'completed' ? 'active' : ''"
        @click="filterTasks('completed')">
        <a> Completed
            <label> <span> {{ completed }} </span></label>
        </a>
        </div>
        <div class="option-list" 
        :class="taskOption == 'pending' ? 'active' : ''" 
        @click="filterTasks('pending')">
        <a> Pending
            <label> <span> {{ pending }} </span></label>
        </a>
        </div>
        <div class="option-list" 
        :class="taskOption == 'behind' ? 'active' : ''" 
        @click="filterTasks('behind')">
        <a> Behind
            <label> <span> {{ behind }} </span></label>
        </a>
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