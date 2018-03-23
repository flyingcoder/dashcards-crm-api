<template>
    <section class="content settings">
        <settings-menu></settings-menu>
        <FormFields></FormFields>
        <!-- <section>
            <transition name="fade" mode="out-in">
                <router-view></router-view>
            </transition>
        </section> -->
    </section>
</template>

<script>
    import SettingsMenu from './SettingsMenu.vue';
    import FormFields from './FormFields.vue';

    export default {
        components: {
          'settings-menu': SettingsMenu,
          'FormFields': FormFields,
      },
      props: ['asset'],
      
      data () {

      },
      methods: {

      }
        
    }
</script>