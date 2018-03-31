<template>
    <div class="underconstruction">
        <button class="btn" @click="construction">Click me!</button>
    </div>
</template>

<script>
    export default { 
      data () {
        return {

        }
      },
      mounted () {

      },
      methods: {
        construction: function () {
            swal({
                title: 'Coming Soon!',
                imageUrl: 'img/temporary/construction.jpg',
                imageWidth: 400,
                imageHeight: 200,
                imageAlt: 'Custom image',
                animation: false
            })  
        },
      }
    }
</script>