<template>
     <li>
        <el-dropdown trigger="click" placement="bottom" class="head-icons notif-badge">
            <el-badge v-show="count>0" :value="count" :max="99" class="item">
                <el-button size="small" class="notif-icon el-dropdown-link">
                    <img src="/img/icons/header/notification.svg" width="28px" class="icon-normal">
                    <img src="/img/icons/header/notificationcolor.png" width="28px" height="30px" class="icon-hover">
                </el-button>
            </el-badge>
            <el-dropdown-menu  slot="dropdown" class="header-dropdown header-notifications buzz-scrollbar buzz-scroll">
                <el-dropdown-item 
                    v-for="notification in notifications" 
                    :data="notification" 
                    :key="notification.id"
                    :class="{ read : readNotification.indexOf(notification.id) != -1 }"
                >
                    <a @click.stop="markRead(notification.id)">
                        <div class="image">
                            <img src="/img/temporary/user1.png" class="user-image">
                            <img src="/img/icons/notifications/files.svg" class="type-image">
                        </div>
                        <div class="info">
                            <p>{{ notification.description | truncate(90) }}</p>
                            <span class="date"> {{ notification.created_at | momentAgo }} </span>
                        </div>
                    </a>
                </el-dropdown-item>
                <!-- el-dropdown-item>
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
                </el-dropdown-item -->
            </el-dropdown-menu>
        </el-dropdown>
        <div v-show="count<1" class="head-icons">
            <el-button size="small" class="notif-icon el-dropdown-link">
                <img src="/img/icons/header/notification.svg" width="28px" class="icon-normal">
                <img src="/img/icons/header/notificationcolor.png" width="28px" height="30px" class="icon-hover">
            </el-button>
        </div>
    </li>
</template>
<style>
    .read {
        background: #f8f9fb !important;
    }
</style>
<script>
    export default {
      data () {
        return {
            count: 0,
            notifications: {},
            readNotification: []
        }
      },
      computed: {
        
      },
      mounted(){
        this.countNotif();
        this.getNotif();
      },
      methods: {
        markRead(id) {
            var notInArray = this.readNotification.indexOf(id) == -1;
            if(notInArray) {
                this.count--;
                this.readNotification.push(id);
                axios.put('api/user/notifications/'+id, {read:true})
                     .then( (response) => {
                        console.log(response.data);
                        location = "/project-hq/86#/files";   
                     });
            }
        },
        showNotif() {
            console.log("notification")
            swal('Oops!', 'No functionality for this yet', 'error');
        },
        getNotif() {
            axios.get('api/user/notifications')
                 .then( (response) => {
                    this.notifications = response.data
                    console.log(this.notifications);
                 });
        },
        countNotif() {
            axios.get('api/user/notifications/count')
                 .then( (response) => {
                    this.count = response.data
                 });
        }
      }
    }
</script>