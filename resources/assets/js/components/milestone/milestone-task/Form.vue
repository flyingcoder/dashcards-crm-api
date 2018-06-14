<template>
  <modal name="add-mlt-milestone" transition="nice-modal-fade" @before-open="beforeOpen">
            <section class="content add-milestone">
            <v-layout row wrap>
              <div class="buzz-modal-header"> {{ title }} </div>
              <div class="buzz-scrollbar" id="buzz-scroll">
            <el-form label-position="top" v-loading="isProcessing" :element-loading-text="loadingText">
              <div class="buzz-modal-content">
                  <el-form-item label="Title" :error="formError.title">
                    <el-input type="text" v-model="form.title" placeholder="Milestone Title"></el-input>
                  </el-form-item>
                  <el-form-item label="Days" :error="formError.days">
                    <el-input-number type="text" v-model="form.days"></el-input-number>
                  </el-form-item>
                <el-table
                  :data="tasks"
                  style="width: 100%">
                  <el-table-column
                    prop="title"
                    label="Title"
                    >
                  </el-table-column>
                  <el-table-column
                    prop="description"
                    label="Description"
                  >
                  </el-table-column>
                  <el-table-column
                    prop="days"
                    label="Days">
                  </el-table-column>
                  <el-table-column
                    fixed="right"
                    label="Actions"
                    width="120">
                    <template slot-scope="scope">
                      <el-button
                        @click.native.prevent="deleteRow(scope.$index, tasks, scope)"
                        type="text"
                        size="small">
                        Remove
                      </el-button>
                    </template>
                  </el-table-column>
                </el-table>
                <el-button type="text" @click="$modal.show('add-mlt-tasks')">Add Task</el-button>
                
                <el-form-item  class="form-buttons">
                  <el-button @click="submit"> {{ action }}</el-button>
                  <el-button @click="$modal.hide('add-mlt-milestone')">Cancel</el-button>
                </el-form-item>
              </div>
            </el-form>
            
            <modal name="add-mlt-tasks" transition="nice-modal-fade">
              <section class="content add-milestone">
                <v-layout row wrap>
                  <div class="buzz-modal-header">Task</div>
                  <div class="buzz-scrollbar" id="buzz-scroll">
                <el-form label-position="top" v-loading="isProcessing" :element-loading-text="loadingText">
                  <div class="buzz-modal-content">
                      <el-input type="hidden" v-model="formTask.tempid"></el-input>
                      <el-form-item label="Title">
                        <el-input type="text" v-model="formTask.title" placeholder="Task Title"></el-input>
                      </el-form-item>
                      <el-form-item label="Days">
                        <el-input-number type="text" v-model="formTask.days"></el-input-number>
                      </el-form-item>
                      <el-form-item class="modal-editor" label="Description">
                          <ckeditor id="description" ref="ckeditor" v-model="formTask.description"></ckeditor>
                      </el-form-item>
                      <el-button type="text" @click="dialogVisible = false">Cancel</el-button>
                        <el-button type="text" @click="addTask">Submit</el-button>
                        </div>
                </el-form>
                  </div>
                </v-layout>
              </section>
            </modal>
          </div>
        </v-layout>
    </section>
  </modal>
</template>

<script>
var URL = '/api/milestones/mlt-milestone/'
export default {
  props: ['id'],
  data(){
    return {
      form: {},
      action: 'Save',
      title: 'Add New Milestone',
      isProcessing: false,
      formError: '',
      loadingText: 'Saving ...',
      dialogVisible: false,
      tasks: [],
      formTask: this.initFormTask(),
    }
  },
  methods:{
    deleteRow(index, rows, scope) {
        if(scope.row.id != undefined){
           axios.delete('/api/tasks/'+scope.row.id)
                 .then((response) => {
                    rows.splice(index, 1);
            });
        }

         rows.splice(index, 1);
    },
    beforeOpen (event) {
      console.log('check')
      this.form = this.initForm();
      this.tasks = [];
      this.formError = '';
      if(typeof event.params != 'undefined' && event.params.action == 'Update') {
        this.action = 'Update';
        this.title = 'Edit Milestone';
        this.isProcessing = true;        
        axios.get('/api/milestone/'+ event.params.data.id)
          .then(response => {
            this.isProcessing = false;     
            this.form.title = response.data.title;
            this.form.days = response.data.days;
            this.form.id = response.data.id;
            this.tasks = response.data.tasks;
          })
      } else {
        this.action = 'Save'
        this.title = 'Add New Milestone'
        this.isProcessing = false
        this.formError = ''
        this.loadingText = 'Saving ...'
        this.dialogVisible = false
        this.formTask = this.initFormTask()
      }
    },
    initForm(){
      return {
        title: '',
        days: 1,
        status: 'active',
      }
    },
    submit(){
      if(this.action == 'Save'){
          this.save();
      }
      else {
          this.update();
      }
    },
    save(){
      this.isProcessing = true;
      var vm = this;
      axios.post('/api/template/' + this.id + '/milestone', {'milestone': this.form, 'tasks': this.tasks})
      .then( response => {
        this.isProcessing = false;                                    
        swal({
          title: 'Success!',
          text: 'Milestone is saved!',
          type: 'success'
        }).then( function() {
           vm.$modal.hide('add-mlt-milestone');
            vm.$emit('updated',response.data);
        });
      })
      .catch ( error => {
          this.isProcessing = false;
          this.formError = '';
          if(error.response.status == 422){
            this.formError = error.response.data;
            swal('Saving Failed!','Form validation failed! ', 'error');
          }
          else {
            swal('Saving Failed!','Server Error! ', 'error');  
          }
      });
    },
    update(){
      this.isProcessing = true;
      var vm = this;
      axios.put(URL + this.form.id,this.form)
      .then( response => {
        this.isProcessing = false;                                    
        swal({
          title: 'Success!',
          text: 'Project is updated!',
          type: 'success'
        }).then( function() {
           vm.$modal.hide('add-mlt-milestone');
            vm.$emit('updated',response.data);
        });
      })
      .catch ( error => {
          this.isProcessing = false;
          this.formError = '';
          if(error.response.status == 422){
            this.formError = error.response.data;
            swal('Saving Failed!','Form validation failed! ', 'error');
          }
          else {
            swal('Saving Failed!','Server Error! ', 'error');  
          }
      });
    },
    initFormTask(counter){
      return {
        title: '',
        description: '',
        days: 1
      }
    },
    addTask(){
      this.tasks.push(this.formTask);
      this.dialogVisible = false;
      this.formTask = this.initFormTask();
      this.$modal.hide('add-mlt-tasks')
    }
  }
}
</script>

<style>

</style>
