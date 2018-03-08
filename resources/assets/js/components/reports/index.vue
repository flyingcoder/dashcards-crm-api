<template>
    <section class="content reports">
        <div class="content-header">
            <page-header></page-header>
        </div>
        <div class="content-body">
            <section class="buzz-section">
                <div class="buzz-table">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#all-reports" data-toggle="tab"> All Clients </a>
                        </li>
                        <li>
                            <a href="#my-reports" data-toggle="tab"> My Clients </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <all-reports></all-reports>
                        <my-reports></my-reports>
                    </div>
                </div>
            </section>
        </div>
    </section>
</template>

<script>
    import PageHeader from '../page-header.vue';
    import AllReports from './all-reports.vue';
    import MyReports from './my-reports.vue';

    export default {   

        components: {
          'page-header': PageHeader,
          'all-reports': AllReports,
          'my-reports': MyReports,
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

      mounted () {
        this.getMyProjects();
        this.getAllProjects();

      },

      methods: {
        getMyProjects(){
            axios.get('api/projects/mine')
                 .then( response => {
                    this.paginatedMyProjects = response.data.data;
                    this.currentPage = response.data.current_page;
                    this.total = response.data.total;
                 })
        },
        getAllProjects(){
            axios.get('api/projects')
            .then( response => {
                this.paginatedMyProjects = response.data;
            })
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
        rowClick(row, event, col){
            location = "/projects/" + row.id;
        }
      }

    }
</script>