<template>
     <li>
        <el-dropdown trigger="click" placement="bottom" class="notif-badge">
            <el-badge :value="200" :max="99" class="item">
                <el-button size="small" class="el-dropdown-link">
                    <img src="/img/icons/header/notification.svg" width="32px" class="icon-normal">
                    <img src="/img/icons/header/notificationcolor.png" width="32px" class="icon-hover">
                </el-button>
            </el-badge>
            <el-dropdown-menu slot="dropdown" class="header-dropdown header-notifications buzz-scrollbar buzz-scroll">
                <el-dropdown-item>
                    <a v-on:click.stop="showNotif">
                        <div class="image">
                            <img src="/img/temporary/user1.png" class="user-image">
                            <img src="/img/icons/notifications/files.svg" class="type-image">
                        </div>
                        <div class="info">
                            <p>Brian Howar <span class="action"> Uploaded </span> Questionnaire for Client Buzzooka Inc. </p>
                            <span class="date"> 5 mins ago </span>
                        </div>
                    </a>
                </el-dropdown-item>
                <el-dropdown-item>
                     <a v-on:click.stop="showNotif">
                        <div class="image">
                            <img src="/img/temporary/user2.png" class="user-image">
                            <img src="/img/icons/notifications/images.svg" class="type-image">
                        </div>
                        <div class="info">
                            <p> Alan Podemski <span class="action"> Uploaded </span> 24 files for client x for following project </p>
                            <span class="date"> 1 hr ago </span>
                        </div>
                    </a>
                </el-dropdown-item>
                 <el-dropdown-item>
                    <a v-on:click.stop="showNotif">
                        <div class="image">
                            <img src="/img/temporary/user3.png" class="user-image">
                            <img src="/img/icons/notifications/files2.svg" class="type-image">
                        </div>
                        <div class="info">
                            <p> Jhon Smith <span class="action"> Shared </span> a GOOGLE SHEET and  GOOGLE DOC files </p>
                            <span class="date"> 3 hrs ago </span>
                        </div>
                    </a>
                </el-dropdown-item>
                <el-dropdown-item>
                    <a v-on:click.stop="showNotif">
                        <div class="image">
                            <img src="/img/temporary/user1.png" class="user-image">
                            <img src="/img/icons/notifications/edit.svg" class="type-image">
                        </div>
                        <div class="info">
                            <p> Tommy Jeffrey <span class="action"> Modified </span> folder for the client Y for the specific folder </p>
                            <span class="date"> 1 day ago </span>
                        </div>
                    </a>
                </el-dropdown-item>
                 <el-dropdown-item>
                    <a v-on:click.stop="showNotif">
                        <div class="image">
                            <img src="/img/temporary/user1.png" class="user-image">
                            <img src="/img/icons/notifications/files.svg" class="type-image">
                        </div>
                        <div class="info">
                            <p>Brian Howar <span class="action"> Uploaded </span> Questionnaire for Client Buzzooka Inc. </p>
                            <span class="date"> 5 mins ago </span>
                        </div>
                    </a>
                </el-dropdown-item>
                <el-dropdown-item>
                     <a v-on:click.stop="showNotif">
                        <div class="image">
                            <img src="/img/temporary/user2.png" class="user-image">
                            <img src="/img/icons/notifications/images.svg" class="type-image">
                        </div>
                        <div class="info">
                            <p> Alan Podemski <span class="action"> Uploaded </span> 24 files for client x for following project </p>
                            <span class="date"> 1 hr ago </span>
                        </div>
                    </a>
                </el-dropdown-item>
                 <el-dropdown-item>
                    <a v-on:click.stop="showNotif">
                        <div class="image">
                            <img src="/img/temporary/user3.png" class="user-image">
                            <img src="/img/icons/notifications/files2.svg" class="type-image">
                        </div>
                        <div class="info">
                            <p> Jhon Smith <span class="action"> Shared </span> a GOOGLE SHEET and  GOOGLE DOC files </p>
                            <span class="date"> 3 hrs ago </span>
                        </div>
                    </a>
                </el-dropdown-item>
                <el-dropdown-item>
                    <a v-on:click.stop="showNotif">
                        <div class="image">
                            <img src="/img/temporary/user1.png" class="user-image">
                            <img src="/img/icons/notifications/edit.svg" class="type-image">
                        </div>
                        <div class="info">
                            <p> Tommy Jeffrey <span class="action"> Modified </span> folder for the client Y for the specific folder </p>
                            <span class="date"> 1 day ago </span>
                        </div>
                    </a>
                </el-dropdown-item>
                 <el-dropdown-item>
                    <a v-on:click.stop="showNotif">
                        <div class="image">
                            <img src="/img/temporary/user1.png" class="user-image">
                            <img src="/img/icons/notifications/files.svg" class="type-image">
                        </div>
                        <div class="info">
                            <p>Brian Howar <span class="action"> Uploaded </span> Questionnaire for Client Buzzooka Inc. </p>
                            <span class="date"> 5 mins ago </span>
                        </div>
                    </a>
                </el-dropdown-item>
                <el-dropdown-item>
                     <a v-on:click.stop="showNotif">
                        <div class="image">
                            <img src="/img/temporary/user2.png" class="user-image">
                            <img src="/img/icons/notifications/images.svg" class="type-image">
                        </div>
                        <div class="info">
                            <p> Alan Podemski <span class="action"> Uploaded </span> 24 files for client x for following project </p>
                            <span class="date"> 1 hr ago </span>
                        </div>
                    </a>
                </el-dropdown-item>
                 <el-dropdown-item>
                    <a v-on:click.stop="showNotif">
                        <div class="image">
                            <img src="/img/temporary/user3.png" class="user-image">
                            <img src="/img/icons/notifications/files2.svg" class="type-image">
                        </div>
                        <div class="info">
                            <p> Jhon Smith <span class="action"> Shared </span> a GOOGLE SHEET and  GOOGLE DOC files </p>
                            <span class="date"> 3 hrs ago </span>
                        </div>
                    </a>
                </el-dropdown-item>
                <el-dropdown-item>
                    <a v-on:click.stop="showNotif">
                        <div class="image">
                            <img src="/img/temporary/user1.png" class="user-image">
                            <img src="/img/icons/notifications/edit.svg" class="type-image">
                        </div>
                        <div class="info">
                            <p> Tommy Jeffrey <span class="action"> Modified </span> folder for the client Y for the specific folder </p>
                            <span class="date"> 1 day ago </span>
                        </div>
                    </a>
                </el-dropdown-item>
                 <el-dropdown-item>
                    <a v-on:click.stop="showNotif">
                        <div class="image">
                            <img src="/img/temporary/user1.png" class="user-image">
                            <img src="/img/icons/notifications/files.svg" class="type-image">
                        </div>
                        <div class="info">
                            <p>Brian Howar <span class="action"> Uploaded </span> Questionnaire for Client Buzzooka Inc. </p>
                            <span class="date"> 5 mins ago </span>
                        </div>
                    </a>
                </el-dropdown-item>
                <el-dropdown-item>
                     <a v-on:click.stop="showNotif">
                        <div class="image">
                            <img src="/img/temporary/user2.png" class="user-image">
                            <img src="/img/icons/notifications/images.svg" class="type-image">
                        </div>
                        <div class="info">
                            <p> Alan Podemski <span class="action"> Uploaded </span> 24 files for client x for following project </p>
                            <span class="date"> 1 hr ago </span>
                        </div>
                    </a>
                </el-dropdown-item>
                 <el-dropdown-item>
                    <a v-on:click.stop="showNotif">
                        <div class="image">
                            <img src="/img/temporary/user3.png" class="user-image">
                            <img src="/img/icons/notifications/files2.svg" class="type-image">
                        </div>
                        <div class="info">
                            <p> Jhon Smith <span class="action"> Shared </span> a GOOGLE SHEET and  GOOGLE DOC files </p>
                            <span class="date"> 3 hrs ago </span>
                        </div>
                    </a>
                </el-dropdown-item>
                <el-dropdown-item>
                    <a v-on:click.stop="showNotif">
                        <div class="image">
                            <img src="/img/temporary/user1.png" class="user-image">
                            <img src="/img/icons/notifications/edit.svg" class="type-image">
                        </div>
                        <div class="info">
                            <p> Tommy Jeffrey <span class="action"> Modified </span> folder for the client Y for the specific folder </p>
                            <span class="date"> 1 day ago </span>
                        </div>
                    </a>
                </el-dropdown-item>
            </el-dropdown-menu>
        </el-dropdown>
        <!-- <ul class="dropdown-menu header-dropdown notifications buzz-scrollbar" id="buzz-scroll">
            <li>
                <a href="#">
                    <div class="image">
                        <img src="/img/temporary/user1.png" class="user-image">
                        <img src="/img/icons/notifications/files.svg" class="type-image">
                    </div>
                    <div class="info">
                        <p>Brian Howar <span class="action"> Uploaded </span> Questionnaire for Client Buzzooka Inc. </p>
                        <span class="date"> 5 mins ago </span>
                    </div>
                </a>
            </li>
            <li>
                <a href="#">
                    <div class="image">
                        <img src="/img/temporary/user2.png" class="user-image">
                        <img src="/img/icons/notifications/images.svg" class="type-image">
                    </div>
                    <div class="info">
                        <p> Alan Podemski <span class="action"> Uploaded </span> 24 files for client x for following project </p>
                        <span class="date"> 1 hr ago </span>
                    </div>
                </a>
            </li>
            <li>
                <a href="#">
                    <div class="image">
                        <img src="/img/temporary/user3.png" class="user-image">
                        <img src="/img/icons/notifications/files2.svg" class="type-image">
                    </div>
                    <div class="info">
                        <p> Jhon Smith <span class="action"> Shared </span> a GOOGLE SHEET and  GOOGLE DOC files </p>
                        <span class="date"> 3 hrs ago </span>
                    </div>
                </a>
            </li>
            <li>
                <a href="#">
                    <div class="image">
                        <img src="/img/temporary/user1.png" class="user-image">
                        <img src="/img/icons/notifications/edit.svg" class="type-image">
                    </div>
                    <div class="info">
                        <p> Tommy Jeffrey <span class="action"> Modified </span> folder for the client Y for the specific folder </p>
                        <span class="date"> 1 day ago </span>
                    </div>
                </a>
            </li>
            <li>
                <a href="#">
                    <div class="image">
                        <img src="/img/temporary/user1.png" class="user-image">
                        <img src="/img/icons/notifications/files.svg" class="type-image">
                    </div>
                    <div class="info">
                        <p>Brian Howar <span class="action"> Uploaded </span> Questionnaire for Client Buzzooka Inc. </p>
                        <span class="date"> 5 mins ago </span>
                    </div>
                </a>
            </li>
            <li>
                <a href="#">
                    <div class="image">
                        <img src="/img/temporary/user2.png" class="user-image">
                        <img src="/img/icons/notifications/images.svg" class="type-image">
                    </div>
                    <div class="info">
                        <p> Alan Podemski <span class="action"> Uploaded </span> 24 files for client x for following project </p>
                        <span class="date"> 1 hr ago </span>
                    </div>
                </a>
            </li>
            <li>
                <a href="#">
                    <div class="image">
                        <img src="/img/temporary/user3.png" class="user-image">
                        <img src="/img/icons/notifications/files2.svg" class="type-image">
                    </div>
                    <div class="info">
                        <p> Jhon Smith <span class="action"> Shared </span> a GOOGLE SHEET and  GOOGLE DOC files </p>
                        <span class="date"> 3 hrs ago </span>
                    </div>
                </a>
            </li>
            <li>
                <a href="#">
                    <div class="image">
                        <img src="/img/temporary/user1.png" class="user-image">
                        <img src="/img/icons/notifications/edit.svg" class="type-image">
                    </div>
                    <div class="info">
                        <p> Tommy Jeffrey <span class="action"> Modified </span> folder for the client Y for the specific folder </p>
                        <span class="date"> 1 day ago </span>
                    </div>
                </a>
            </li>
        </ul> -->
    </li>
</template>

<script>
    export default {
      data () {
        return {

        }
      },
      computed: {

      },
      mounted(){

      },
      methods: {
        showNotif(){
            console.log("notification")
        },
      }
    }
</script>