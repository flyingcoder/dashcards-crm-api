<template>
    <section class="content teams">
        <v-layout row wrap>
            <div class="content-header col-md-6">
                <div class="page-title">
                    <el-breadcrumb separator=">">
                        <el-breadcrumb-item :to="{ path: '/' }">Dashboard</el-breadcrumb-item>
                        <el-breadcrumb-item>Team</el-breadcrumb-item>
                        <el-breadcrumb-item>Groups</el-breadcrumb-item>
                    </el-breadcrumb>  
                </div>
            </div>
             <div class="col-md-6">
                <div class="head-page-option">
                    <ul class="nav nav-tabs">
                        <li>
                            <div class="add-button" @click="$modal.show('add-groups')">
                                <span> ADD NEW </span>
                                <button>
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="20px" height="20px">
                                        <path fill-rule="evenodd"  fill="rgb(255, 255, 255)"
                                        d="M18.852,10.789 L11.590,10.789 L11.590,19.039 C11.590,19.444 11.193,19.773 10.703,19.773 C10.212,19.773 9.815,19.444 9.815,19.039 L9.815,10.789 L1.663,10.789 C1.262,10.789 0.937,10.387 0.937,9.892 C0.937,9.395 1.262,8.993 1.663,8.993 L9.815,8.993 L9.815,1.645 C9.815,1.240 10.212,0.911 10.703,0.911 C11.193,0.911 11.590,1.240 11.590,1.645 L11.590,8.993 L18.852,8.993 C19.252,8.993 19.577,9.395 19.577,9.892 C19.577,10.387 19.252,10.789 18.852,10.789 Z"/>
                                    </svg>
                                </button>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-12">
                <div class="content-body">
                    <section class="buzz-section">
                        <groups-table></groups-table>
                    </section>
                </div>
            </div>
        </v-layout>
        <add-groups @refresh="getAllGroups"></add-groups>
        <permissions></permissions>
        <migrate></migrate>
        <members></members>
    </section>
</template>

<script>
    import GroupsTable from './GroupsTable.vue';
    import AddGroups from './AddGroups.vue';
    import Permissions from './Permissions.vue';
    import Migrate from './Migrate.vue';
    import Members from './Members.vue';

    export default {
        components: {
            'groups-table': GroupsTable,
            'add-groups': AddGroups,
            'permissions': Permissions,
            'migrate': Migrate,
            'members': Members,
        },
    }
</script>