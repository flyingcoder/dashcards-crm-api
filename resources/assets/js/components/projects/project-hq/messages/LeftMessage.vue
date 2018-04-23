<template>
    <div class="left-message">
        <div class="message-storage">
            <div class="write-btn">
                <el-button>
                    Write a Message
                </el-button>
            </div>
            <ul>
                <li>
                    <el-button>
                        <span> Inbox </span>
                        <el-badge :value="12" class="item"></el-badge>
                    </el-button>
                </li>
                <li>
                    <el-button>
                        <span> Sent Mail </span>
                        <el-badge :value="12" class="item"></el-badge>
                    </el-button>
                </li>
                <li>
                    <el-button>
                        <span> Draft </span>
                        <el-badge :value="12" class="item"></el-badge>
                    </el-button>
                </li>
                <li>
                    <el-button>
                        <span> Bin </span>
                        <el-badge :value="12" class="item"></el-badge>
                    </el-button>
                </li>
            </ul>
        </div>
        <members-list></members-list>
    </div>
</template>

<script>
    import MembersList from './MembersList.vue'
    export default {
        components: {
            'members-list': MembersList,
        },
        data(){
            return {

            }
        },
        methods:{
  
        }
    
    }
</script>