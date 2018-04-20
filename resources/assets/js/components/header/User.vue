<template>
    <li>
        <el-dropdown trigger="click" placement="bottom" class="member-option">
            <el-button size="small" class="el-dropdown-link"> 
                <img src="/img/temporary/user.png" id="user-image">
                <img src="/img/icons/header/arrowdown.svg" class="arrow-down">
            </el-button>
            <el-dropdown-menu slot="dropdown" class="header-dropdown header-user buzz-scrollbar buzz-scroll">
                <el-dropdown-item>
                    <ul class="dropdown-content">
                        <li>
                            <button>
                                <img src="/img/icons/user/Profile.svg">
                                <h3>Profile</h3> 
                            </button>
                        </li>
                        <li>
                            <button v-on:click="settings">
                                <img src="/img/icons/user/Settings.svg">
                                <h3>Settings</h3> 
                            </button>
                        </li>
                        <li>
                            <button>
                                <img src="/img/icons/user/Help.svg">
                                <h3>Help</h3> 
                            </button>
                        </li>
                        <li>
                            <button>
                                <img src="/img/icons/user/Lock.svg">
                                <h3>Lock</h3> 
                            </button>
                        </li>
                        <li>
                            <button>
                                <img src="/img/icons/user/Logout.svg">
                                <h3>Logout</h3> 
                            </button>
                        </li>
                    </ul>
                </el-dropdown-item>
            </el-dropdown-menu>
        </el-dropdown>
    </li>
</template>

<script>
    export default {   
      data () {
        return {

        }
      },

      mounted () {

      },
      methods: {
        settings(event){
            location = "/settings/";
        }
      }
    }
</script>