<template>
    <section class="content hq-milestones">
        <v-layout row wrap >
            <el-button @click="$modal.show('add-template')">Import Template</el-button>
			<div class="col-md-12" v-if="!isProcessing">
            	<milestone-card v-for="d in milestones" :data="d" :key="d.id" v-on:updated="update"></milestone-card>
    		</div>
			<div v-else>
				<i class="fas fa-circle-notch fa-spin"></i> {{ loading }}
			</div>
        <add-milestone :projectId='$parent.projectId' v-on:updated="update"></add-milestone>
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
				isProcessing: true,
				loading: 'Fetching Datas ...'
			}
		},
		mounted() {
			this.getMilestones();
		},
		methods:{
			getMilestones(){
				this.isProcessing = true;
				axios.get('/api/projects/' + this.$parent.projectId + '/milestones')
				.then( response => {
					this.isProcessing = false;
					this.milestones = response.data.data;
				})
			},
			showModal(){
				// $('#modal-template-HQmilestone').modal('toggle');
			},
			update(){
				this.getMilestones();
				this.loading = 'updating';
			},

		}
    }
    
</script>