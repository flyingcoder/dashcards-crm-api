<template>
    <section class="content settings">
        <settings-menu></settings-menu>
        <Cron></Cron>
        <!-- <section>
            <transition name="fade" mode="out-in">
                <router-view></router-view>
            </transition>
        </section> -->
    </section>
</template>

<script>
    import SettingsMenu from './SettingsMenu.vue';
    import Cron from './Cron.vue';

    export default {
        components: {
          'settings-menu': SettingsMenu,
          'Cron': Cron,
      },
      props: ['asset'],
      
      data () {

      },
      methods: {

      }
        
    }
</script>