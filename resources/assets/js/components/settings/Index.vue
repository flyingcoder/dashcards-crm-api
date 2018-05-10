<template>
    <div>
        <settings-menu></settings-menu>
        <section class="content settings"> 
            <transition name="fade" mode="out-in">
                <router-view></router-view>
            </transition>
        </section>
    </div>
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