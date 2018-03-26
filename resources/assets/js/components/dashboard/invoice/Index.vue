<template>
    <div class="box-content db-invoice">
        <div class="row invoice-head">
        <div class="col-md-4">
            <h5 class="name"> Clients </h5>
            <span class="number"> 10 </span>
        </div>  
        <div class="col-md-4">
            <h5 class="name"> This Month </h5>
            <span class="number"> $76.8K </span>
        </div>  
        <div class="col-md-4">
            <h5 class="name"> Last Month </h5>
            <span class="number" id="last-month"> $64.5K </span>
        </div>  
        </div>
        <div class="row invoice-chart">
            <div class="col-md-12">
                <div class="chart">
                    <img src="img/temporary/invoice.png">
                </div>
            </div>
        </div>
        <div class="row invoice-table">
        <div class="col-md-12">
            <table>
            <tbody>
                <tr>
                <td>   
                    <div class="invoice-checkbox">
                        <input type="checkbox" value="None" id="one" name="check" checked />
                        <label for="one"></label>
                    </div>
                </td>
                <td>  <img src="img/temporary/user1.png">  </td>
                <td>  <span> Brian McBurn </span> </td>
                <td>  <span> $2,123 </span> </td>
                </tr>
                <tr>
                <td>   
                    <div class="invoice-checkbox">
                    <input type="checkbox" value="None" id="two" name="check" checked />
                    <label for="two"></label>
                    </div>
                </td>
                <td>  <img src="img/temporary/user2.png">  </td>
                <td>  <span> Brian McBurn </span> </td>
                <td>  <span> $2,123 </span> </td>
                </tr>
                <tr>
                <td>   
                    <div class="invoice-checkbox">
                    <input type="checkbox" value="None" id="three" name="check" checked />
                    <label for="three"></label>
                    </div>
                </td>
                <td>  <img src="img/temporary/user3.png">  </td>
                <td>  <span> Brian McBurn </span> </td>
                <td>  <span> $2,123 </span> </td>
                </tr>
            </tbody>
            </table>
        </div>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return {
            invoice: []
        }
    },
    mounted(){
        this.getInvoice();
    },
    methods:{
        getInvoice(){
            axios.get('/api/invoice')
            .then( response => {
                this.invoice = response.data;
            })
            .catch( error => {
                if(error.response.status == 500 || error.response.status == 404){

                }
            });
        },
    }
}
</script>