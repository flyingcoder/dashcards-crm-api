<template>
    <div class="add-shortcut">
        <button  @click="$modal.show('add-shortcut')">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                width="22px" height="21px">
                <path fill-rule="evenodd"  fill="rgb(255, 255, 255)"
                d="M20.486,10.861 L12.470,10.861 L12.470,19.861 C12.470,20.302 12.031,20.661 11.490,20.661 C10.949,20.661 10.510,20.302 10.510,19.861 L10.510,10.861 L1.511,10.861 C1.069,10.861 0.710,10.423 0.710,9.882 C0.710,9.340 1.069,8.902 1.511,8.902 L10.510,8.902 L10.510,0.885 C10.510,0.443 10.949,0.085 11.490,0.085 C12.031,0.085 12.470,0.443 12.470,0.885 L12.470,8.902 L20.486,8.902 C20.928,8.902 21.286,9.340 21.286,9.882 C21.286,10.423 20.928,10.861 20.486,10.861 Z"/>
            </svg>
        </button>
    </div>
</template>

<script>
    export default {
        components: {
         
         },
    	data() {
        	return {   
                
        	}
        },

        methods: {
            construction: function () {
                swal({
                    title: 'Coming Soon!',
                    imageUrl: '/img/temporary/construction.jpg',
                    imageWidth: 400,
                    imageHeight: 200,
                    imageAlt: 'Custom image',
                    animation: false
                })  
            },
            onBlur (e) {
                console.log(e)
            },
            onFocus (e) {
                console.log(e)
            },
        },
        mounted() {
            
        }
    }
</script>