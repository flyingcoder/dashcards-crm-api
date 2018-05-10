<template>
    <section class="content hq-milestones">
        <v-layout row wrap>
             <el-button @click="$modal.show('add-template')">Import Template</el-button>
			<div class="col-md-12">
            	<milestone-card v-for="d in milestones" :data="d" :key="d.id" v-on:addTask="addTask"></milestone-card>
    		</div>
        <add-milestone></add-milestone>
        <add-template :projectId='$parent.projectId' v-on:updated="update"></add-template>
        </v-layout>
    </section>
</template>

<script>
    import MilestoneCard from './MilestoneCard';
    import AddMilestone from './AddMilestone';
    import AddTemplate from './AddTemplate';

    export default {
        components: {
          'milestone-card': MilestoneCard,
          'add-milestone': AddMilestone,
          'add-template': AddTemplate,
        },
        data(){
			return {
				milestones: [],
			}
		},
		mounted() {
			this.getMilestones();
		},
		methods:{
			getMilestones(){
				axios.get('/api/projects/' + this.$parent.projectId + '/milestones')
				.then( response => {
					this.milestones = response.data.data;
				})
			},
			showModal(){
				// $('#modal-template-HQmilestone').modal('toggle');
			},
			addTask(val){
				let i = _.findIndex(this.milestones, ['id', val.id]);
				this.milestones[i].tasks.push(val.task);
			},
			update(){
				this.getMilestones();
			}
		}
    }
    
</script>