<template>
    <div class="row">
        <div class="col-md-7">
            
        </div>
        <div class="col-md-5">
            
        </div>
        <under-construction></under-construction>
    </div>
</template>


<script>
    import UnderConstruction from '../UnderConstruction.vue';

    export default {
        components: {
            'under-construction': UnderConstruction,
            },
            data() {
                return {
                events: [],
            }
        },
    }
</script>