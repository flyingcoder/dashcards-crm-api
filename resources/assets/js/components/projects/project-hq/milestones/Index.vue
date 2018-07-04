<template>
    <section class="content hq-milestones">
        <v-layout row wrap >
			<div class="col-md-12 hq-milestone-add">
				<!-- <el-button @click="$modal.show('add-milestone')">
					<span> Add New </span>
				</el-button> -->
				<el-button v-show="!milestones" @click="$modal.show('add-template')">
					<span> Import Template </span>
				</el-button>
			</div>
			<div class="col-md-12" v-if="!isProcessing">
            	<milestone-card @refresh="getMilestones" v-for="d in milestones" :data="d" :key="d.id" v-on:updated="update"></milestone-card>
    		</div>
			<div class="col-md-12" v-else>
				<div class="data-loader">
					<div class="spinner">
						<div class="bounce1"></div>
						<div class="bounce2"></div>
						<div class="bounce3"></div>
					</div>
					{{ loading }} 
				</div>
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
				loading: 'Fetching Data'
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
			}

		}
    }
    
</script>