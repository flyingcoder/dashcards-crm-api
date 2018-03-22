<template>
    <section class="content settings">
        <settings-menu></settings-menu>
        <company></company>
        <!-- <section>
            <transition name="fade" mode="out-in">
                <router-view></router-view>
            </transition>
        </section> -->
    </section>
</template>

<script>
    import SettingsMenu from './SettingsMenu.vue';
    import Company from './Company.vue';

    export default {
        components: {
          'settings-menu': SettingsMenu,
          'company': Company,
      },
      props: ['asset'],
      
      data () {

      },
      methods: {

      }
        
    }
</script>