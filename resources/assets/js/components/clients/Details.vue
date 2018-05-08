<template>
    <section class="content profile">
        <v-layout row wrap>
            <div class="col-md-12 content-header">
                <div class="page-title">
                    <h1> <span class="prev-path"> Dashboard </span> 
                        &nbsp; <img src="/img/icons/ArrowRight.svg"> 
                        &nbsp; <span class="prev-path"> Clients </span>
                        &nbsp; <img src="/img/icons/ArrowRight.svg"> 
                        &nbsp; <span class="current">Profile</span>
                    </h1>
                </div>
            </div>
            <div class="col-md-12">
                <div class="content-body">
                    <profile></profile>
                </div>
            </div>
        </v-layout>
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
            full_name: '',
            image_url: '',
            id: 0
        }
      },
      mounted () {
      },
      methods: {

      }
    }
</script>