<template>
    <div class="messages-table">
        <!-- <div v-if="paginatedAllMessages.length >= 1"> -->
        <div>
            <el-table :data="paginatedAllMessages" stripe empty-text="No Data Found" v-loading="isProcessing" 
                @sort-change="handleSortChange" element-loading-text="Processing ..." 
                @selection-change="handleSelectionChange" style="width: 100%"
                @row-click="rowClick" @cell-click="cellClick">

                <el-table-column @cell-click="rowClick" sortable type="selection" width="60"></el-table-column>
                <el-table-column sortable label="Client" width="250">
                    <template slot-scope="scope">
                        <span> 
                             <img class="user-image" :src="asset + '' + scope.row.image_url">
                            {{ scope.row.first_name + " " + scope.row.last_name }} 
                        </span>
                    </template>
                </el-table-column>
                <el-table-column prop="subject" label="Subject" width="300">
                    <template slot-scope="scope">
                        <img :src="scope.row.client_image_url" class="user-image">
                    </template>
                </el-table-column>
                <el-table-column fixed="right" :render-header="renderHeader">
                    <template slot-scope="scope">
                        <a href="#" @click="scope.row">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                width="16px" height="16px">
                                <path fill-rule="evenodd"  fill="rgb(212, 214, 224)"
                                d="M9.896,3.854 C9.498,3.455 8.852,3.455 8.453,3.854 L8.093,4.216 L3.043,9.276 L3.044,9.277 L2.885,9.437 C2.885,9.437 2.377,9.948 1.232,13.651 C1.224,13.677 1.216,13.702 1.208,13.729 C1.187,13.795 1.166,13.862 1.146,13.931 C1.127,13.991 1.108,14.053 1.090,14.115 C1.074,14.167 1.058,14.219 1.042,14.272 C1.006,14.393 0.969,14.517 0.932,14.644 C0.849,14.924 0.648,15.554 0.876,15.783 C1.095,16.003 1.731,15.809 2.010,15.726 C2.136,15.689 2.258,15.652 2.379,15.616 C2.434,15.599 2.488,15.583 2.542,15.566 C2.601,15.548 2.659,15.531 2.715,15.513 C2.787,15.491 2.858,15.469 2.928,15.448 C2.948,15.441 2.969,15.435 2.989,15.428 C6.509,14.332 7.140,13.817 7.193,13.770 C7.193,13.769 7.193,13.769 7.194,13.769 C7.196,13.767 7.197,13.766 7.197,13.766 L7.360,13.602 L7.371,13.613 L12.421,8.553 L12.421,8.553 L12.782,8.191 C13.180,7.792 13.180,7.145 12.782,6.746 L9.896,3.854 ZM6.616,13.112 C6.612,13.115 6.606,13.119 6.600,13.123 C6.596,13.125 6.592,13.128 6.588,13.130 C6.583,13.133 6.579,13.136 6.574,13.139 C6.569,13.142 6.565,13.144 6.560,13.147 C6.392,13.248 5.899,13.508 4.702,13.941 C4.563,13.992 4.410,14.046 4.251,14.101 L2.550,12.397 C2.605,12.236 2.659,12.082 2.710,11.941 C3.142,10.738 3.401,10.243 3.501,10.075 C3.504,10.071 3.506,10.068 3.508,10.064 C3.512,10.058 3.515,10.053 3.518,10.048 C3.520,10.044 3.523,10.040 3.525,10.037 C3.529,10.030 3.533,10.024 3.536,10.020 L3.660,9.895 L6.744,12.984 L6.616,13.112 ZM15.668,3.854 L12.782,0.962 C12.383,0.563 11.737,0.563 11.339,0.962 L10.617,1.685 C10.219,2.085 10.219,2.732 10.617,3.131 L13.503,6.023 C13.902,6.422 14.548,6.422 14.946,6.023 L15.668,5.300 C16.066,4.901 16.066,4.253 15.668,3.854 Z"/>
                            </svg>
                        </a>
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
        <!-- <div v-else> 
            <empty-message></empty-message>
        </div> -->
    </div>
</template>

<script>
    import EmptyMessage from './EmptyMessage.vue'

    export default {
        components: {
            'empty-message': EmptyMessage,
        },  
      data () {
        return {
        testCount: '',
        isProcessing: false,
        multipleSelection: [],
        currentPage: 1,
        currentSize: 10,
        total : 1,
        paginatedAllMessages: [],
        }
      },

      mounted () {

      },
      methods: {
        renderHeader(h,{column,$index}){
            return h('img', { attrs: { src: '../../../img/icons/menu.svg'}  });
        },
        getAllMessages(){
            axios.get('api/messages')
            .then( response => {
                this.paginatedAllMessages = response.data.data;
                this.currentPage = response.data.current_page;
                this.total = response.data.total;
                this.sliceDate();
            })
        },
        sliceDate: function() {
            for(var x in this.paginatedAllMessages) {
                this.paginatedAllMessages[x].started_at = this.paginatedAllMessages[x].started_at.split(' ')[0];
            }
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
                axios.delete('/messages/' + row.id + '/delete')
                .then(response => {
                    swal('Success!', 'Message is Deleted!', 'success');
                    self.getAllMessages();
                });
              }
          })
            
        },
      }
    }
</script>