<template>
    <section class="content clients">
        <div class="content-header">
             <div class="row">
                <div class="col-md-6">
                    <div class="page-title">
                        <h1> <span class="prev-path"> Dashboard </span> &nbsp; <img src="img/icons/ArrowRight.svg"> &nbsp; <span class="current"> Clients </span> </h1>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="head-page-option">
                        <ul class="nav nav-tabs">
                            <li class="add-button">
                                <span> ADD NEW </span>
                                <button>
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="20px" height="20px">
                                        <path fill-rule="evenodd"  fill="rgb(255, 255, 255)"
                                        d="M18.852,10.789 L11.590,10.789 L11.590,19.039 C11.590,19.444 11.193,19.773 10.703,19.773 C10.212,19.773 9.815,19.444 9.815,19.039 L9.815,10.789 L1.663,10.789 C1.262,10.789 0.937,10.387 0.937,9.892 C0.937,9.395 1.262,8.993 1.663,8.993 L9.815,8.993 L9.815,1.645 C9.815,1.240 10.212,0.911 10.703,0.911 C11.193,0.911 11.590,1.240 11.590,1.645 L11.590,8.993 L18.852,8.993 C19.252,8.993 19.577,9.395 19.577,9.892 C19.577,10.387 19.252,10.789 18.852,10.789 Z"/>
                                    </svg>
                                </button>
                            </li>
                            <li class="sort">
                                <button data-toggle="dropdown" class="dropdown-toggle">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="22px" height="6px">
                                        <path fill-rule="evenodd"
                                        d="M19.062,5.250 C17.665,5.250 16.531,4.124 16.531,2.734 C16.531,1.345 17.665,0.219 19.062,0.219 C20.460,0.219 21.594,1.345 21.594,2.734 C21.594,4.124 20.460,5.250 19.062,5.250 ZM10.953,5.250 C9.564,5.250 8.437,4.124 8.437,2.734 C8.437,1.345 9.564,0.219 10.953,0.219 C12.342,0.219 13.469,1.345 13.469,2.734 C13.469,4.124 12.342,5.250 10.953,5.250 ZM2.875,5.250 C1.477,5.250 0.344,4.124 0.344,2.734 C0.344,1.345 1.477,0.219 2.875,0.219 C4.273,0.219 5.406,1.345 5.406,2.734 C5.406,4.124 4.273,5.250 2.875,5.250 Z"/>
                                    </svg>
                                </button>
                                <ul class="dropdown-menu sort-dropdown">
                                    <li>
                                        <a href="#"> Sort by Client </a>
                                    </li>
                                    <li>
                                        <a href="#"> Sort by Company </a>
                                    </li>
                                    <li>
                                        <a href="#"> Sort by Project </a>
                                    </li>
                                    <li>
                                        <a href="#"> Sort by Date </a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#grid-view" data-toggle="tab">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="18px" height="17px">
                                        <path fill-rule="evenodd"  fill="rgb(102, 115, 129)"
                                        d="M17.324,16.511 C16.889,16.940 16.183,16.940 15.748,16.511 L9.191,10.039 L2.635,16.511 C2.200,16.940 1.494,16.940 1.058,16.511 C0.623,16.080 0.623,15.384 1.058,14.954 L7.614,8.484 L1.058,2.013 C0.623,1.583 0.623,0.886 1.058,0.456 C1.494,0.026 2.200,0.026 2.635,0.456 L9.191,6.927 L15.748,0.456 C16.183,0.026 16.889,0.026 17.324,0.456 C17.759,0.886 17.759,1.583 17.324,2.013 L10.768,8.484 L17.324,14.954 C17.759,15.384 17.759,16.080 17.324,16.511 Z"/>
                                    </svg>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <section class="buzz-section">
                <div class="buzz-table">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#all-client" data-toggle="tab"> All Clients </a>
                        </li>
                        <li>
                            <a href="#m-client" data-toggle="tab"> My Clients </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <all-clients></all-clients>
                        <my-clients></my-clients>
                    </div>
                </div>
            </section>
        </div>
    </section>
</template>

<script>
    import PageHeader from '../page-header.vue';
    import AllClients from './all-clients.vue';
    import MyClients from './my-clients.vue';

    export default {   

        components: {
          'page-header': PageHeader,
          'all-clients': AllClients,
          'my-clients': MyClients,
      },

      data () {
        return {
        isProcessing: false,
        multipleSelection: [],
        currentPage: 1,
        currentSize: 10,
        total : 1,
        paginatedMyProjects: [],
        paginatedAllProjects: [],
        }
      },

      mounted () {
        this.getMyProjects();
        this.getAllProjects();

      },

      methods: {
        getMyProjects(){
            axios.get('api/projects/mine')
                 .then( response => {
                    this.paginatedMyProjects = response.data.data;
                    this.currentPage = response.data.current_page;
                    this.total = response.data.total;
                 })
        },
        getAllProjects(){
            axios.get('api/projects')
            .then( response => {
                this.paginatedMyProjects = response.data;
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
        rowClick(row, event, col){
            location = "/projects/" + row.id;
        }
      }

    }
</script>