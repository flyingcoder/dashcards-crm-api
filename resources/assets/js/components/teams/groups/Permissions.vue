<template>
    <modal name="permissions-modal" transition="nice-modal-fade" @before-open="beforeOpen">
        <section class="content permissions">
            <v-layout row wrap>
                <div class="buzz-modal-header"> Permissions - {{ title }} </div>
                <div class="buzz-scrollbar" id="buzz-scroll">
                    <div class="buzz-table">
                        <div class="groups">
                            <el-table :data="paginatedAllPermissions" stripe empty-text="No Data Found" v-loading="isProcessing" 
                                element-loading-text="Processing ..." style="width: 100%"
                                >
                                <el-table-column sortable label="Category">
                                    <template slot-scope="scope">
                                        <span> {{ scope.row.name}} </span>
                                    </template>
                                </el-table-column>
                                <el-table-column sortable label="View">
                                    <template slot-scope="scope">
                                        <span> {{ scope.row.slug.view}} </span>
                                    </template>
                                </el-table-column>
                                <el-table-column sortable label="Add">
                                    <template slot-scope="scope">
                                        <span> {{ scope.row.slug.create}} </span>
                                    </template>
                                </el-table-column>
                                <el-table-column sortable label="Update">
                                    <template slot-scope="scope">
                                        <span> {{ scope.row.slug.update}} </span>
                                    </template>
                                </el-table-column>
                                <el-table-column sortable label="Delete">
                                    <template slot-scope="scope">
                                        <span> {{ scope.row.slug.delete}} </span>
                                    </template>
                                </el-table-column>
                                <el-table-column sortable label="Permission level">
                                    <template slot-scope="scope">
                                        <span> {{ scope.row.slug.permission_level}} </span>
                                    </template>
                                </el-table-column>
                            </el-table>
                                <el-button @click="$modal.hide('permissions-modal')">Cancel</el-button>
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