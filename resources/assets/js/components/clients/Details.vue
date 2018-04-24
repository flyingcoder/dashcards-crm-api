<template>
    <section class="content clients">
        <div class="content-header">
             <div class="row">
                <div class="col-md-6">
                    <div class="page-title">
                        <h1> <span class="prev-path"> Dashboard </span> 
                            &nbsp; <img src="/img/icons/ArrowRight.svg"> 
                            &nbsp; <span class="prev-path"> Clients </span>
                            &nbsp; <img src="/img/icons/ArrowRight.svg"> 
                            &nbsp; <span class="current">{{ full_name }}</span>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <section class="buzz-section">
                <profile></profile>
            </section>
        </div>
    </section>
</template>

<script>
    import Profile from './profile/Index.vue';

    export default {   
        components: {
          'profile': Profile,
        },
       data () {
        return {
            full_name: ''
        }
      },

      mounted () {
      },
        methods: {
        }
    }
</script>