<template>
    <div class="tab-pane fade active in" id="passbox">
        <!-- <passbox-login></passbox-login> -->
        <passbox-clients></passbox-clients>

        <div class="box-footer">
            <a href=""> View More </a>
        </div>
    </div>
</template>

<script>
    import PassboxLogin from './passbox/passbox-login.vue';
    import PassboxClients from './passbox/passbox-clients.vue';

     export default {
        components: {
            'passbox-login': PassboxLogin,
            'passbox-clients': PassboxClients,
        }
    }
</script>