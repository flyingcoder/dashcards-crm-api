<template>
    <header class="buzz-header">
        <!-- Logo -->
          <div class="logo">
            <button>
              <!-- <span class="logo-mini"> <img src="{{ asset('img/logo/buzzooka.png') }}" width="40"> </span> -->
              <span class="logo-mini"> <img src="/img/logo/buzzooka-mini.png"> </span>
              <span class="logo-lg"> <img src="/img/logo/buzzooka.png" width="170"> </span>
            </button>
          </div>
        <!-- Buzz Menu -->
            <div class="menu-toggle">
              <button class="sidebar-toggle" id="toggleBuzzMenu">
                  <!-- <span class="expand"> 
                      <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="14px" height="13px">
                        <path fill-rule="evenodd"  fill="rgb(144, 152, 163)"
                        d="M12.694,7.806 L1.306,7.806 C0.585,7.806 -0.000,7.221 -0.000,6.500 C-0.000,5.779 0.585,5.194 1.306,5.194 L12.694,5.194 C13.415,5.194 14.000,5.779 14.000,6.500 C14.000,7.221 13.415,7.806 12.694,7.806 ZM12.694,2.612 L1.306,2.612 C0.585,2.612 -0.000,2.027 -0.000,1.306 C-0.000,0.585 0.585,-0.000 1.306,-0.000 L12.694,-0.000 C13.415,-0.000 14.000,0.585 14.000,1.306 C14.000,2.027 13.415,2.612 12.694,2.612 ZM1.306,10.388 L12.694,10.388 C13.415,10.388 14.000,10.973 14.000,11.694 C14.000,12.415 13.415,13.000 12.694,13.000 L1.306,13.000 C0.585,13.000 -0.000,12.415 -0.000,11.694 C-0.000,10.973 0.585,10.388 1.306,10.388 Z"/>
                    </svg>
                  </span> -->
                  <span class="icon">
                      <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            width="8px" height="15px">
                            <path fill-rule="evenodd"  fill="rgb(144, 152, 163)"
                            d="M7.713,8.229 L1.696,14.689 C1.313,15.100 0.693,15.100 0.310,14.689 C-0.072,14.278 -0.072,13.612 0.310,13.201 L5.634,7.485 L0.311,1.770 C-0.072,1.359 -0.072,0.693 0.311,0.282 C0.693,-0.129 1.314,-0.129 1.696,0.282 L7.713,6.742 C7.905,6.947 8.000,7.216 8.000,7.485 C8.000,7.755 7.904,8.024 7.713,8.229 Z"/>
                        </svg>
                  </span>
              </button>
            </div>
        <div class="buzz-head">
            <div class="buzz-search">
                <form class="form-inline navbar-form pull-left">
                    <el-input
                        placeholder="Search..."
                        prefix-icon="el-icon-search"
                        v-model="input21">
                    </el-input>
                </form>
            </div>
            <div class="buzz-nav-menu">
                <ul class="buzz-nav">
                    <templates></templates>
                    <messages></messages>
                    <notifications></notifications>
                    <chat></chat>
                    <user></user>
                </ul>
            </div>
        </div>
    </header>
</template>

<script>
    import Templates from './Templates.vue';
    import Messages from './Messages/Index.vue';
    import Notifications from './Notifications.vue';
    import Chat from './Chat.vue';
    import User from './User.vue';

    export default {   
        components: {
          'templates': Templates,
          'messages': Messages,
          'notifications': Notifications,
          'chat': Chat,
          'user': User,
      },
      data(){
          return{
              input21: ""
          }
      }
    }
</script>