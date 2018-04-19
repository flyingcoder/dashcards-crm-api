<template>
    <div class="buzz-table">
        <el-table :data="paginatedMyInvoices" stripe empty-text="No Data Found" v-loading="isProcessing" 
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
</template>

<script>
    import Form from './form/Index.vue';

    export default { 
        components: {
          'invoice-form': Form,
      },  
        data () {
            return {
            isProcessing: false,
            multipleSelection: [],
            currentPage: 1,
            currentSize: 10,
            total : 1,
            paginatedMyInvoices: [],
            }
        },
        mounted () {
            this.getMyInvoices();
        },
        methods: {
            renderHeader(h,{column,$index}){
                return h('img', { attrs: { src: '../../../img/icons/menu.svg'}  });
            },
            getMyInvoices(){
                axios.get('api/user/invoices')
                    .then( response => {
                        this.paginatedMyInvoices = response.data.data;
                        this.currentPage = response.data.current_page;
                        this.total = response.data.total;
                    })
            },
            TableColumnClass({column, rowIndex}){
                if (rowIndex === 10) {
                return 'second';
                } else if (rowIndex === 3) {
                return 'success-row';
                }
                return '';
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
            rowClick(row, event, cell, col){
                location = "/invoice-hq/" + row.id;
            },
            edit(data){
                console.log(data);
            }
        }
    }
</script>