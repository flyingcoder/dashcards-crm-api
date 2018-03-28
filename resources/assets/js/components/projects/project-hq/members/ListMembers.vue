<template>
    <div class="tab-pane fade" id="list-view">
        <div v-if="members.length >= 1"> 
            <el-table :data="members" stripe empty-text="No Data Found" v-loading="isProcessing" 
            @sort-change="handleSortChange" element-loading-text="Processing ..." 
            @selection-change="handleSelectionChange" style="width: 100%"
            >
                <el-table-column sortable type="selection" width="60"></el-table-column>
                <el-table-column sortable label="Member" width="250">
                    <template slot-scope="scope">
                        <span> 
                             <img class="user-image" :src="asset + '' + scope.row.image_url">
                            {{ scope.row.first_name + " " + scope.row.last_name }} 
                        </span>
                    </template>
                </el-table-column>
                <el-table-column label="Position" width="150">
                    <template slot-scope="scope">
                        <span> {{ scope.row.pivot.role }} </span>
                    </template>
                </el-table-column>
                <el-table-column prop="member_location" label="Location"  width="150"></el-table-column>
                <el-table-column sortable prop="total_hours" label="Total Hours" width="130"></el-table-column>
                <el-table-column sortable prop="project_assigned" label="Project Assigned" width="150"></el-table-column>
                <el-table-column fixed="right">
                    <template slot-scope="scope">
                        <a href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                width="12px" height="17px">
                                <path fill-rule="evenodd"  fill="rgb(212, 214, 224)"
                                d="M11.348,5.027 L0.975,5.027 C0.692,5.027 0.463,4.792 0.463,4.501 L0.463,2.882 C0.463,2.591 0.692,2.355 0.975,2.355 L3.777,2.355 L3.777,1.262 C3.777,0.971 4.006,0.736 4.288,0.736 L8.035,0.736 C8.317,0.736 8.547,0.971 8.547,1.262 L8.547,2.355 L11.349,2.355 C11.631,2.355 11.860,2.591 11.860,2.882 L11.860,4.501 C11.860,4.792 11.631,5.027 11.348,5.027 ZM7.523,1.789 L4.800,1.789 L4.800,2.355 L7.523,2.355 L7.523,1.789 ZM10.725,16.377 C10.714,16.658 10.488,16.881 10.214,16.881 L2.109,16.881 C1.835,16.881 1.609,16.658 1.598,16.377 L1.174,6.080 L11.150,6.080 L10.725,16.377 ZM4.615,8.221 C4.615,7.931 4.386,7.695 4.104,7.695 C3.821,7.695 3.592,7.931 3.592,8.221 L3.592,14.740 C3.592,15.031 3.821,15.266 4.104,15.266 C4.386,15.266 4.615,15.031 4.615,14.740 L4.615,8.221 ZM6.673,8.221 C6.673,7.931 6.444,7.695 6.162,7.695 C5.879,7.695 5.650,7.931 5.650,8.221 L5.650,14.740 C5.650,15.031 5.879,15.266 6.162,15.266 C6.444,15.266 6.673,15.031 6.673,14.740 L6.673,8.221 ZM8.731,8.221 C8.731,7.931 8.502,7.695 8.219,7.695 C7.937,7.695 7.708,7.931 7.708,8.221 L7.708,14.740 C7.708,15.031 7.937,15.266 8.219,15.266 C8.502,15.266 8.731,15.031 8.731,14.740 L8.731,8.221 Z"/>
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
        <div v-else> 
            Empty Table
        </div> 
    </div>
</template>


<script>
    export default {
        props:['projectId','asset'],
        data(){
            return {
                isProcessing: false,
                multipleSelection: [],
                currentPage: 1,
                currentSize: 10,
                total : 1,
                members: [],
            }
        },
        methods: {
            getMembers(){
                axios.get('/api/projects/' + this.projectId + '/members')
                .then( response => {
                    this.members = response.data.data;
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
        },
        mounted(){
            this.getMembers();
        }
    }
</script>
