<template>
    <div class="tab-pane fade active in" id="grid-view">
        <div class="box-body" id="buzz-scroll">
            <div class="col-md-4 member-box" v-for="member in teams">
                <div class="content-body">
                    <div class="member-header">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="member-status offline"></div>
                                <div class="member-position">
                                    {{ member.job_title | capitalize }}
                                </div>
                                <div class="member-settings btn-group">
                                    <button data-toggle="dropdown" class="dropdown-toggle">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                            width="32px" height="31px">
                                            <path fill-rule="evenodd"
                                            d="M31.329,13.558 C31.281,13.130 30.780,12.809 30.346,12.809 C28.945,12.809 27.702,11.990 27.181,10.723 C26.648,9.426 26.991,7.913 28.035,6.960 C28.364,6.661 28.404,6.160 28.128,5.812 C27.411,4.906 26.596,4.087 25.707,3.378 C25.358,3.099 24.847,3.138 24.545,3.471 C23.633,4.476 21.996,4.849 20.732,4.324 C19.415,3.773 18.585,2.445 18.667,1.019 C18.693,0.572 18.365,0.183 17.916,0.131 C16.774,-0.001 15.622,-0.005 14.477,0.122 C14.034,0.171 13.705,0.550 13.720,0.992 C13.770,2.404 12.930,3.708 11.626,4.240 C10.377,4.748 8.751,4.377 7.842,3.382 C7.541,3.054 7.038,3.014 6.688,3.285 C5.772,4.001 4.939,4.820 4.215,5.719 C3.933,6.069 3.974,6.575 4.306,6.876 C5.370,7.835 5.714,9.361 5.161,10.673 C4.633,11.924 3.328,12.730 1.834,12.730 C1.350,12.715 1.004,13.039 0.951,13.477 C0.817,14.621 0.815,15.786 0.945,16.938 C0.993,17.368 1.509,17.687 1.947,17.687 C3.279,17.653 4.557,18.473 5.093,19.772 C5.627,21.069 5.284,22.581 4.238,23.535 C3.911,23.835 3.869,24.335 4.145,24.682 C4.855,25.583 5.671,26.402 6.564,27.118 C6.914,27.399 7.424,27.359 7.727,27.026 C8.642,26.019 10.279,25.647 11.539,26.173 C12.858,26.722 13.688,28.050 13.607,29.476 C13.581,29.924 13.911,30.314 14.357,30.365 C14.941,30.433 15.529,30.467 16.119,30.467 C16.678,30.467 17.237,30.436 17.797,30.374 C18.240,30.326 18.569,29.945 18.554,29.503 C18.502,28.092 19.344,26.788 20.646,26.257 C21.904,25.746 23.522,26.120 24.432,27.115 C24.734,27.442 25.233,27.482 25.585,27.211 C26.500,26.497 27.332,25.678 28.059,24.777 C28.341,24.428 28.301,23.920 27.967,23.620 C26.903,22.661 26.558,21.135 27.111,19.823 C27.631,18.589 28.887,17.760 30.238,17.760 L30.428,17.765 C30.866,17.801 31.269,17.464 31.322,17.019 C31.457,15.875 31.459,14.711 31.329,13.558 ZM16.161,20.356 C13.349,20.356 11.062,18.080 11.062,15.280 C11.062,12.481 13.349,10.204 16.161,10.204 C18.973,10.204 21.260,12.481 21.260,15.280 C21.260,18.080 18.973,20.356 16.161,20.356 Z"/>
                                        </svg>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="#" @click="$modal.show('add-member', { action: 'Update', id: member.id })"> Edit </a>
                                        </li>
                                        <!-- li>
                                            <a href="#"> Call </a>
                                        </li -->
                                        <li>
                                            <a href="#" @click="remove(member.id)"> Remove </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="member-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="member-image">
                                        <img :src="member.image_url">
                                </div>
                                <div class="member-name">
                                    <span>  {{ member.first_name | capitalize }} {{ member.last_name | capitalize}} </span>
                                </div>
                                <div class="member-profile">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="14px" height="19px">
                                        <path fill-rule="evenodd"  fill="rgb(186, 188, 200)"
                                        d="M11.674,13.277 L9.060,11.858 C8.814,11.724 8.660,11.455 8.660,11.155 L8.660,10.151 C8.723,10.067 8.789,9.972 8.857,9.868 C9.196,9.348 9.468,8.769 9.666,8.145 C10.052,7.953 10.302,7.538 10.302,7.070 L10.302,5.881 C10.302,5.595 10.204,5.318 10.029,5.101 L10.029,3.521 C10.044,3.357 10.104,2.385 9.456,1.582 C8.894,0.886 7.982,0.533 6.745,0.533 C5.508,0.533 4.595,0.886 4.033,1.582 C3.385,2.384 3.446,3.357 3.461,3.521 L3.461,5.101 C3.286,5.318 3.187,5.595 3.187,5.881 L3.187,7.070 C3.187,7.432 3.338,7.769 3.597,7.994 C3.848,9.072 4.372,9.884 4.555,10.144 L4.555,11.128 C4.555,11.415 4.411,11.679 4.179,11.817 L1.737,13.263 C0.943,13.733 0.450,14.635 0.450,15.617 L0.450,16.579 C0.450,17.989 4.568,18.362 6.745,18.362 C8.922,18.362 13.039,17.989 13.039,16.579 L13.039,15.675 C13.039,14.653 12.516,13.734 11.674,13.277 Z"/>
                                    </svg>
                                    <a href="#" @click="profile(member.id)">
                                        Profile
                                    </a>
                                </div>
                                <div class="member-country">
                                    <span> 
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                            width="13px" height="18px">
                                            <path fill-rule="evenodd"  fill="rgb(179, 182, 195)"
                                            d="M6.661,0.042 C3.284,0.042 0.537,2.869 0.537,6.344 C0.537,7.251 0.718,8.116 1.073,8.917 C2.604,12.364 5.540,16.004 6.403,17.038 C6.468,17.115 6.562,17.160 6.661,17.160 C6.760,17.160 6.855,17.115 6.919,17.038 C7.783,16.004 10.718,12.365 12.250,8.917 C12.605,8.116 12.785,7.251 12.785,6.344 C12.785,2.869 10.038,0.042 6.661,0.042 ZM6.661,9.617 C4.907,9.617 3.480,8.149 3.480,6.344 C3.480,4.539 4.907,3.071 6.661,3.071 C8.415,3.071 9.842,4.539 9.842,6.344 C9.842,8.149 8.415,9.617 6.661,9.617 Z"/>
                                        </svg>
                                        Poland 
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="member-footer">
                        <div class="col-md-6">
                            <label> Project Involve </label>  <br>
                            <span> {{ member.projects.length }} </span>
                        </div>
                        <div class="col-md-6">
                            <label> Task Involve </label> <br>
                            <span> {{ member.tasks.length }} </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import MembersOption from './MembersOption.vue';

    export default {
        components: {
            'members-option': MembersOption,
        },
        data() {
            return {
                teams: {}
            }
        },
        mounted() {
            this.getTeam();
        },
        methods: {
            getTeam: function () {
                axios.get('api/company/teams')
                     .then( (response) => {
                        this.teams = response.data;
                        console.log(this.teams);
                     });
            },
            edit(id) {

            },
            remove(id) {
                var vm = this;
                swal({
                  title: 'Are you sure?',
                  text: "You won't be able to revert this!",
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, delete it!'
                }).then(function (result) {
                  if (result) {
                    axios.delete('/api/company/teams/' + id)
                    .then(response => {
                        vm.getTeam();
                        swal('Success!', 'Member is Deleted!', 'success');
                    });
                  }
              })
            },
            profile(id) {

            }
        }
    }
</script>
