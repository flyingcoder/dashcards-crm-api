<template>
    <section class="content cloud">
        <v-layout row wrap>
            <div class="content-header col-md-6">
                <div class="page-title">
                    <h1> <span class="prev-path"> Dashboard </span> &nbsp; <img src="img/icons/ArrowRight.svg"> &nbsp; <span class="current"> Cloud </span> </h1>
                </div>
            </div>
            <!-- <div class="col-md-6">
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
            </div> -->
            <div class="col-md-12">
                <div class="content-body">
                    <section class="buzz-section">
                        <div class="cloud-box google-drive" v-bind:class="{connected: GoogleConnect }">
                            <div class="cloud-head">
                                <h2>Connect To Google Drive</h2>
                            </div>
                            <img class="colored" src="/img/icons/cloud/drive.svg">
                            <img class="gray" src="/img/icons/cloud/drive-gray.svg">
                            <div class="button">
                                <button class="buzz-add" v-on:click="GoogleConnect = !GoogleConnect">
                                    <span class="connect-now">Connect Now</span>
                                    <span class="disconnect">Disconnect</span>
                                </button>
                            </div>
                        </div>
                        <div class="cloud-box dropbox" v-bind:class="{connected: DropboxConnect }">
                            <div class="cloud-head">
                                <h2>Connect To Dropbox</h2>
                            </div>
                            <img class="colored" src="/img/icons/cloud/dropbox.svg">
                            <img class="gray" src="/img/icons/cloud/dropbox-gray.svg">
                            <div class="button">
                                <button class="buzz-add" v-on:click="DropboxConnect = !DropboxConnect">
                                    <span class="connect-now">Connect Now</span>
                                    <span class="disconnect">Disconnect</span>
                                </button>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </v-layout>
    </section>
</template>


<script>
    export default {
    	data: function () {
        	return {    
                GoogleConnect: false,
                DropboxConnect: false,
        	}
        },

        methods: {

        },
        mounted() {
 
        }
    }
</script>