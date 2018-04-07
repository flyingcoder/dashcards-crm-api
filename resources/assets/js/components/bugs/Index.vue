<template>
    <section class="content bugs">
        <div class="content-header">
            <div class="row">
                <div class="col-md-6">
                    <div class="page-title">
                        <h1> <span class="prev-path"> Dashboard </span> &nbsp; <img src="img/icons/ArrowRight.svg"> &nbsp; <span class="current"> Bugs </span> </h1>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="head-page-option">
                        <ul class="nav nav-tabs">
                            <li>
                                <div class="add-button">
                                    <span> ADD NEW </span>
                                    <button  @click="$modal.show('add-bugs')">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                            width="20px" height="20px">
                                            <path fill-rule="evenodd"  fill="rgb(255, 255, 255)"
                                            d="M18.852,10.789 L11.590,10.789 L11.590,19.039 C11.590,19.444 11.193,19.773 10.703,19.773 C10.212,19.773 9.815,19.444 9.815,19.039 L9.815,10.789 L1.663,10.789 C1.262,10.789 0.937,10.387 0.937,9.892 C0.937,9.395 1.262,8.993 1.663,8.993 L9.815,8.993 L9.815,1.645 C9.815,1.240 10.212,0.911 10.703,0.911 C11.193,0.911 11.590,1.240 11.590,1.645 L11.590,8.993 L18.852,8.993 C19.252,8.993 19.577,9.395 19.577,9.892 C19.577,10.387 19.252,10.789 18.852,10.789 Z"/>
                                        </svg>
                                    </button>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <section class="buzz-section">
                <div class="topicsReview">
                    <h1>Topics <span>Review</span></h1>
                    <div class="row">
                        <div class="col-md-4">
                            <el-select v-model="general" placeholder="General">
                                <el-option
                                v-for="item in general"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                                </el-option>
                            </el-select>
                        </div>
                        <div class="col-md-4">
                            <el-select v-model="recently" placeholder="Recently Updated">
                                <el-option
                                v-for="item in recently"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                                </el-option>
                            </el-select>
                        </div>
                        <div class="col-md-4">
                            <el-input placeholder="Updates" v-model="updates"></el-input>
                        </div>
                    </div>
                    <h2> Topics <span>21-30</span> from <span>1,489</span></h2>
                </div>
                <div class="bugs-content">
                    <div class="row">
                        <div class="col-md-6">
                            <bugs></bugs>
                        </div>
                        <div class="col-md-6 ">
                            <bugs-info></bugs-info>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <add-bugs></add-bugs>
    </section>
</template>

<script>
    import AddBugs from './AddBugs.vue'
    import Bugs from './Bugs.vue'
    import BugsInfo from './BugsInfo.vue'

    export default {
        components: {
            'add-bugs': AddBugs ,
            'bugs': Bugs ,
            'bugs-info': BugsInfo ,
        },
        data(){
            return {
                taksId : 0,
            }
        },
        methods:{
            clickTask(val){
                this.taksId = val.id;
            }
        }
    
    }
</script>