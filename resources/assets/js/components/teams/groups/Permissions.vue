<template>
    <modal name="permissions-modal" class="permissions-modal" transition="nice-modal-fade" @before-open="beforeOpen">
        <section class="content permissions">
            <v-layout row wrap>
                <div class="buzz-modal-header"> Permissions - {{ title }} </div>
                <div class="buzz-scrollbar" id="buzz-scroll">
                    <div class="buzz-table">
                        <div class="groups">
                            <el-table :data="paginatedAllPermissions" stripe empty-text="No Data Found" v-loading="isProcessing" 
                                element-loading-text="Processing ..." style="width: 100%"
                                >
                                <el-table-column label="Category">
                                    <template slot-scope="scope">
                                        <span> {{ scope.row.name}} </span>
                                    </template>
                                </el-table-column>
                                <el-table-column label="View">
                                    <template slot-scope="scope">
                                        <span> {{ scope.row.slug.view}} </span>
                                    </template>
                                </el-table-column>
                                <el-table-column label="Add">
                                    <template slot-scope="scope">
                                        <span> {{ scope.row.slug.create}} </span>
                                    </template>
                                </el-table-column>
                                <el-table-column label="Update">
                                    <template slot-scope="scope">
                                        <span> {{ scope.row.slug.update}} </span>
                                    </template>
                                </el-table-column>
                                <el-table-column label="Delete">
                                    <template slot-scope="scope">
                                        <span> {{ scope.row.slug.delete}} </span>
                                    </template>
                                </el-table-column>
                                <el-table-column label="Permission level">
                                    <template slot-scope="scope">
                                        <span> {{ scope.row.slug.permission_level}} </span>
                                    </template>
                                </el-table-column>
                            </el-table>
                            <el-button class="modal-close-btn" @click="$modal.hide('permissions-modal')">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="18px" height="17px">
                                    <path fill-rule="evenodd"  fill="rgb(102, 115, 129)"
                                    d="M17.324,16.511 C16.889,16.940 16.183,16.940 15.748,16.511 L9.191,10.039 L2.635,16.511 C2.200,16.940 1.494,16.940 1.058,16.511 C0.623,16.080 0.623,15.384 1.058,14.954 L7.614,8.484 L1.058,2.013 C0.623,1.583 0.623,0.886 1.058,0.456 C1.494,0.026 2.200,0.026 2.635,0.456 L9.191,6.927 L15.748,0.456 C16.183,0.026 16.889,0.026 17.324,0.456 C17.759,0.886 17.759,1.583 17.324,2.013 L10.768,8.484 L17.324,14.954 C17.759,15.384 17.759,16.080 17.324,16.511 Z"/>
                                </svg>
                            </el-button>
                        </div>
                    </div>
                </div>
            </v-layout>
        </section>
    </modal>
</template>

<script>
    export default {
    data () {
        return {
            title: '',
            isProcessing: false,
            paginatedAllPermissions: [],
        }
      },
       
    methods: {
        beforeOpen (event) {
            var vm = this;
            this.title = event.params.data.name;
            this.id = event.params.data.id;
            console.log(this.title);
            console.log(this.id);
            // axios.get('/api/groups/permissions/'+this.id)
            axios.get('/api/groups/'+this.id+'/permissions')
            .then( response => {
                this.paginatedAllPermissions = response.data.data;
                this.currentPage = response.data.current_page;
                this.total = response.data.total;
            })
        },
        formatDate (value, fmt = 'D MMM YYYY') {
            return (value == null)
            ? ''
            : moment(value, 'YYYY-MM-DD').format(fmt)
        },
        onCellClicked (data, field, event) {
            console.log(data)
            console.log('cellClicked: ', field.name)
        },
      }
    }
</script>