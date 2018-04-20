<template>
    <div class="tab-pane fade active in" id="passbox">
        <!-- <passbox-login></passbox-login> -->
        <!-- <passbox-clients></passbox-clients> -->
        <passbox-edit></passbox-edit>

        <div class="box-footer">
            <a href=""> View More </a>
        </div>
    </div>
</template>

<script>
    import PassboxLogin from './passbox/PassboxLogin.vue';
    import PassboxClients from './passbox/PassboxClients.vue';
    import PassboxEdit from './passbox/PassboxEdit.vue';

     export default {
        components: {
            'passbox-login': PassboxLogin,
            'passbox-clients': PassboxClients,
            'passbox-edit': PassboxEdit,
        }
    }
</script>