<template>
    <div class="buzz-table">
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
                <el-table-column class="text-left" fixed="right" :render-header="renderHeader">
                    <template slot-scope="scope">
                        <el-button @click="permission(scope.row)" class="permission-btn" v-if="scope.row.name != 'Administrator'">
                            <svg>
                                <path d="M19.341,1.679 C18.235,0.558 16.903,-0.002 15.346,-0.002 C13.790,-0.002 12.458,0.558 11.352,1.679 C10.245,2.800 9.692,4.149 9.692,5.726 L9.692,8.182 L1.211,8.182 C0.874,8.182 0.588,8.301 0.353,8.540 C0.117,8.778 -0.001,9.068 -0.001,9.409 L-0.001,16.775 C-0.001,17.116 0.117,17.406 0.353,17.644 C0.588,17.883 0.874,18.002 1.211,18.002 L13.327,18.002 C13.664,18.002 13.950,17.883 14.185,17.644 C14.421,17.405 14.539,17.116 14.539,16.775 L14.539,9.409 C14.539,9.068 14.421,8.778 14.185,8.540 C13.950,8.301 13.664,8.182 13.327,8.182 L12.116,8.182 L12.116,5.726 C12.116,4.823 12.431,4.051 13.062,3.412 C13.693,2.773 14.455,2.453 15.346,2.453 C16.238,2.453 17.000,2.773 17.631,3.412 C18.262,4.051 18.577,4.823 18.577,5.726 L18.577,9.000 C18.577,9.222 18.657,9.413 18.817,9.575 C18.977,9.737 19.166,9.818 19.385,9.818 L20.193,9.818 C20.412,9.818 20.601,9.737 20.761,9.575 C20.921,9.413 21.001,9.222 21.001,9.000 L21.001,5.726 C21.001,4.149 20.447,2.800 19.341,1.679 Z"/>
                            </svg>
                            <span>Permissions</span>
                        </el-button>
                        <el-button @click="migrate(scope.row)" class="migrate-btn" v-if="scope.row.name != 'Administrator'">
                            <svg>
                                <path d="M15.637,12.391 L15.764,8.774 L9.359,8.548 L9.538,3.400 L15.943,3.626 L16.069,0.009 L21.999,6.417 L15.637,12.391 ZM0.007,15.595 L5.937,22.002 L6.063,18.386 L12.468,18.612 L12.647,13.464 L6.243,13.237 L6.369,9.621 L0.007,15.595 Z"/>
                            </svg>
                            <span>Migrate Members</span>
                        </el-button>
                        <el-button @click="members(scope.row)" class="members-btn">
                            <svg>
                                <image x="0px" y="0px" width="16px" height="20px"  xlink:href="data:img/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAUCAQAAAAua3X8AAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAAmJLR0QA/4ePzL8AAAAHdElNRQfiBRwVFxhJdQolAAABA0lEQVQoz32OPS+DYRhGz/s2VIVKE0ljKA0GMWkYiaSD0e5XGC1Wf8Gqf0B8TCKxSCRmESzoIo2QRtuFahxDP1/Rnme5c18nz30hnbfsoSVfPTHf3XbjdYvWVa377NZfIeupvVw530xCmsyxQS8L5JtDW0gSjwgJUlHhnXJE+KDYmlodUu5HOpybiJYMnLbQiY/aFSWw++0im6wRcM0xN52tiKNue2vNimUrVq16764T7RNpC376E+nw45dnZgTj7vjtfzQ8MIM5n+zHo3shM2TpxxSrIeMEfYVhkiGDEAcLQEhsQBpjJOSNWl+hyl1gyCQ5VlhiljRjDNGgSokHLrng5Rc6rfSFLNb4hAAAAABJRU5ErkJggg==" />
                            </svg>
                            <span>Group Members</span>
                        </el-button>
                        <el-button @click="edit(scope.row)" class="edit-btn" v-if="scope.row.name != 'Administrator'">
                            <svg viewBox="0 0 250 250">
                                <path d="M192 10l54 56c4,5 4,13 -1,18l-18 17c-5,5 -13,5 -17,0l-54 -56c-5,-5 -5,-13 0,-18l18 -17c5,-5 13,-5 18,0zm-140 202l40 -13 -39 -41 -16 38 15 16zm99 -152l43 45c8,8 7,21 -1,29l-80 77c-1,0 -92,30 -100,32 -2,1 -5,1 -7,0 -4,-2 -6,-7 -4,-12l40 -94 80 -77c8,-8 21,-8 29,0z"/>
                            </svg>
                            <span>Edit Settings</span>
                        </el-button>
                        <el-button @click="destroy(scope.row)" class="delete-btn" v-if="scope.row.name != 'Administrator'">
                            <svg viewBox="0 0 250 250">
                                <path d="M61 83l129 0c6,0 11,5 10,10l-3 146c-1,6 -5,11 -11,11l-121 0c-6,0 -11,-5 -11,-11l-4 -146c0,-5 5,-10 11,-10zm37 -83l54 0c5,0 9,2 12,5l0 0c3,3 4,7 4,11l0 10 33 0c6,0 11,4 11,10l0 23c0,6 -5,11 -11,11l-152 0c-6,0 -11,-5 -11,-11l0 -23c0,-6 5,-10 11,-10l33 0 0 -10c0,-4 2,-8 5,-11 3,-3 7,-5 11,-5zm1 26l53 0 0 -9 -53 0 0 9zm-5 83l0 0c5,0 9,4 9,10l0 95c0,5 -4,9 -9,9l0 0c-6,0 -10,-4 -10,-9l0 -95c0,-6 4,-10 10,-10zm64 0l0 0c5,0 9,4 9,10l0 95c0,5 -4,9 -9,9l0 0c-5,0 -10,-4 -10,-9l0 -95c0,-6 5,-10 10,-10zm-32 0l0 0c5,0 9,4 9,10l0 95c0,5 -4,9 -9,9l0 0c-5,0 -10,-4 -10,-9l0 -95c0,-6 5,-10 10,-10z"/>
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