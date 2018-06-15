<template>
  <modal name="add-template" transition="nice-modal-fade" @before-open="beforeOpen">
    <section class="content">
        <v-layout row wrap>
            <div class="buzz-modal-header"> import template </div>
            <div class="buzz-scrollbar milestone-form" id="buzz-scroll">
              <!-- div style="height: 200px;" class="col-md-3">
                <el-steps direction="vertical" :active="active" >
                  <el-step title="Choose Template">
                  </el-step>
                  <el-step title="Assign Member"></el-step>
                </el-steps>
                
              </div -->
              <label>Choose Template</label>
              <div class="buzz-modal-content col-md-9">
                <el-form v-if="active == 0" v-loading="isProcessing">
                  <el-form-item >
                        <el-select v-model="template_id" clearable placeholder="Select template">
                            <el-option 
                            v-for="t in templates"
                            :key="t.id"
                            :label="t.name"
                            :value="t.id">
                            </el-option>
                        </el-select>
                    </el-form-item>
                </el-form>
                <!-- div v-if="active == 1">
                  <el-form v-loading="isProcessing">
                    <div style="margin-bottom: 10px"  v-for="m in form.milestones" :key="m.id" >
                    <el-card>
                    <div slot="header" class="clearfix">
                      <span>{{ m.title }}</span>
                    </div>
                    <el-form-item v-for="(t,ii) in m.mlt_tasks" :key="t.id" :label="t.title">
                      <el-select v-model="assign[ii].members" multiple placeholder="Assign Member">
                            <el-option 
                            v-for="m in members"
                            :key="m.id"
                            :label="m.first_name + ' ' + m.last_name"
                            :value="m.id">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    </el-card>
                    </div>
                    
                  </el-form>
                  
                </div -->
                
                <el-button style="margin-top: 12px;" @click="prev">Prev step</el-button>
                <el-button style="margin-top: 12px;" @click="next">Next step</el-button>
                <el-button style="margin-top: 12px;" @click="submit">Submit</el-button>
                </div>
            </div>
        </v-layout>
    </section>
  </modal>
</template>

<script>
export default {
  props: ['projectId'],
  data(){
    return {
      isProcessing: true,
      active: 0,
      templates: [],
      template_id: '',
      form: this.initForm(),
      members: [],
      assign: []
    }
  },
  methods: {
    beforeOpen(){
      axios.get('/api/template?paginated=false&type=App\\Milestone')
      .then( response => {
        this.isProcessing = false;
        this.templates = response.data;
        console.log(response.data)
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
        this.isProcessing = true;
        var vm = this;
        axios.post('/api/projects/' + this.projectId + '/milestone-import', { template_id : this.template_id })
             .then( response => {
                //swal('Success!', 'Template imported.', 'success');

                location = "/project-hq/"+this.projectId+"#/milestones/"; 
              });
        
      }
    },
    prev() {
      if (this.active-- < 0);
    },
    submit(){
        this.isProcessing = true;      
        var vm = this;
        this.assign.forEach(function(a, index){
          vm.form.milestones[0].mlt_tasks[index].assign = a.members;
        })
      axios.post('/api/milestones/' + this.projectId + '/import', this.form)
      .then(response => {
        this.isProcessing = false;
        swal({
          title: 'Success!',
          text: 'Milestone is saved!',
          type: 'success'
        }).then( function() {
           vm.$modal.hide('add-template');
            vm.$emit('updated',response.data);
        });
      })
    }
  }
}
</script>

<style>

</style>
