<template>
    <section class="content projects">
        <div class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <div class="page-title">
                        <h1> <span class="prev-path"> Dashboard </span> &nbsp; <img src="img/icons/ArrowRight.svg"> &nbsp; <span class="current"> Projects </span> </h1>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="head-page-option">
                        <ul class="nav nav-tabs">
                            <AddProject></AddProject>
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
                                            <a href="#"> Sort by Service </a>
                                        </el-dropdown-item>
                                        <el-dropdown-item>
                                            <a href="#"> Sort by Date </a>
                                        </el-dropdown-item>
                                        <el-dropdown-item>
                                            <a href="#"> Sort by Progress </a>
                                        </el-dropdown-item>
                                        <el-dropdown-item>
                                            <a href="#"> Sort by Status </a>
                                        </el-dropdown-item>
                                    </el-dropdown-menu>  
                                </el-dropdown>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <section class="buzz-section">
                <div class="buzz-table">
                    <el-tabs type="border-card">
                        <el-tab-pane label="All Projects">
                            <AllProjects></AllProjects>
                        </el-tab-pane>
                        <el-tab-pane label="My Projects">
                            <MyProjects></MyProjects>
                        </el-tab-pane>
                    </el-tabs>
                    <!-- <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#all-project" data-toggle="tab"> All Project </a>
                        </li>
                        <li>
                            <a href="#my-project" data-toggle="tab"> My Project </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <all-projects></all-projects>
                        <my-projects></my-projects>
                    </div> -->
                </div>
            </section>
        </div>
    </section>
</template>

<script>
    import AddProject from './AddProject.vue';
    import AllProjects from './AllProjects.vue';
    import MyProjects from './MyProjects.vue';
    import SelectedProject from './SelectedProject.vue';

    export default {
        components: {
          'AllProjects': AllProjects,
          'MyProjects': MyProjects,
          'AddProject': AddProject,
          'SelectedProject': SelectedProject,
      },
    }
</script>