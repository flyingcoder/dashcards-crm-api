<template>
    <header class="buzz-header">
        <!-- Logo -->
          <div class="logo">
            <a href="#">
              <!-- <span class="logo-mini"> <img src="{{ asset('img/logo/buzzooka.png') }}" width="40"> </span> -->
              <span class="logo-mini"> <img src="img/logo/buzzooka.png}" width="40"> </span>
              <span class="logo-lg"> <img src="img/logo/buzzooka.png" width="205"> </span>
            </a>
          </div>
        <!-- Buzz Menu -->
            <div class="menu-toggle">
              <a href="#">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="14px" height="13px">
                  <path fill-rule="evenodd"  fill="rgb(144, 152, 163)"
                  d="M12.694,7.806 L1.306,7.806 C0.585,7.806 -0.000,7.221 -0.000,6.500 C-0.000,5.779 0.585,5.194 1.306,5.194 L12.694,5.194 C13.415,5.194 14.000,5.779 14.000,6.500 C14.000,7.221 13.415,7.806 12.694,7.806 ZM12.694,2.612 L1.306,2.612 C0.585,2.612 -0.000,2.027 -0.000,1.306 C-0.000,0.585 0.585,-0.000 1.306,-0.000 L12.694,-0.000 C13.415,-0.000 14.000,0.585 14.000,1.306 C14.000,2.027 13.415,2.612 12.694,2.612 ZM1.306,10.388 L12.694,10.388 C13.415,10.388 14.000,10.973 14.000,11.694 C14.000,12.415 13.415,13.000 12.694,13.000 L1.306,13.000 C0.585,13.000 -0.000,12.415 -0.000,11.694 C-0.000,10.973 0.585,10.388 1.306,10.388 Z"/>
                </svg>
              </a>
            </div>
        <div class="buzz-head">
            <section>
                <div class="row">
                    <div class="col-md-6">
                        <div class="buzz-search">
                        <form class="form-inline navbar-form pull-left">
                            <div class="input-group search">
                            <span class="input-group-btn">
                                <button class="btn" type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="26px" height="25px">
                                <path fill-rule="evenodd"  fill="rgb(218, 225, 231)"
                                    d="M24.643,22.771 L18.474,16.582 C20.060,14.764 20.929,12.476 20.929,10.094 C20.929,4.528 16.234,0.000 10.463,0.000 C4.693,0.000 -0.002,4.528 -0.002,10.094 C-0.002,15.659 4.693,20.187 10.463,20.187 C12.630,20.187 14.694,19.557 16.459,18.361 L22.675,24.596 C22.935,24.856 23.285,25.000 23.659,25.000 C24.014,25.000 24.350,24.869 24.605,24.632 C25.148,24.129 25.165,23.294 24.643,22.771 ZM10.463,2.633 C14.729,2.633 18.199,5.980 18.199,10.094 C18.199,14.207 14.729,17.554 10.463,17.554 C6.198,17.554 2.728,14.207 2.728,10.094 C2.728,5.980 6.198,2.633 10.463,2.633 Z"/>
                                </svg>
                                </button>
                            </span>
                            <input class="form-control" type="text" placeholder="Search">
                            </div>
                        </form>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="buzz-nav-menu pull-right">
                        <ul class="buzz-nav">
                            <templates></templates>
                            <email></email>
                            <notifications></notifications>
                            <chat></chat>
                            <user></user>
                        </ul>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </header>
</template>

<script>
    import Templates from './Templates.vue';
    import Email from './Email.vue';
    import Notifications from './Notifications.vue';
    import Chat from './Chat.vue';
    import User from './User.vue';

    export default {   
        components: {
          'templates': Templates,
          'email': Email,
          'notifications': Notifications,
          'chat': Chat,
          'user': User,
      },
    }
</script>