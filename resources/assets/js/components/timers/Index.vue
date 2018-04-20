<template>
    <section class="content timers">
        <div class="tabs" id="timer-tabs">
            <ul class="timer-tabs nav nav-tabs">
                <li>
                    <a href="#alarm" data-toggle="tab"> Alarm </a>
                </li>
                 <li class="active">
                    <a href="#timer" data-toggle="tab"> Timer </a>
                </li>
            </ul>
        </div>
        <div class="tab-content">
            <alarm></alarm>
            <timer></timer>
        </div>
    </section>
</template>

<script>
     import Timer from './Timer.vue';
     import Alarm from './Alarm.vue';
     
    export default {  
        components: {
            'timer': Timer,
            'alarm': Alarm,
        }, 
        data () {
            return {
            isProcessing: false,
            multipleSelection: [],
            currentPage: 1,
            currentSize: 10,
            total : 1,
            }
        },

      mounted () {
      },

      methods: {
        renderHeader(h,{column,$index}){
            return h('img', { attrs: { src: '../../../img/icons/menu.svg'}  });
        },
        handleSizeChange: function (val) {
            this.currentSize = val;
        },
        handleCurrentChange: function (val) {
            this.currentPage = val;
        },
        handleSortChange: function (col) {
            this.orderName = col.prop;
            this.orderBy = col.order == 'ascending' ? 'asc' : 'desc';
        },
        handleSelectionChange: function(val) {
            this.multipleSelection = [];
            for (let index in val) {
            this.multipleSelection.push(val[index].id);
            }
        },
        // rowClick(row, event, col){
        //     location = "/projects/" + row.id;
        // }
      }

    }
</script>