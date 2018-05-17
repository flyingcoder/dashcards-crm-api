<template>
    <section class="content services">
        <v-layout row wrap>
             <div class="col-md-6 content-header">
                <div class="page-title">
                    <h1> <span class="prev-path"> Dashboard </span> &nbsp; <img src="img/icons/ArrowRight.svg"> &nbsp; <span class="current"> Services </span> </h1>
                </div>
            </div>
            <div class="col-md-6">
                <div class="head-page-option">
                    <ul class="nav nav-tabs">
                        <add-service></add-service>
                        <li class="sort">
                                <el-dropdown trigger="click" placement="bottom-end">
                                <el-button size="small" class="el-dropdown-link">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="22px" height="6px">
                                        <path fill-rule="evenodd"
                                        d="M19.062,5.250 C17.665,5.250 16.531,4.124 16.531,2.734 C16.531,1.345 17.665,0.219 19.062,0.219 C20.460,0.219 21.594,1.345 21.594,2.734 C21.594,4.124 20.460,5.250 19.062,5.250 ZM10.953,5.250 C9.564,5.250 8.437,4.124 8.437,2.734 C8.437,1.345 9.564,0.219 10.953,0.219 C12.342,0.219 13.469,1.345 13.469,2.734 C13.469,4.124 12.342,5.250 10.953,5.250 ZM2.875,5.250 C1.477,5.250 0.344,4.124 0.344,2.734 C0.344,1.345 1.477,0.219 2.875,0.219 C4.273,0.219 5.406,1.345 5.406,2.734 C5.406,4.124 4.273,5.250 2.875,5.250 Z"/>
                                    </svg>
                                </el-button>
                                <el-dropdown-menu slot="dropdown" class="sort-dropdown">
                                    <el-dropdown-item>
                                        <a href="#"> Sort by Client </a>
                                    </el-dropdown-item>
                                    <el-dropdown-item>
                                        <a href="#"> Sort by Company </a>
                                    </el-dropdown-item>
                                    <el-dropdown-item>
                                        <a href="#"> Sort by Project </a>
                                    </el-dropdown-item>
                                    <el-dropdown-item>
                                        <a href="#"> Sort by Date </a>
                                    </el-dropdown-item>
                                </el-dropdown-menu>  
                            </el-dropdown>
                        </li>
                        <li>
                            <a href="#grid-view" data-toggle="tab">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="18px" height="17px">
                                    <path fill-rule="evenodd"  fill="rgb(102, 115, 129)"
                                    d="M17.324,16.511 C16.889,16.940 16.183,16.940 15.748,16.511 L9.191,10.039 L2.635,16.511 C2.200,16.940 1.494,16.940 1.058,16.511 C0.623,16.080 0.623,15.384 1.058,14.954 L7.614,8.484 L1.058,2.013 C0.623,1.583 0.623,0.886 1.058,0.456 C1.494,0.026 2.200,0.026 2.635,0.456 L9.191,6.927 L15.748,0.456 C16.183,0.026 16.889,0.026 17.324,0.456 C17.759,0.886 17.759,1.583 17.324,2.013 L10.768,8.484 L17.324,14.954 C17.759,15.384 17.759,16.080 17.324,16.511 Z"/>
                                </svg>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="content-body">
                    <section class="buzz-section">
                        <div class="buzz-table" v-if="notEmpty">
                            <el-table :data="paginatedServices" stripe empty-text="No Data Found" v-loading="isProcessing" 
                                @sort-change="handleSortChange" :element-loading-text="loadingText" 
                                @selection-change="handleSelectionChange" style="width: 100%"
                                >
                                    <el-table-column sortable type="selection" width="45"></el-table-column>
                                    <el-table-column sortable prop="service_name" label="Service" width="200"></el-table-column>
                                    <el-table-column sortable prop="name" label="Created By"></el-table-column>
                                    <el-table-column sortable prop="company" label="Company"></el-table-column>
                                    <el-table-column sortable prop="date_created" label="Date Created">
                                        <template slot-scope="scope">
                                            {{ scope.row.service_created_at | momentAgo }}
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
                            <EmptyServices></EmptyServices>
                        </div>
                    </section>
                </div>
            </div>
        </v-layout >
    </section>
</template>

<script>
    import AddService from './AddService.vue';
    import EmptyServices from './EmptyServices.vue';

    export default {   
      components: {
        'add-service': AddService,
        'EmptyServices': EmptyServices
      },
      data () {
        return {
        isProcessing: false,
        multipleSelection: [],
        currentPage: 1,
        currentSize: 10,
        total : 1,
        paginatedServices: [],
        loadingText: 'Fetching Datas ...',
        notEmpty: true
        }
      },

      mounted () {
        this.getServices();
      },

      methods: 
      {
        renderHeader(h,{column,$index}){
            return h('img', { attrs: { src: '../../../img/icons/menu.svg'}  });
        },
        getServices(){
            this.isProcessing = true;
            axios.get('api/services')
            .then( response => {
                
                this.isProcessing = false;
                this.paginatedServices = response.data.data;
                this.currentPage = response.data.current_page;
                this.total = response.data.total;

                if(this.paginatedServices < 1){
                    this.notEmpty = false;
                }
                else{
                    this.notEmpty = true;							
                }
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
        destroy: function(row) {
         var vm = this;	
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
			    vm.isProcessing = true;	
              vm.loadingText = 'Loading ...'
              axios.delete('/api/services/' + row.id)
              .then(response => {
                swal({
                  title: 'Success!',
                  text: 'Templete is Deleted!',
                  type: 'success'
                }).then( function() {
                    vm.loadingText = 'Updating ...'
                    vm.getServices();
                });
                
              });
            }
          })
        }
      }

    }
</script>