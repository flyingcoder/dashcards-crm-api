<template>
    <div class="buzz-tabs">
        <v-tabs v-model="active" class="view-tabs">
            <v-tab>
                All Task
            </v-tab>
            <v-tab-item class="all-task">
                <list-alltask></list-alltask>
            </v-tab-item>
            <v-tab>
                My Task
            </v-tab>
            <v-tab-item class="my-task">
                <list-mytask></list-mytask>
            </v-tab-item>
        </v-tabs>
    </div>
</template>

<script>
    import ListAllTask from './ListAllTask.vue';
    import ListMyTask from './ListMyTask.vue';
    import GridAllTask from './GridAllTask.vue';
    import GridMyTask from './GridMyTask.vue';
    import TaskSelected from './TaskSelected.vue';

  export default {
      components: {
            'list-alltask': ListAllTask,
            'list-mytask': ListMyTask,
            'grid-alltask': GridAllTask,
            'grid-mytask': GridMyTask,
            'task-selected': TaskSelected,
        },
    data() {
      return {
        pickerOptions1: {
            disabledDate(time) {
                return time.getTime() > Date.now();
            },
            shortcuts: [{
                text: 'Today',
                onClick(picker) {
                picker.$emit('pick', new Date());
                }
            }, {
                text: 'Yesterday',
                onClick(picker) {
                const date = new Date();
                date.setTime(date.getTime() - 3600 * 1000 * 24);
                picker.$emit('pick', date);
                }
            }, {
                text: 'A week ago',
                onClick(picker) {
                const date = new Date();
                date.setTime(date.getTime() - 3600 * 1000 * 24 * 7);
                picker.$emit('pick', date);
                }
            }]
        },
        value1: 'March 16, 2018',
      };
    },
    methods: {
      handleClick(tab, event) {
        console.log(tab, event);
      }
    }
  };
</script>