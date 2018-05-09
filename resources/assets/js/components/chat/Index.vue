<template>
    <section class="content chat">
        <v-layout row wrap>
            <div class="content-header col-md-12">
                <div class="page-title">
                    <h1> <span class="prev-path"> Dashboard </span> &nbsp; <img src="img/icons/ArrowRight.svg"> &nbsp; <span class="current"> Chat </span> </h1>
                </div>
            </div>
            <div class="col-md-12">
                <div class="content-body">
                    <section class="buzz-section">
                        <div class="col-md-3 left">
                            <chat-members></chat-members>
                        </div>
                        <div class="col-md-9 right">
                            <chat-content></chat-content>
                        </div>
                    </section>
                </div>
            </div>
        </v-layout>
    </section>
</template>

<script>
    import ChatMembers from './ChatMembers.vue'
    import ChatContent from './ChatContent.vue'

    export default {
        components: {
            'chat-members': ChatMembers ,
            'chat-content': ChatContent ,
        },
        data(){
            return {

            }
        },
        methods:{

        }
    }
</script>