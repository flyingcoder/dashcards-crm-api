<template>
  <modal name="add-template" transition="nice-modal-fade" @before-open="beforeOpen">
    <section class="content">
        <v-layout row wrap>
            <div class="buzz-modal-header"> import template </div>
            <div class="buzz-scrollbar milestone-form" id="buzz-scroll">
              <div style="height: 400px;" class="col-md-3">
                <el-steps direction="vertical" :active="active" >
                  <el-step title="Choose Template">
                  </el-step>
                  <el-step title="Assign Member"></el-step>
                </el-steps>
                
              </div>
              <div class="buzz-modal-content col-md-9">
                <el-form v-if="active == 0">
                  <el-form-item>
                        <el-select v-model="template_id" clearable placeholder="Select template">
                            <el-option 
                            v-for="t in templates"
                            :key="t.id"
                            :label="t.title"
                            :value="t.id">
                            </el-option>
                        </el-select>
                    </el-form-item>
                </el-form>
                <div v-if="active == 1">
                  <el-card v-for="m in form.milestones" :key="m.id">
                    <div slot="header" class="clearfix">
                      <span>{{ m.title }}</span>
                    </div>
                  </el-card>
                </div>
                
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
      active: 0,
      templates: [],
      template_id: '',
      form: this.initForm()
    }
  },
  methods: {
    beforeOpen(){
      axios.get('/api/milestones/all')
      .then( response => {
        this.templates = response.data;
      })  
    },
    initForm(){
      return {
        milestones:[],
      }
    },
    next() {
      if (this.active++ > 1);
      console.log(this.active);
      if(this.active == 1){
        axios.get('/api/milestones/mlt-milestone/' + this.template_id + '/all')
        .then( response => {
          this.form.milestones = response.data;
        })
      }
    },
    prev() {
      if (this.active-- < 0);
    }
  }
}
</script>

<style>

</style>
