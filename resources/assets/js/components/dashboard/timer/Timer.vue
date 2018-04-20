<template>
<div class="tab-pane fade in show active" id="timer">
  <table>
      <tbody>
          <tr>
              <td>  <img src="img/temporary/user1.png">  </td>
              <td class="buzz-overflow"> <span> Brian McBurn </span> </td>
              <td class="buzz-overflow">  <span> Website Design Wireframe Website Design Wireframe </span> </td>
              <td>  <span> 2:25:03 </span> </td>
          </tr>
          <tr>
              <td>  <img src="img/temporary/user2.png">  </td>
              <td class="buzz-overflow">  <span> William Stomhson </span> </td>
              <td class="buzz-overflow">  <span> Social Media </span> </td>
              <td>  <span> 3:25:30 </span> </td>
          </tr>
          <tr>
              <td>  <img src="img/temporary/user3.png">  </td>
              <td class="buzz-overflow">  <span> Jhon Doe </span> </td>
              <td class="buzz-overflow">  <span> Facebook Marketing </span> </td>
              <td>  <span> 3:12:09 </span> </td>
          </tr>
      </tbody>
  </table>
</div>
</template>

<script>
export default {
    data(){
        return {
            timer: []
        }
    },
    mounted(){
        this.getTimer();
    },
    methods:{
        getTimer(){
            axios.get('/api/timer')
            .then( response => {
                this.client = response.data;
            })
            .catch( error => {
                if(error.response.status == 500 || error.response.status == 404){

                }
            });
        },
    }
}
</script>

<style>

</style>
