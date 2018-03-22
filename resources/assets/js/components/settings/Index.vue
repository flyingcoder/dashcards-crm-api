<template>
    <section class="content settings">
        <settings-menu></settings-menu>
        <invoice></invoice>
        <!-- <section>
            <transition name="fade" mode="out-in">
                <router-view></router-view>
            </transition>
        </section> -->
    </section>
</template>

<script>
    import SettingsMenu from './SettingsMenu.vue';
    import Invoice from './Invoice.vue';

    export default {
        components: {
          'settings-menu': SettingsMenu,
          'invoice': Invoice,
      },
      props: ['asset'],
      
      data () {

      },
      methods: {

      }
        
    }
</script>