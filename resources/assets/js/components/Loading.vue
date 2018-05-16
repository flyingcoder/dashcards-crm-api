<template>
    <div class="page-loader">
        <vue-loading type="spiningDubbles" color="#3b589e" :size="{ width: '100px', height: '100px' }"></vue-loading>   
    </div>
</template>

<script>
    import vueLoading from 'vue-loading-template';

    export default {
        components: {
            vueLoading
        },
    }
</script>