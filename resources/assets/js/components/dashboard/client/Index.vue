<template>
    <div class="box-content db-client">
        <div class="row">
            <div class="col-md-4">
                <span class="name"> Total Sales </span> <br>
                <span class="number"> 3491 </span>
            </div>  
            <div class="col-md-4">
                <span class="name"> New Orders </span> <br>
                <span class="number"> 721 </span>
            </div>  
            <div class="col-md-4">
                <span class="name"> Total Earnings </span> <br>
                <span class="number"> $8103 </span>
            </div>  
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="clients-chart">
                    <img src="img/temporary/clients.png">
                </div>
            </div>
        </div>
    </div>
</template>
// <script>
// export default {
//     data(){
//         return {
//             client: [],
//         }
//     },
//     mounted(){
//         this.getClient();
//     },
//     methods:{
//         getClient(){
//             axios.get('/api/client')
//             .then( response => {
//                 this.client = response.data;
//             })
//             .catch( error => {
//                 if(error.response.status == 500 || error.response.status == 404){

//                 }
//             });
//         },
//     }
// }
// </script>