<template>
    <section class="content settings">
        <settings-menu></settings-menu>
        <Updates></Updates>
        <!-- <section>
            <transition name="fade" mode="out-in">
                <router-view></router-view>
            </transition>
        </section> -->
    </section>
</template>

<script>
    import SettingsMenu from './SettingsMenu.vue';
    import Updates from './Updates.vue';

    export default {
        components: {
          'settings-menu': SettingsMenu,
          'Updates': Updates,
      },
      props: ['asset'],
      
      data () {

      },
      methods: {

      }
        
    }
</script>