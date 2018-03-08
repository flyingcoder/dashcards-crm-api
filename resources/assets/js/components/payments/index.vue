<template>
    <section class="content payments">
        <div class="content-header">
           <div class="row">
                <div class="col-md-12">
                    <div class="page-title">
                        <h1> <span class="prev-path"> Dashboard </span> &nbsp; <img src="img/icons/ArrowRight.svg"> &nbsp; <span class="current"> Payments </span> </h1>
                    </div>
                </div>
            </div>
        </div>
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
                        <div class="progress blue">
                        <span class="progress-left">
                            <span class="progress-bar"></span>
                        </span>
                        <span class="progress-right">
                            <span class="progress-bar"></span>
                        </span>
                        <div class="progress-value"> <span> 75% </span> </div>
                        </div>
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
    </section>
</template>

<script>
    import PageHeader from '../page-header.vue';

    export default {   

        components: {
          'page-header': PageHeader,
      },

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