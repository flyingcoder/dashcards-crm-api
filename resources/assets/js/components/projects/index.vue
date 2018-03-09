<template>
    <section class="content projects">
        <div class="content-header">
            <page-header title="Projects"></page-header>
        </div>
        <div class="content-body">
            <section class="buzz-section">
                <div class="buzz-table">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#all-project" data-toggle="tab"> All Project </a>
                        </li>
                        <li>
                            <a href="#my-project" data-toggle="tab"> My Project </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <all-projects></all-projects>
                        <my-projects></my-projects>
                    </div>
                </div>
            </section>
        </div>
    </section>
</template>

<script>
    import AllProjects from './AllProjects.vue';
    import MyProjects from './MyProjects.vue';

    export default {   
        components: {
          'all-projects': AllProjects,
          'my-projects': MyProjects,
      },

      data () {
        return {
        isProcessing: false,
        multipleSelection: [],
        currentPage: 1,
        currentSize: 10,
        total : 1,
        paginatedMyProjects: [],
        paginatedAllProjects: [],

        }
      },
      methods: {
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
      }

    }
</script>