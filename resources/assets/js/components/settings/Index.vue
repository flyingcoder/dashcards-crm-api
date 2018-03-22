<template>
    <section class="content settings">
        <settings-menu></settings-menu>
        <PaymentMethods></PaymentMethods>
        <!-- <section>
            <transition name="fade" mode="out-in">
                <router-view></router-view>
            </transition>
        </section> -->
    </section>
</template>

<script>
    import SettingsMenu from './SettingsMenu.vue';
    import PaymentMethods from './PaymentMethods.vue';

    export default {
        components: {
          'settings-menu': SettingsMenu,
          'PaymentMethods': PaymentMethods,
      },
      props: ['asset'],
      
      data () {

      },
      methods: {

      }
        
    }
</script>