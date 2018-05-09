 <template>
    <section class="content db-backup">
        <v-layout row wrap>
            <div class="content-header col-md-6">
                <div class="page-title">
                    <h1> Database Backup </h1>
                </div>
            </div>
            <div class="col-md-6">
                <div class="head-page-option">
                    <ul class="nav nav-tabs">
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
                                        <a href="#"> Sort by Name </a>
                                    </el-dropdown-item>
                                    <el-dropdown-item>
                                        <a href="#"> Sort by Size </a>
                                    </el-dropdown-item>
                                    <el-dropdown-item>
                                        <a href="#"> Sort by Date </a>
                                    </el-dropdown-item>
                                    <el-dropdown-item>
                                        <a href="#"> Back Up Database </a>
                                    </el-dropdown-item>
                                </el-dropdown-menu>  
                            </el-dropdown>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-12">
                <div class="content-body">
                    <section class="buzz-section">
                        <div class="db-loader">
                            <vue-loading type="spiningDubbles" color="#3b589e" :size="{ width: '100px', height: '100px' }"></vue-loading>   
                            <h3> Please wait while your database is being back up... </h3>
                        </div>
                        
                        <el-table :data="tableDB" style="width: 100%">
                            <el-table-column label="Filename" width="170">
                                <template slot-scope="scope">
                                    <span>{{ scope.row.fileName }}</span>
                                </template>
                            </el-table-column>
                            <el-table-column label="File Size" width="160">
                                <template slot-scope="scope">
                                    <span>{{ scope.row.fileSize }}</span>
                                </template>
                            </el-table-column>
                            <el-table-column label="Date Created" width="230">
                                <template slot-scope="scope">
                                    <span>{{ scope.row.dateCreated }}</span>
                                </template>
                            </el-table-column>
                            <el-table-column label="Actions">
                                <template slot-scope="scope">
                                    <el-button class="action-edit">
                                        <img src="/img/action/download.svg" width="23px" height="23px">
                                        Download File
                                    </el-button>
                                    <el-popover trigger="hover" placement="top">
                                        <p class="popup-text-department">Do you really want to delete <br> this file?</p>
                                        <div class="popup-deparment-button">
                                            <el-button type="text" @click="cancelDelete = false">No</el-button>
                                            <el-button type="primary" @click="confirmDelete = false">Yes</el-button>
                                        </div>
                                        <el-button slot="reference" class="action-delete">
                                            <img src="/img/action/delete.svg" width="17px" height="23px">
                                            Delete This File
                                        </el-button>
                                    </el-popover>
                                </template>
                            </el-table-column>
                        </el-table>
                    </section>
                </div>
            </div>
        </v-layout>
    </section>
</template>

<script>

    import vueLoading from 'vue-loading-template';

    export default {
        components: {
            vueLoading
        },
        data() {
            return {
                loading2: true,
                confirmDelete: false,
                tableDB: [
                    {
                    fileName: 'filename.gz',
                    fileSize: '10,514kb',
                    dateCreated: '02-06-2014 [14:02:31]',
                    }, 
                    {
                    fileName: 'filename.gz',
                    fileSize: '10,514kb',
                    dateCreated: '02-06-2014 [14:02:31]',
                    }, 
                    {
                    fileName: 'filename.gz',
                    fileSize: '10,514kb',
                    dateCreated: '02-06-2014 [14:02:31]',
                    }, 
                ]
            }
        },
        methods: {
        handleEdit(index, row) {
            console.log(index, row);
        },
        handleDelete(index, row) {
            console.log(index, row);
        }
        }
    }
</script>