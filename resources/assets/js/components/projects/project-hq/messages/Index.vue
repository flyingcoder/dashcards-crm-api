<template>
    <section class="content hq-messages">
        <div class="content-body">
            <v-layout row wrap>
                <section class="buzz-section">
                        <div class="col-md-3">
                            <left-message></left-message>
                        </div>
                        <div class="col-md-9">
                            <!-- <message-info></message-info> -->
                            <!-- <message-table></message-table> -->
                            <new-message></new-message>
                        </div>
                </section>
            </v-layout>
        </div>
    </section>
</template>

<script>
    import LeftMessage from './LeftMessage.vue'
    import MessageInfo from './MessageInfo.vue'
    import MessageTable from './MessageTable.vue'
    import NewMessage from './NewMessage.vue'
    export default {
        components: {
            'left-message': LeftMessage,
            'message-info': MessageInfo,
            'message-table': MessageTable,
            'new-message': NewMessage,
        },
        data(){
            return {

            }
        },
        methods:{
  
        }
    
    }
</script>