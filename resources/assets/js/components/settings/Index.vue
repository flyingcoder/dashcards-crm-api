<template>
    <section class="content settings">
        <settings-menu></settings-menu>
        <!-- <section>
            <transition name="fade" mode="out-in">
                <router-view></router-view>
            </transition>
        </section> -->
    </section>
</template>

<script>
    import SettingsMenu from './SettingsMenu.vue';

    export default {
        components: {
          'settings-menu': SettingsMenu,
      },
      props: ['asset'],
      
      data () {
          return{

          }
      },
      methods: {

      }
        
    }
</script>