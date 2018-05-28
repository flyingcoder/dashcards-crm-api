<template>
    <modal name="migrate-modal" transition="nice-modal-fade" @before-open="beforeOpen">
        <section class="content migrate">
            <v-layout row wrap>
                <div class="buzz-modal-header"> Migrate - {{ title }} </div>
                <div class="buzz-scrollbar" id="buzz-scroll">
                    <el-form ref="form" name="migrateGroup" status-icon :model="form" :rules="rules" v-loading="isProcessing" style="width: 100%">                    
                        <div class="buzz-modal-content">
                            <el-form-item prop="group_name" class="buzz-input">
                                <el-select type="text" v-model="role" placeholder="Select One">
                                    <el-option
                                        v-for="role in roles"
                                        :key="role.value"
                                        :label="role.name"
                                        :value="role.value">
                                    </el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item  class="form-buttons">
                                <el-button @click="$modal.hide('migrate-modal')">Cancel</el-button>
                            </el-form-item>
                        </div>
                    </el-form>
                </div>
            </v-layout>
        </section>
    </modal>
</template>

<script>
    export default {
    data () {
        return {
            title: '',
            isProcessing: false,
            action: 'save',
            id: 0,
            roles: [],
            role: '',
            form: {
                roles: '',
            },
        }
      },
       
    methods: {
        beforeOpen (event) {
            var vm = this;
            this.id = event.params.data.id;
            let name = event.params.data.name;
            vm.roles = [];
            axios.get('/api/groups/roles')
            .then(function (response) {
                // console.log(response.data.name)
                // vm.name = response.roles;
                // vm.oldName = vm.name;
                _.forEach(response.data.roles, function(value, key){
                    vm.roles.push(_.pick(value, ['id', 'name']));
                });
                let index = _.findIndex(vm.roles, { 'id' : vm.id, 'name' : name });
                vm.roles.splice(index, 1);
            });
        },
        save: function () {
            axios.post('api/groups/migrate', {'to' : this.role.id, 'from' : this.id })
            .then(
                (response) => {
                    this.$modal.hide('new-role-modal')
                    swal('Success!', 'Group updated!', 'success');
                    this.$events.fire('filter-reset')
                },
                (error) => {
                    this.error.status = true;
                    this.error.message = error.response.data.errors.name[0];
                }
            );
        }
      }
    }
</script>