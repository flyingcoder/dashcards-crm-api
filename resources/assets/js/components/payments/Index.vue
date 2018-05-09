<template>
    <section class="content payments">
        <v-layout row wrap>
            <div class="content-header col-md-12">
                <div class="page-title">
                    <h1> <span class="prev-path"> Dashboard </span> &nbsp; <img src="img/icons/ArrowRight.svg"> &nbsp; <span class="current"> Payments </span> </h1>
                </div>
            </div>
            <div class="col-md-12">
                <div class="content-body">
                    <section class="buzz-section">
                        <div class="row">
                            <div class="col-md-12 payment-info">
                                <h5> Total Earnings </h5>
                                <span> $100,000 </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 payment-graph">
                                <el-progress type="circle" :percentage="75"></el-progress>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 payment-info">
                                <span> $75,000 </span>
                                <h5> Completed </h5>
                            </div>
                            <div class="col-md-6 payment-info">
                                <span> $25,000 </span>
                                <h5> Remaining </h5>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </v-layout>
    </section>
</template>

<script>

    export default {   

      data () {
        return {


        }
      },

      mounted () {
        

      },

      methods: {

      }

    }
</script>