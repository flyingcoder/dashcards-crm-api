<template>
     <div class="box-content db-calendar">
        <div class="row">
            <div class="col-md-7">
                <!-- <FullCalendar></FullCalendar> -->
            </div>
            <div class="col-md-5">

            </div>
        </div>
        <under-construction></under-construction>
    </div>
</template>


<script>
    // import { FullCalendar } from 'vue-full-calendar'
    import UnderConstruction from '../../UnderConstruction.vue';

    export default {
        components: {
            // FullCalendar,
            'under-construction': UnderConstruction,
            },
            data() {
                return {
                events: [],
            }
        },
    }
</script>