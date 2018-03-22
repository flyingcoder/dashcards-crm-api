<template>
    <section class="content settings">
        <settings-menu></settings-menu>
        <general></general>
        <!-- <section>
            <transition name="fade" mode="out-in">
                <router-view></router-view>
            </transition>
        </section> -->
    </section>
</template>

<script>
    import SettingsMenu from './SettingsMenu.vue';
    import General from './General.vue';

    export default {
        components: {
          'settings-menu': SettingsMenu,
          'general': General,
      },
      props: ['asset'],
      
      data () {

      },
      methods: {

      }
        
    }
</script>