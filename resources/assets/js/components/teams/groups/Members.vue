<template>
    <modal name="members-modal" transition="nice-modal-fade" @before-open="beforeOpen">
        <section class="content members">
            <v-layout row wrap>
                <div class="buzz-modal-header"> Groups - {{ title }} </div>
                <div class="buzz-scrollbar" id="buzz-scroll">
                    <div class="buzz-table">
                        <div class="groups">
                            <el-table :data="paginatedAllMembers" stripe empty-text="No Data Found" v-loading="isProcessing" 
                                element-loading-text="Processing ..." style="width: 100%"
                                >
                                <el-table-column label="Full Name">
                                    <template slot-scope="scope">
                                        <span> {{ scope.row.first_name + " " + scope.row.last_name }} </span>
                                    </template>
                                </el-table-column>
                                <el-table-column label="Email">
                                    <template slot-scope="scope">
                                        <span> {{ scope.row.email}} </span>
                                    </template>
                                </el-table-column>
                                <el-table-column label="Job Title">
                                    <template slot-scope="scope">
                                        <span> {{ scope.row.job_title}} </span>
                                    </template>
                                </el-table-column>
                            </el-table>
                                <el-button @click="$modal.hide('members-modal')">Cancel</el-button>
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
            paginatedAllMembers: [],
        }
      },
       
    methods: {
        beforeOpen (event) {
            var vm = this;
            this.title = event.params.data.name;
            this.id = event.params.data.id;
            console.log(this.title);
            console.log(this.id);
            axios.get('/api/groups/'+this.id+'/members')
            .then( response => {
                this.paginatedAllMembers = response.data.data;
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