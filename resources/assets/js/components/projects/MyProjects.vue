<template>
    <div class="my-projects tab-pane fade" id="my-project">
         <div v-if="notEmpty">      
            <el-table :data="paginatedMyProjects" stripe empty-text="No Data Found" v-loading="isProcessing" 
            @sort-change="handleSortChange" :element-loading-text="loadingText"
            @selection-change="handleSelectionChange" style="width: 100%"
            @cell-click="cellClick"
            >
                <el-table-column type="selection" width="60"></el-table-column>
                <el-table-column sortable prop="title" label="Title" width="115"></el-table-column>
                <el-table-column sortable prop="service_name" label="Service" width="115"></el-table-column>
                <el-table-column prop="client_image_url" label="Client" width="85">
                    <template slot-scope="scope">
                        <img :src="scope.row.client_image_url" class="user-image">
                    </template>
                </el-table-column>
                <!-- <el-table-column prop="manager_name" label="Project Manager"  width="135"></el-table-column> -->
                <el-table-column sortable prop="started_at" label="Start Date" width="115"></el-table-column>
                <el-table-column sortable label="Progress" width="150">
                    <div class="progress project-progress"> 
                        <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </el-table-column>
                <el-table-column prop="time_spent" label="Time Spent" width="100"></el-table-column>
                <el-table-column sortable label="Status">
                    <template slot-scope="scope">
                        <span class="status"> {{ scope.row.status }} </span>
                        <div class="progress project-status" :class="scope.row.status.toLowerCase()"> </div>
                    </template>
                </el-table-column>
                <el-table-column fixed="right" :render-header="renderHeader">
                    <template slot-scope="scope">
                        <el-button @click="edit(scope.row)">
                            <svg viewBox="0 0 250 250">
                                <path class="edit" d="M192 10l54 56c4,5 4,13 -1,18l-18 17c-5,5 -13,5 -17,0l-54 -56c-5,-5 -5,-13 0,-18l18 -17c5,-5 13,-5 18,0zm-140 202l40 -13 -39 -41 -16 38 15 16zm99 -152l43 45c8,8 7,21 -1,29l-80 77c-1,0 -92,30 -100,32 -2,1 -5,1 -7,0 -4,-2 -6,-7 -4,-12l40 -94 80 -77c8,-8 21,-8 29,0z"/>
                            </svg>
                        </el-button>
                        <el-button @click="destroy(scope.row)">
                            <svg viewBox="0 0 250 250">
                                <path class="delete" d="M61 83l129 0c6,0 11,5 10,10l-3 146c-1,6 -5,11 -11,11l-121 0c-6,0 -11,-5 -11,-11l-4 -146c0,-5 5,-10 11,-10zm37 -83l54 0c5,0 9,2 12,5l0 0c3,3 4,7 4,11l0 10 33 0c6,0 11,4 11,10l0 23c0,6 -5,11 -11,11l-152 0c-6,0 -11,-5 -11,-11l0 -23c0,-6 5,-10 11,-10l33 0 0 -10c0,-4 2,-8 5,-11 3,-3 7,-5 11,-5zm1 26l53 0 0 -9 -53 0 0 9zm-5 83l0 0c5,0 9,4 9,10l0 95c0,5 -4,9 -9,9l0 0c-6,0 -10,-4 -10,-9l0 -95c0,-6 4,-10 10,-10zm64 0l0 0c5,0 9,4 9,10l0 95c0,5 -4,9 -9,9l0 0c-5,0 -10,-4 -10,-9l0 -95c0,-6 5,-10 10,-10zm-32 0l0 0c5,0 9,4 9,10l0 95c0,5 -4,9 -9,9l0 0c-5,0 -10,-4 -10,-9l0 -95c0,-6 5,-10 10,-10z"/>
                            </svg>
                        </el-button>
                    </template>
                </el-table-column>
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
        <div v-else> 
            <EmptyProjects></EmptyProjects>
        </div>
    </div>
</template>


<script>
    import EmptyProjects from './EmptyProjects.vue';

    export default { 
        components: {
          'EmptyProjects': EmptyProjects,
      },  
      data () {
        return {
        isProcessing: true,
        multipleSelection: [],
        currentPage: 1,
        currentSize: 10,
        total : 1,
        paginatedMyProjects: [],
        notEmpty: true,
        loadingText: 'Fetching datas ...',
        }
      },
      mounted () {
        this.getMyProjects();
      },
      methods: {
        renderHeader(h,{column,$index}){
            return h('img', { attrs: { src: '../../../img/icons/menu.svg'}  });
        },
        getMyProjects(){
        isProcessing: true,
            
            axios.get('api/user/projects')
                 .then( response => {
                    this.isProcessing = false;                     
                    this.paginatedMyProjects = response.data.data;
                    this.currentPage = response.data.current_page;
                    this.total = response.data.total;
                    if(this.paginatedMyProjects < 1){
                        this.notEmpty = false;
                    }
                    else{
                        this.notEmpty = true;							
                    }
                 })
                 .catch( error => {
                    if (error.response.status == 401) {
                        location.reload();
                    } else {
                        this.isProcessing = false;
                        this.paginatedMyProjects = [];
                        this.notEmpty = false;
                    }
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
            // location = "/project-hq/" + row.id;
        },
        destroy: function(row) {
            var self = this;
                swal({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delete it!'
            }).then(function (result) {
              if (result) {
                axios.delete('/projects/' + row.id + '/delete')
                .then(response => {
                    swal('Success!', 'Project is Deleted!', 'success');
                    self.getMyProjects();
                });
              }
          })
            
        },
        cellClick: function(row, col) {
            var a = col.id;
            if(a != 'el-table_3_column_22') {
                location = "/project-hq/" + row.id; 
            }
        },
        edit(data){
            this.$modal.show('add-project', { action: 'Update', data: data })
        },
        updated() {
          this.loadingText = 'Updating ...'
          this.getMyProjects();
        }
      }
    }
</script>