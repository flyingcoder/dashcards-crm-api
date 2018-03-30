<template>
     <div class="box-content db-calendar">
        <div class="row">
            <div class="col-md-7">
                <FullCalendar></FullCalendar>
            </div>
            <div class="col-md-5">
            Right
            </div>
        </div>
    </div>
</template>


<script>
    import { FullCalendar } from 'vue-full-calendar'
    export default {
        components: {
            FullCalendar,
            },
            data() {
                return {
                events: [],
            }
        },
    }
</script>