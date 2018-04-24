<template>
    <section class="content hq-milestones">
        <div class="row">
			<div class="col-md-12">
            	<milestone-card v-for="d in milestones" :data="d" :key="d.id" v-on:addTask="addTask"></milestone-card>
    		</div>
		</div>
        <add-milestone></add-milestone>
    </section>
</template>

<script>
    import MilestoneCard from './MilestoneCard';
    import AddMilestone from './AddMilestone';

    export default {
        components: {
          'milestone-card': MilestoneCard,
          'add-milestone': AddMilestone
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
			}
		}
    }
    
</script>