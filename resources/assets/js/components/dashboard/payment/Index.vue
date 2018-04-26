<template>
     <div class="box-content db-payment">
        <div class="row">
            <div class="col-md-12 payment-info">
                <h5> Total Earnings </h5>
                <span> $100,000 </span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 payment-graph">
                <el-progress type="circle" :percentage="25"></el-progress>
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
    </div>
</template>
<script>
export default {
    data(){
        return {
            payment: []
        }
    },
    mounted(){
        this.getPayment();
    },
    methods:{
        getPayment(){
            axios.get('/api/payments')
            .then( response => {
                this.payment = response.data;
            })
            .catch( error => {
                if(error.response.status == 500 || error.response.status == 404){

                }
            });
        },
    }
}
</script>