<template>
    <section class="content hq-milestones">
        <div class="row">
            <milestone-card v-for="d in milestones" :data="d" :key="d.id" v-on:addTask="addTask"></milestone-card>
        </div>
        <div class="add-button">
            <span> ADD NEW </span>
            <button>
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                    width="22px" height="21px">
                    <path fill-rule="evenodd"  fill="rgb(255, 255, 255)"
                    d="M20.486,10.861 L12.470,10.861 L12.470,19.861 C12.470,20.302 12.031,20.661 11.490,20.661 C10.949,20.661 10.510,20.302 10.510,19.861 L10.510,10.861 L1.511,10.861 C1.069,10.861 0.710,10.423 0.710,9.882 C0.710,9.340 1.069,8.902 1.511,8.902 L10.510,8.902 L10.510,0.885 C10.510,0.443 10.949,0.085 11.490,0.085 C12.031,0.085 12.470,0.443 12.470,0.885 L12.470,8.902 L20.486,8.902 C20.928,8.902 21.286,9.340 21.286,9.882 C21.286,10.423 20.928,10.861 20.486,10.861 Z"/>
                </svg>
            </button>
        </div>
    </section>
</template>

<script>
    import BoxOption from '../../../box-option.vue';
    import MilestoneCard from './MilestoneCard';

    export default {
        components: {
          'box-option': BoxOption,
          'milestone-card': MilestoneCard
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