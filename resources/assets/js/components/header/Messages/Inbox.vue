<template>
    <div>
        <el-table :data="messagesData" stripe empty-text="No Data Found" v-loading="isProcessing" 
            @sort-change="handleSortChange"  :element-loading-text="loadingText"
            @selection-change="handleSelectionChange" style="width: 100%">
                <el-table-column type="selection" width="60"></el-table-column>
                <el-table-column prop="client" label="Client" width="115">
                    <template slot-scope="scope">
                        <span>John Smith</span>
                    </template>
                </el-table-column>
                <el-table-column prop="subject" label="Subject" width="85">
                    <template slot-scope="scope">
                        <span>Website Redesign</span>
                    </template>
                </el-table-column>
                <el-table-column fixed="right" width="150" :render-header="renderHeader">
                    <template slot-scope="scope">
                        <el-button @click="edit(scope.row)">
                            <svg viewBox="0 0 250 250">
                                <path class="edit" d="M192 10l54 56c4,5 4,13 -1,18l-18 17c-5,5 -13,5 -17,0l-54 -56c-5,-5 -5,-13 0,-18l18 -17c5,-5 13,-5 18,0zm-140 202l40 -13 -39 -41 -16 38 15 16zm99 -152l43 45c8,8 7,21 -1,29l-80 77c-1,0 -92,30 -100,32 -2,1 -5,1 -7,0 -4,-2 -6,-7 -4,-12l40 -94 80 -77c8,-8 21,-8 29,0z"/>
                            </svg>
                        </el-button>
                        <span>7:45 am</span>
                    </template>
                </el-table-column>
        </el-table>
    </div>
</template>


<script>

    export default {   

      data () {
        return {
            testCount: '',
            isProcessing: true,
            multipleSelection: [],
            messagesData: [],
            getAll: 1,
            currentUserId: 0,
            notEmpty: true,
            loadingText: 'Fetching datas ...',
        }
      },

      mounted () {
        this.getAllMessages();
        this.sliceDate();
        this.progressCount();
      },
      methods: {
        renderHeader(h,{column,$index}){
            return h('img', { attrs: { src: '../../../img/icons/menu.svg'}  });
        },
        getAllMessages(){
            this.isProcessing = true;            
           var url = '/api/projects';

            axios.get(url)
                .then( response => {
                    this.isProcessing = false;
                    this.messagesData = response.data.data;
                    this.currentPage = response.data.current_page;
                    this.total = response.data.total;
                    this.sliceDate();
                    if(this.messagesData < 1){
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
                        this.messagesData = [];
                        this.notEmpty = false;
                    }
                })
           
        },
        sliceDate: function() {
            for(var x in this.messagesData) {
                this.messagesData[x].started_at = this.messagesData[x].started_at.split(' ')[0];
            }
        },
        progressCount: function() {
            var x = 0;
            for(x > this.messagesData.length; x++;) {
                for(var y in this.messagesData[x].tasks) {
                    if (this.messagesData[x].task[y].status == 'completed') {
                        this.testCount = x++;
                        console.log(x);
                    }
                }
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
                axios.delete('/projects/' + row.id + '/delete')
                .then(response => {
                    swal('Success!', 'Project is Deleted!', 'success');
                    self.getAllMessages();
                });
              }
          })
            
        },
        edit(data){
            this.$modal.show('add-project', { action: 'Update', data: data })
        },
        updated() {
          this.loadingText = 'Updating ...'
          this.getAllMessages();
        }
      }
    }
</script>