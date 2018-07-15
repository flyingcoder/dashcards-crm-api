<template>
    <li>
        <el-dropdown trigger="click" placement="bottom" class="head-icons email-badge">
            <el-badge :value="20" :max="99" class="item">
                <el-button size="small" class="el-dropdown-link">
                    <img src="/img/icons/header/email.svg" width="35px" class="icon-normal">
                    <img src="/img/icons/header/emailcolor.png" width="35px" class="icon-hover">
                </el-button>
            </el-badge>
            <el-dropdown-menu slot="dropdown" class="header-dropdown header-messages buzz-scrollbar buzz-scroll">
                <h2> Messages </h2>
                <v-tabs v-model="active" class="icon-tabs">
                    <v-tab>
                          <el-button size="small">Write A Message</el-button>
                    </v-tab>
                    <v-tab-item>
                        <write></write>
                    </v-tab-item>
                    <v-tab>
                        <el-badge :value="200" :max="99" class="item">
                            <el-button size="small">Inbox</el-button>
                        </el-badge>
                    </v-tab>
                    <v-tab-item>
                       <inbox></inbox>
                    </v-tab-item>
                    <v-tab>
                        <el-badge :value="200" :max="99" class="item">
                            <el-button size="small">Sent Mail</el-button>
                        </el-badge>
                    </v-tab>
                    <v-tab-item>
                        <sent></sent>
                    </v-tab-item>
                    <v-tab>
                       <el-badge :value="200" :max="99" class="item">
                            <el-button size="small">Draft</el-button>
                        </el-badge>
                    </v-tab>
                    <v-tab-item>
                       <draft></draft>
                    </v-tab-item>
                    <v-tab>
                       <el-badge :value="200" :max="99" class="item">
                            <el-button size="small">Bin</el-button>
                        </el-badge>
                    </v-tab>
                    <v-tab-item>
                       <bin></bin>
                    </v-tab-item>
                </v-tabs>
            </el-dropdown-menu>
        </el-dropdown>
    </li>
</template>

<script>
    import Write from './Write.vue';
    import Inbox from './Inbox.vue';
    import Sent from './Sent.vue';
    import Draft from './Draft.vue';
    import Bin from './Bin.vue';

    export default {   
        components: {
          'write': Write,
          'inbox': Inbox,
          'sent': Sent,
          'draft': Draft,
          'bin': Bin,
      },
        data(){
            return{
                active: ""
            }
        }
    }
</script>