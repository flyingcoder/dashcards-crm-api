<template>
    <section class="content milestones">
        <v-layout row wrap>
            <div class="col-md-6 content-header">
                <div class="page-title">
                    <h1> <span class="prev-path"> Dashboard </span> &nbsp; <img src="img/icons/ArrowRight.svg"> &nbsp; <span class="current"> Milestones Template </span> </h1>
                </div>
            </div>
            <div class="col-md-6">
                <div class="head-page-option">
                    <ul class="nav nav-tabs">
                        <li>
                            <div class="add-button" @click="$modal.show('add-template')">
                                <span> ADD NEW </span>
                                <button>
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="20px" height="20px">
                                        <path fill-rule="evenodd"  fill="rgb(255, 255, 255)"
                                        d="M18.852,10.789 L11.590,10.789 L11.590,19.039 C11.590,19.444 11.193,19.773 10.703,19.773 C10.212,19.773 9.815,19.444 9.815,19.039 L9.815,10.789 L1.663,10.789 C1.262,10.789 0.937,10.387 0.937,9.892 C0.937,9.395 1.262,8.993 1.663,8.993 L9.815,8.993 L9.815,1.645 C9.815,1.240 10.212,0.911 10.703,0.911 C11.193,0.911 11.590,1.240 11.590,1.645 L11.590,8.993 L18.852,8.993 C19.252,8.993 19.577,9.395 19.577,9.892 C19.577,10.387 19.252,10.789 18.852,10.789 Z"/>
                                    </svg>
                                </button>
                            </div>
                        </li>
                        <li class="sort">
                            <el-dropdown trigger="click" placement="bottom">
                                <el-button size="small" class="el-dropdown-link">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="22px" height="6px">
                                        <path fill-rule="evenodd"
                                        d="M19.062,5.250 C17.665,5.250 16.531,4.124 16.531,2.734 C16.531,1.345 17.665,0.219 19.062,0.219 C20.460,0.219 21.594,1.345 21.594,2.734 C21.594,4.124 20.460,5.250 19.062,5.250 ZM10.953,5.250 C9.564,5.250 8.437,4.124 8.437,2.734 C8.437,1.345 9.564,0.219 10.953,0.219 C12.342,0.219 13.469,1.345 13.469,2.734 C13.469,4.124 12.342,5.250 10.953,5.250 ZM2.875,5.250 C1.477,5.250 0.344,4.124 0.344,2.734 C0.344,1.345 1.477,0.219 2.875,0.219 C4.273,0.219 5.406,1.345 5.406,2.734 C5.406,4.124 4.273,5.250 2.875,5.250 Z"/>
                                    </svg>
                                </el-button>
                                <el-dropdown-menu slot="dropdown" class="sort-dropdown">
                                    <el-dropdown-item>
                                        <p style="font-weight: light !important; font-family:lato medium; font-size: 15px; line-height:3; color: #727d92; " @click="exteralSort('first_name', 'ascending')"> Sort by Client </p>
                                    </el-dropdown-item>
                                    <el-dropdown-item>
                                        <p style="font-weight: light !important; font-family:lato medium; font-size: 15px; line-height:3; color: #727d92; " @click="exteralSort('email', 'ascending')"> Sort by Email </p>
                                    </el-dropdown-item>
                                    <el-dropdown-item>
                                        <p style="font-weight: light !important; font-family:lato medium; font-size: 15px; line-height:3; color: #727d92; " @click="exteralSort('status', 'ascending')"> Sort by Status </p>
                                    </el-dropdown-item>
                                </el-dropdown-menu>  
                            </el-dropdown>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <div class="content-body">
                <section class="buzz-section">
                  <div class="buzz-table">
                    <div class="milestone-templates">
                      <div v-if="notEmpty">
                        <el-table :data="paginatedAllTemplate" stripe empty-text="No Data Found" v-loading="isProcessing" 
                            @sort-change="handleSortChange" :element-loading-text="loadingText" 
                            @selection-change="handleSelectionChange" style="width: 100%"
                            @cell-click="cellClick"
                            >
                            <el-table-column type="selection" width="60"></el-table-column>
                            <el-table-column prop="title" sortable label="Title"></el-table-column>
                            <el-table-column  sortable label="User">
                            <template slot-scope="scope">
                                {{ scope.row.user.first_name + " " + scope.row.user.last_name }}
                            </template>
                            </el-table-column>
                            <el-table-column sortable prop="created_at" label="Date"></el-table-column>
                            <el-table-column sortable prop="status" label="Status"></el-table-column>
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
                    <!-- <el-pagination
                        @size-change="handleSizeChange"
                        @current-change="handleCurrentChange"
                        :current-page.sync="currentPage"
                        :page-sizes="[10, 20, 50, 100]"
                        :page-size="currentSize"
                        layout="total, sizes, prev, pager, next"
                        :total="total">
                    </el-pagination> -->
                      </div>
                        <div v-else>
                            <empty title="Add New Template" modal="add-template" icon="/img/icons/empty/projects.svg"></empty>
                        </div>
                    </div>
                  </div>
                </section>
              </div>
            </div>  
            <add-form v-on:updated="updated"></add-form>
        </v-layout>  
    </section>
</template>

<script>
    var URL = '/api/milestones/'
    import Form from './Form.vue'
    export default {   
      components:{
        'add-form': Form
      },
      data(){
        return {
            paginatedAllTemplate: [],
            isProcessing: false,
            loadingText: 'Fetching datas ...',
            notEmpty: true
        }
      },
      mounted(){
        this.getTemplates();
      },
      methods:{
        renderHeader(h,{column,$index}){
            return h('img', { attrs: { src: '../../../img/icons/menu.svg'}  });
        },
        getTemplates() {
          this.isProcessing = true;
          axios.get(URL)
          .then (response => {
            this.isProcessing = false;
            this.paginatedAllTemplate = response.data.data;
            if(this.paginatedAllTemplate < 1){
                this.notEmpty = false;
            }
            else{
                this.notEmpty = true;							
            }
          }) .catch (error => {
            if (error.response.status == 401) {
              location.reload();
            } else {
                this.isProcessing = false;
                this.paginatedAllTemplate = [];
                this.notEmpty = false;
            }
          });
        },
        edit(data){
            this.$modal.show('add-template', { action: 'Update', data: data })
        },
        destroy(row){
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
              axios.delete(URL + row.id)
              .then(response => {
                swal({
                  title: 'Success!',
                  text: 'Templete is Deleted!',
                  type: 'success'
                }).then( function() {
                    vm.loadingText = 'Updating ...'
                    vm.getTemplates();
                });
                
              });
            }
          })
        },
        handleSortChange(){

        },
        handleSelectionChange(){

        },
        cellClick: function(row, col) {
            var a = col.id;
            if(a != 'el-table_1_column_6') {
                location = "/milestones/" + row.id; 
            }
        },
        renderHeader(h,{column,$index}){
            return h('img', { attrs: { src: '../../../img/icons/menu.svg'}  });
        },
        updated() {
          this.loadingText = 'Updating ...'
          this.getTemplates();
        }
      }
    }
</script>