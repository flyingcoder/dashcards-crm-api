<template>
    <div class="buzz-table">
        <div class="groups">
            <div v-if="paginatedAllGroups.length >= 1">
                <el-table :data="paginatedAllGroups" stripe empty-text="No Data Found" v-loading="isProcessing" 
                    @sort-change="handleSortChange" element-loading-text="Processing ..." 
                    @selection-change="handleSelectionChange" style="width: 100%"
                    @cell-click="cellClick"
                    >
                    <el-table-column sortable label="ID" width="70">
                        <template slot-scope="scope">
                            <span> {{ scope.row.id}} </span>
                        </template>
                    </el-table-column>
                    <el-table-column sortable label="Group Name" width="150">
                        <template slot-scope="scope">
                            <span> {{ scope.row.name}} </span>
                        </template>
                    </el-table-column>
                    <el-table-column sortable label="Team" width="80">
                        <template slot-scope="scope">
                            <span> {{ scope.row.slug}} </span>
                        </template>
                    </el-table-column>
                    <el-table-column fixed="right" :render-header="renderHeader">
                        <template slot-scope="scope">
                            <el-button @click="permission(scope.row)" class="permission-btn" v-if="scope.row.name != 'Administrator'">
                                 <svg viewBox="0 0 250 250">
                                    <path class="edit" d="M192 10l54 56c4,5 4,13 -1,18l-18 17c-5,5 -13,5 -17,0l-54 -56c-5,-5 -5,-13 0,-18l18 -17c5,-5 13,-5 18,0zm-140 202l40 -13 -39 -41 -16 38 15 16zm99 -152l43 45c8,8 7,21 -1,29l-80 77c-1,0 -92,30 -100,32 -2,1 -5,1 -7,0 -4,-2 -6,-7 -4,-12l40 -94 80 -77c8,-8 21,-8 29,0z"/>
                                </svg>
                                <span>Permissions</span>
                            </el-button>
                            <el-button @click="migrate(scope.row)" class="migrate-btn" v-if="scope.row.name != 'Administrator'">
                                 <svg viewBox="0 0 250 250">
                                    <path class="edit" d="M192 10l54 56c4,5 4,13 -1,18l-18 17c-5,5 -13,5 -17,0l-54 -56c-5,-5 -5,-13 0,-18l18 -17c5,-5 13,-5 18,0zm-140 202l40 -13 -39 -41 -16 38 15 16zm99 -152l43 45c8,8 7,21 -1,29l-80 77c-1,0 -92,30 -100,32 -2,1 -5,1 -7,0 -4,-2 -6,-7 -4,-12l40 -94 80 -77c8,-8 21,-8 29,0z"/>
                                </svg>
                                <span>Migrate Members</span>
                            </el-button>
                            <el-button @click="members(scope.row)" class="members-btn">
                                 <svg viewBox="0 0 250 250">
                                    <path class="edit" d="M192 10l54 56c4,5 4,13 -1,18l-18 17c-5,5 -13,5 -17,0l-54 -56c-5,-5 -5,-13 0,-18l18 -17c5,-5 13,-5 18,0zm-140 202l40 -13 -39 -41 -16 38 15 16zm99 -152l43 45c8,8 7,21 -1,29l-80 77c-1,0 -92,30 -100,32 -2,1 -5,1 -7,0 -4,-2 -6,-7 -4,-12l40 -94 80 -77c8,-8 21,-8 29,0z"/>
                                </svg>
                                <span>Group Members</span>
                            </el-button>
                            <el-button @click="edit(scope.row)" class="edit-btn" v-if="scope.row.name != 'Administrator'">
                                 <svg viewBox="0 0 250 250">
                                    <path class="edit" d="M192 10l54 56c4,5 4,13 -1,18l-18 17c-5,5 -13,5 -17,0l-54 -56c-5,-5 -5,-13 0,-18l18 -17c5,-5 13,-5 18,0zm-140 202l40 -13 -39 -41 -16 38 15 16zm99 -152l43 45c8,8 7,21 -1,29l-80 77c-1,0 -92,30 -100,32 -2,1 -5,1 -7,0 -4,-2 -6,-7 -4,-12l40 -94 80 -77c8,-8 21,-8 29,0z"/>
                                </svg>
                                <span>Edit Settings</span>
                            </el-button>
                            <el-button @click="destroy(scope.row)" class="delete-btn" v-if="scope.row.name != 'Administrator'">
                                <svg viewBox="0 0 250 250">
                                    <path class="delete" d="M61 83l129 0c6,0 11,5 10,10l-3 146c-1,6 -5,11 -11,11l-121 0c-6,0 -11,-5 -11,-11l-4 -146c0,-5 5,-10 11,-10zm37 -83l54 0c5,0 9,2 12,5l0 0c3,3 4,7 4,11l0 10 33 0c6,0 11,4 11,10l0 23c0,6 -5,11 -11,11l-152 0c-6,0 -11,-5 -11,-11l0 -23c0,-6 5,-10 11,-10l33 0 0 -10c0,-4 2,-8 5,-11 3,-3 7,-5 11,-5zm1 26l53 0 0 -9 -53 0 0 9zm-5 83l0 0c5,0 9,4 9,10l0 95c0,5 -4,9 -9,9l0 0c-6,0 -10,-4 -10,-9l0 -95c0,-6 4,-10 10,-10zm64 0l0 0c5,0 9,4 9,10l0 95c0,5 -4,9 -9,9l0 0c-5,0 -10,-4 -10,-9l0 -95c0,-6 5,-10 10,-10zm-32 0l0 0c5,0 9,4 9,10l0 95c0,5 -4,9 -9,9l0 0c-5,0 -10,-4 -10,-9l0 -95c0,-6 5,-10 10,-10z"/>
                                </svg>
                                <span>Delete Group</span>
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
                <empty-groups></empty-groups>
            </div>
        </div>
    </div>
</template>

<script>
    import EmptyGroups from './EmptyGroups.vue';

    export default { 
        components: {
          'empty-groups': EmptyGroups,
        },  
       data () {
        return {
            isProcessing: false,
            multipleSelection: [],
            currentPage: 1,
            currentSize: 10,
            total : 1,
            paginatedAllGroups: [],
        }
      },

      mounted () {
        this.getAllGroups();
      },
        methods: {
            exteralSort: function(prop, order) {
                var col = [{
                    prop: prop,
                    order: order,
                }];
                this.$refs.allGroups.handleSortChange(col);
            },
            renderHeader(h,{column,$index}){
                return h('img', { attrs: { src: '../../../img/icons/menu.svg'}  });
            },
            getAllGroups(){
                axios.get('/api/groups')
                .then( response => {
                    this.paginatedAllGroups = response.data.data;
                    this.currentPage = response.data.current_page;
                    this.total = response.data.total;
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
            permission (data){
                this.$modal.show('permissions-modal', {data: data})
            },
            migrate (data){
                this.$modal.show('migrate-modal', {data: data})
            },
            members (data){
                this.$modal.show('members-modal', {data: data})
            },
            edit(data){
                this.$modal.show('add-groups', { action: 'Update', data: data })
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
                    axios.delete('/api/groups/' + row.id)
                    .then(response => {
                        self.getAllGroups();
                        swal('Success!', 'Client is Deleted!', 'success');
                    });
                  }
              })
                
            },
        }
    }
</script>