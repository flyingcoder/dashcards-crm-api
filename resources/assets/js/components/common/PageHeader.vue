<template>
    <div class="row">
        <div class="col-md-6">
            <div class="page-title">
                <h1> {{ title }} </h1>
            </div>
        </div>
        <div class="col-md-6">
            <div class="head-page-option">

            </div>
        </div>
    </div>
</template>


<script>
    export default {
        props: {
            title: {
                required: true,
                type: String
            }
        }
    }
</script>