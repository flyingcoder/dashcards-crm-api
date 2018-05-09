<template>
  <modal name="add-template" transition="nice-modal-fade" @before-open="beforeOpen">
    <section class="content">
        <v-layout row wrap>
            <div class="buzz-modal-header"> {{ title }} </div>
            <div class="buzz-scrollbar milestone-form" id="buzz-scroll">
              <div style="height: 400px;">
                <el-steps direction="vertical" :active="active" >
                  <el-step title="Choose Template">

                  </el-step>
                  <el-step title="Select Milestone"></el-step>
                  <el-step title="Assign Member"></el-step>
                </el-steps>
                <el-button style="margin-top: 12px;" @click="prev">Prev step</el-button>
                <el-button style="margin-top: 12px;" @click="next">Next step</el-button>
              </div>
            </div>
        </v-layout>
    </section>
  </modal>
</template>

<script>
export default {
  data(){
    return {
      active: 0
    }
  },  
  methods: {
    beforeOpen(){
      axios.get('/api/milestones/')
      .then( response => {
        
      })
    },
    next() {
      if (this.active++ > 2);
    },
    prev() {
      if (this.active-- > 2);
    }
  }
}
</script>

<style>

</style>
