<template>
    <div class="row">
        <div class="col-md-12 invoice-form">
            <div class="tab-content">
                <one></one>
                <two></two>
                <three></three>
            </div>
        </div>
    </div>
</template>

<script>
    import One from './One.vue';
    import Two from './Two.vue';
    import Three from './Three.vue';

    export default {
        components: {
          'one': One,
          'two': Two,
          'three': Three,
      },
    data() {
      return {
      };
    },
    methods: {

    }
  }
</script>