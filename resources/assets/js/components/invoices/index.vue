<template>
    <section class="content invoice">
        <div class="content-header">
            <page-header></page-header>
        </div>
        <div class="content-body">
            <section class="buzz-section">
                <div class="buzz-table">
                     <el-table :data="paginatedMyProjects" stripe empty-text="No Data Found" v-loading="isProcessing" 
                        @sort-change="handleSortChange" element-loading-text="Processing ..." 
                        @selection-change="handleSelectionChange" style="width: 100%"
                        @row-click="rowClick">
                            <el-table-column sortable type="selection" width="45"></el-table-column>
                            <el-table-column sortable prop="invoice_date" label="Date"></el-table-column>
                            <el-table-column sortable prop="invoice_num" label="Invoice Number"></el-table-column>
                            <el-table-column sortable prop="client_name" label="Client"></el-table-column>
                            <el-table-column sortable prop="status" label="Status"></el-table-column>
                            <el-table-column sortable prop="action" label="Action"></el-table-column>
                            <el-table-column sortable prop="amount" label="Amount"></el-table-column>
                        </el-table>
                    <el-pagination
                        @size-change="handleSizeChange"
                        @current-change="handleCurrentChange"
                        :current-page.sync="currentPage"
                        :page-sizes="[10, 20, 50, 100]"
                        :page-size="currentSize"
                        layout="total, sizes, prev, pager, next"
                        :total="total">
                    </el-pagination>
                </div>
            </section>
        </div>
    </section>
</template>

<script>
    import PageHeader from '../page-header.vue';

    export default {   

        components: {
          'page-header': PageHeader,
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