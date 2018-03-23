<template>
    <section class="content settings">
        <settings-menu></settings-menu>
        <DbBackup></DbBackup>
        <!-- <section>
            <transition name="fade" mode="out-in">
                <router-view></router-view>
            </transition>
        </section> -->
    </section>
</template>

<script>
    import SettingsMenu from './SettingsMenu.vue';
    import DbBackup from './DbBackup.vue';

    export default {
        components: {
          'settings-menu': SettingsMenu,
          'DbBackup': DbBackup,
      },
      props: ['asset'],
      
      data () {

      },
      methods: {

      }
        
    }
</script>