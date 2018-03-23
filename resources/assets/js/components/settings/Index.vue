<template>
    <section class="content settings">
        <settings-menu></settings-menu>
        <Support></Support>
        <!-- <section>
            <transition name="fade" mode="out-in">
                <router-view></router-view>
            </transition>
        </section> -->
    </section>
</template>

<script>
    import SettingsMenu from './SettingsMenu.vue';
    import Support from './Supports/Support.vue';

    export default {
        components: {
          'settings-menu': SettingsMenu,
          'Support': Support,
      },
      props: ['asset'],
      
      data () {

      },
      methods: {

      }
        
    }
</script>