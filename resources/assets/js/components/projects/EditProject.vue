<template>
    <modal name="edit-project" transition="nice-modal-fade" @before-open="beforeOpen">
        <section class="content">
            <div class="buzz-modal-header"> {{ title }} </div>
            <div class="buzz-scrollbar" id="buzz-scroll">
                <el-form :model="form" ref="projectForm" label-position="top" v-loading="isProcessing" style="width: 100%">
                    <div class="add-members">
                        <h4> Add Members </h4>
                        <img src="img/temporary/user1.png" class="members" alt="user">
                        <img src="img/temporary/user2.png" class="members" alt="user">
                        <img src="img/temporary/user3.png" class="members" alt="user">
                        <button class="members add-button">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                width="18px" height="18px">
                                <path fill-rule="evenodd"  fill="rgb(255, 255, 255)"
                                d="M16.905,9.715 L10.225,9.715 L10.225,17.218 C10.225,17.586 9.859,17.885 9.408,17.885 C8.957,17.885 8.592,17.586 8.592,17.218 L8.592,9.715 L1.092,9.715 C0.724,9.715 0.425,9.349 0.425,8.898 C0.425,8.447 0.724,8.081 1.092,8.081 L8.592,8.081 L8.592,1.397 C8.592,1.029 8.957,0.730 9.408,0.730 C9.859,0.730 10.225,1.029 10.225,1.397 L10.225,8.081 L16.905,8.081 C17.273,8.081 17.572,8.447 17.572,8.898 C17.572,9.349 17.273,9.715 16.905,9.715 Z"/>
                            </svg>
                        </button>
                    </div>
                    <div class="modal-options">
                        <el-form-item  class="option">
                            <div class="row">
                                <div class="option-item members-btn"> 
                                    <el-dropdown trigger="click" placement="bottom" class="member-option">
                                        <el-button size="small" class="el-dropdown-link"> 
                                            <img src="/img/icons/modal/members.png" alt="" class="button-icon">   
                                            <span> Members </span> 
                                        </el-button>
                                        <el-dropdown-menu slot="dropdown" class="member-dropdown" id="member-dropdown">
                                            <!-- v-bind:class="{ active: showMembers }" -->
                                            <div class="member-content">
                                                <el-select v-model="form.member" multiple filterable placeholder="Select a Member">
                                                    <el-option
                                                    v-for="item in members"
                                                    :key="item.value"
                                                    :label="item.label"
                                                    :value="item.value"
                                                
                                                    class="member-option">
                                                    <!-- v-on:click="showMembers = !showMembers" -->
                                                        <!-- <label class="member-image"> <img src="/img/temporary/user1.png"> </label> -->
                                                    </el-option>
                                                </el-select>
                                            </div>
                                        </el-dropdown-menu>
                                    </el-dropdown>
                                </div>
                                <div class="option-item">
                                    <div class="date-option">
                                        <img src="/img/icons/modal/date.svg" alt="" class="button-icon">                                    
                                        <el-date-picker
                                            :clearable="false"
                                            v-model="form.end_at"
                                            type="date"
                                            placeholder="Due Date">
                                        </el-date-picker>
                                    </div>
                                </div>
                                <div class="option-item">
                                    <div class="file-upload">
                                        <el-upload
                                            class=""
                                            ref="upload"
                                            action=""
                                            :auto-upload="false">
                                            <el-button slot="trigger">
                                                <img src="/img/icons/modal/attachment.svg" alt="" class="button-icon"> 
                                                Attachment 
                                            </el-button>
                                        </el-upload>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="option-item"> 
                                    <el-button>
                                        <img src="img/icons/modal/more.svg" alt="" class="button-icon">
                                        More 
                                    </el-button>
                                </div>
                                <div class="option-item">
                                    <el-button>
                                        <img src="img/icons/modal/copy.png" alt="" class="button-icon">
                                        Copy 
                                    </el-button>
                                </div>
                                <div class="option-item">
                                    <el-button>
                                        <img src="img/icons/modal/archive.svg" alt="" class="button-icon"> 
                                        Archive 
                                    </el-button>
                                </div>
                             </div>
                        </el-form-item>
                    </div>
                    <div class="buzz-modal-content">
                        <el-form-item prop="name">
                            <el-input type="text" v-model="form.name" placeholder="Untitled Project"></el-input>
                        </el-form-item>
                        <el-form-item>
                            <el-select v-model="form.client" clearable placeholder="Select Client">
                                <el-option
                                v-for="c in clients"
                                :key="c.id"
                                :label="c.company_name"
                                :value="c.id">
                                </el-option>
                            </el-select>
                        </el-form-item>
                        <el-form-item>
                            <el-select v-model="form.service" clearable placeholder="Select Service">
                                <el-option
                                v-for="s in services"
                                :key="s.id"
                                :label="s.name"
                                :value="s.id">
                                </el-option>
                            </el-select>
                        </el-form-item>
                        <el-form-item label="Add Description">
                            <quill-editor 
                                class="add-description" 
                                v-bind:class="{ showEditor: descriptionEditor }"
                                v-model="form.description" 
                                ref="myQuillEditor">
                            </quill-editor>
                            <div class="field-options">
                                <el-button class="send border"> <span> Send </span> </el-button>
                                <el-button class="border" v-on:click="descriptionEditor = !descriptionEditor"> 
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="14px" height="18px">
                                        <path fill-rule="evenodd"
                                        d="M0.599,17.193 L0.599,13.304 L13.453,13.304 L13.453,17.193 L0.599,17.193 ZM12.135,14.600 L1.918,14.600 L1.918,15.896 L12.135,15.896 L12.135,14.600 ZM9.149,8.993 L4.948,8.993 L3.875,12.007 L2.478,12.007 L6.550,0.569 L7.588,0.569 L11.601,12.007 L10.207,12.007 L9.149,8.993 ZM7.063,3.049 L5.409,7.697 L8.694,7.697 L7.063,3.049 Z"/>
                                        </svg> 
                                </el-button>
                                <el-button> 
                                    <el-upload
                                        class=""
                                        ref="upload"
                                        action=""
                                        :auto-upload="false">
                                        <el-button slot="trigger">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                width="14px" height="14px">
                                                <path fill-rule="evenodd"
                                                d="M13.592,5.029 C13.369,4.808 13.009,4.809 12.787,5.031 L5.943,11.879 C5.055,12.762 3.613,12.762 2.723,11.877 C1.834,10.992 1.834,9.557 2.723,8.673 L9.770,1.624 C10.324,1.072 11.226,1.072 11.782,1.625 C12.337,2.178 12.337,3.075 11.782,3.628 L5.944,9.437 C5.944,9.437 5.943,9.438 5.943,9.438 C5.720,9.658 5.361,9.658 5.139,9.437 C4.917,9.216 4.917,8.857 5.139,8.636 L7.957,5.831 C8.179,5.610 8.179,5.252 7.957,5.030 C7.735,4.809 7.374,4.809 7.152,5.030 L4.334,7.835 C3.667,8.498 3.667,9.574 4.334,10.238 C5.001,10.902 6.082,10.902 6.749,10.238 C6.750,10.238 6.751,10.237 6.751,10.236 L12.587,4.429 C13.587,3.433 13.587,1.819 12.587,0.823 C11.586,-0.172 9.964,-0.172 8.964,0.823 L1.917,7.872 C0.584,9.199 0.584,11.351 1.918,12.679 C3.253,14.006 5.415,14.006 6.749,12.679 L13.594,5.830 C13.816,5.608 13.815,5.250 13.592,5.029 Z"/>
                                            </svg>
                                        </el-button>
                                    </el-upload>
                                </el-button>
                                <el-button> 
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="22px" height="16px">
                                        <path fill-rule="evenodd"
                                        d="M20.440,14.013 L4.752,14.013 C4.127,14.013 3.616,13.517 3.616,12.911 L3.616,1.421 C3.616,0.814 4.127,0.319 4.752,0.319 L20.440,0.319 C21.064,0.319 21.575,0.814 21.575,1.421 L21.575,12.911 C21.575,13.517 21.064,14.013 20.440,14.013 ZM20.432,1.544 L4.654,1.544 L4.654,9.341 L8.939,4.599 C9.460,4.023 9.882,4.185 10.366,4.748 L14.762,9.866 L16.978,7.685 C17.446,7.224 17.872,7.226 18.378,7.691 L20.432,9.582 L20.432,1.544 ZM17.178,5.930 C16.301,5.930 15.590,5.240 15.590,4.388 C15.590,3.537 16.301,2.847 17.178,2.847 C18.055,2.847 18.766,3.537 18.766,4.388 C18.766,5.240 18.055,5.930 17.178,5.930 ZM4.752,14.256 L12.954,14.256 L3.155,15.446 C2.535,15.521 1.965,15.090 1.888,14.489 L0.420,3.088 C0.342,2.486 0.786,1.933 1.405,1.858 L3.365,1.620 L3.365,2.855 L1.465,3.085 L2.462,10.822 L3.365,9.529 L3.365,12.911 C3.365,13.281 3.521,13.617 3.772,13.861 C4.023,14.105 4.370,14.256 4.752,14.256 Z"/>
                                    </svg>
                                </el-button>
                                <el-button> 
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="20px" height="20px">
                                        <path fill-rule="evenodd"
                                        d="M18.337,14.812 C17.458,16.317 16.265,17.509 14.759,18.388 C13.253,19.266 11.608,19.705 9.824,19.705 C8.041,19.705 6.396,19.266 4.889,18.388 C3.383,17.509 2.191,16.317 1.312,14.812 C0.433,13.307 -0.007,11.663 -0.007,9.881 C-0.007,8.099 0.433,6.455 1.312,4.949 C2.191,3.444 3.383,2.253 4.890,1.374 C6.396,0.496 8.041,0.057 9.824,0.057 C11.608,0.057 13.253,0.496 14.759,1.374 C16.265,2.253 17.458,3.444 18.337,4.949 C19.216,6.455 19.655,8.099 19.655,9.881 C19.655,11.663 19.216,13.307 18.337,14.812 ZM17.364,6.702 C16.929,5.691 16.346,4.822 15.617,4.092 C14.887,3.363 14.017,2.781 13.005,2.346 C11.994,1.911 10.934,1.694 9.824,1.694 C8.715,1.694 7.655,1.912 6.643,2.346 C5.632,2.781 4.762,3.363 4.032,4.092 C3.302,4.822 2.720,5.692 2.285,6.702 C1.849,7.713 1.632,8.772 1.632,9.881 C1.632,10.990 1.849,12.049 2.285,13.060 C2.720,14.070 3.302,14.940 4.032,15.669 C4.762,16.398 5.632,16.980 6.643,17.415 C7.655,17.850 8.715,18.068 9.824,18.068 C10.934,18.068 11.994,17.850 13.005,17.415 C14.017,16.980 14.887,16.398 15.617,15.669 C16.346,14.940 16.929,14.070 17.364,13.060 C17.799,12.049 18.017,10.990 18.017,9.881 C18.017,8.772 17.799,7.713 17.364,6.702 ZM13.101,8.244 C12.649,8.244 12.263,8.084 11.943,7.764 C11.623,7.444 11.462,7.058 11.462,6.606 C11.462,6.154 11.623,5.768 11.943,5.448 C12.263,5.129 12.649,4.969 13.101,4.969 C13.553,4.969 13.940,5.129 14.260,5.448 C14.580,5.768 14.740,6.154 14.740,6.606 C14.740,7.058 14.580,7.444 14.260,7.764 C13.940,8.083 13.553,8.244 13.101,8.244 ZM12.948,11.685 C13.016,11.463 13.152,11.301 13.357,11.198 C13.562,11.096 13.771,11.079 13.984,11.147 C14.198,11.216 14.355,11.350 14.458,11.550 C14.561,11.750 14.578,11.958 14.509,12.170 C14.193,13.203 13.605,14.034 12.743,14.665 C11.881,15.296 10.908,15.612 9.825,15.612 C8.741,15.612 7.768,15.296 6.906,14.665 C6.044,14.034 5.455,13.202 5.139,12.170 C5.071,11.958 5.088,11.750 5.191,11.550 C5.293,11.350 5.455,11.216 5.677,11.147 C5.890,11.079 6.097,11.096 6.298,11.198 C6.498,11.301 6.633,11.463 6.701,11.685 C6.914,12.367 7.309,12.919 7.885,13.341 C8.461,13.763 9.108,13.975 9.824,13.975 C10.541,13.975 11.188,13.763 11.764,13.341 C12.340,12.919 12.735,12.367 12.948,11.685 ZM6.547,8.244 C6.095,8.244 5.709,8.084 5.389,7.764 C5.069,7.444 4.909,7.058 4.909,6.606 C4.909,6.154 5.069,5.768 5.389,5.448 C5.709,5.129 6.095,4.969 6.547,4.969 C6.999,4.969 7.386,5.129 7.706,5.448 C8.026,5.768 8.186,6.154 8.186,6.606 C8.186,7.058 8.026,7.444 7.706,7.764 C7.386,8.083 6.999,8.244 6.547,8.244 Z"/>
                                    </svg>
                                </el-button>
                                <el-button class="pull-right"> 
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="15px" height="22px">
                                        <path fill-rule="evenodd"
                                        d="M14.315,6.346 L0.664,6.346 C0.292,6.346 -0.009,6.039 -0.009,5.661 L-0.009,3.554 C-0.009,3.175 0.292,2.868 0.664,2.868 L4.351,2.868 L4.351,1.446 C4.351,1.068 4.652,0.761 5.024,0.761 L9.955,0.761 C10.326,0.761 10.628,1.068 10.628,1.446 L10.628,2.868 L14.315,2.868 C14.687,2.868 14.988,3.175 14.988,3.554 L14.988,5.661 C14.988,6.039 14.687,6.346 14.315,6.346 ZM9.281,2.131 L5.697,2.131 L5.697,2.868 L9.281,2.868 L9.281,2.131 ZM13.495,21.112 C13.480,21.479 13.183,21.768 12.823,21.768 L2.156,21.768 C1.795,21.768 1.499,21.479 1.484,21.113 L0.925,7.716 L14.053,7.716 L13.495,21.112 ZM5.454,10.501 C5.454,10.123 5.153,9.816 4.781,9.816 C4.410,9.816 4.108,10.123 4.108,10.501 L4.108,18.983 C4.108,19.361 4.410,19.668 4.781,19.668 C5.153,19.668 5.454,19.361 5.454,18.983 L5.454,10.501 ZM8.162,10.501 C8.162,10.123 7.861,9.816 7.489,9.816 C7.118,9.816 6.816,10.123 6.816,10.501 L6.816,18.983 C6.816,19.361 7.118,19.668 7.489,19.668 C7.861,19.668 8.162,19.361 8.162,18.983 L8.162,10.501 ZM10.870,10.501 C10.870,10.123 10.569,9.816 10.197,9.816 C9.825,9.816 9.524,10.123 9.524,10.501 L9.524,18.983 C9.524,19.361 9.825,19.668 10.197,19.668 C10.569,19.668 10.870,19.361 10.870,18.983 L10.870,10.501 Z"/>
                                    </svg>
                                </el-button>
                            </div>
                        </el-form-item>
                        <el-form-item label="Add Comment">
                            <quill-editor 
                                class="add-comment" 
                                v-bind:class="{ showEditor: commentEditor }"
                                v-model="form.comment" 
                                ref="myQuillEditor">
                            </quill-editor>
                            <div class="field-options">
                                <el-button class="send border"> <span> Send </span> </el-button>
                                <el-button class="border" v-on:click="commentEditor = !commentEditor"> 
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="14px" height="18px">
                                        <path fill-rule="evenodd"
                                        d="M0.599,17.193 L0.599,13.304 L13.453,13.304 L13.453,17.193 L0.599,17.193 ZM12.135,14.600 L1.918,14.600 L1.918,15.896 L12.135,15.896 L12.135,14.600 ZM9.149,8.993 L4.948,8.993 L3.875,12.007 L2.478,12.007 L6.550,0.569 L7.588,0.569 L11.601,12.007 L10.207,12.007 L9.149,8.993 ZM7.063,3.049 L5.409,7.697 L8.694,7.697 L7.063,3.049 Z"/>
                                        </svg> 
                                </el-button>
                                <el-button> 
                                    <el-upload
                                        class=""
                                        ref="upload"
                                        action=""
                                        :auto-upload="false">
                                        <el-button slot="trigger">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                width="14px" height="14px">
                                                <path fill-rule="evenodd"
                                                d="M13.592,5.029 C13.369,4.808 13.009,4.809 12.787,5.031 L5.943,11.879 C5.055,12.762 3.613,12.762 2.723,11.877 C1.834,10.992 1.834,9.557 2.723,8.673 L9.770,1.624 C10.324,1.072 11.226,1.072 11.782,1.625 C12.337,2.178 12.337,3.075 11.782,3.628 L5.944,9.437 C5.944,9.437 5.943,9.438 5.943,9.438 C5.720,9.658 5.361,9.658 5.139,9.437 C4.917,9.216 4.917,8.857 5.139,8.636 L7.957,5.831 C8.179,5.610 8.179,5.252 7.957,5.030 C7.735,4.809 7.374,4.809 7.152,5.030 L4.334,7.835 C3.667,8.498 3.667,9.574 4.334,10.238 C5.001,10.902 6.082,10.902 6.749,10.238 C6.750,10.238 6.751,10.237 6.751,10.236 L12.587,4.429 C13.587,3.433 13.587,1.819 12.587,0.823 C11.586,-0.172 9.964,-0.172 8.964,0.823 L1.917,7.872 C0.584,9.199 0.584,11.351 1.918,12.679 C3.253,14.006 5.415,14.006 6.749,12.679 L13.594,5.830 C13.816,5.608 13.815,5.250 13.592,5.029 Z"/>
                                            </svg>
                                        </el-button>
                                    </el-upload>
                                </el-button>
                                <el-button> 
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="22px" height="16px">
                                        <path fill-rule="evenodd"
                                        d="M20.440,14.013 L4.752,14.013 C4.127,14.013 3.616,13.517 3.616,12.911 L3.616,1.421 C3.616,0.814 4.127,0.319 4.752,0.319 L20.440,0.319 C21.064,0.319 21.575,0.814 21.575,1.421 L21.575,12.911 C21.575,13.517 21.064,14.013 20.440,14.013 ZM20.432,1.544 L4.654,1.544 L4.654,9.341 L8.939,4.599 C9.460,4.023 9.882,4.185 10.366,4.748 L14.762,9.866 L16.978,7.685 C17.446,7.224 17.872,7.226 18.378,7.691 L20.432,9.582 L20.432,1.544 ZM17.178,5.930 C16.301,5.930 15.590,5.240 15.590,4.388 C15.590,3.537 16.301,2.847 17.178,2.847 C18.055,2.847 18.766,3.537 18.766,4.388 C18.766,5.240 18.055,5.930 17.178,5.930 ZM4.752,14.256 L12.954,14.256 L3.155,15.446 C2.535,15.521 1.965,15.090 1.888,14.489 L0.420,3.088 C0.342,2.486 0.786,1.933 1.405,1.858 L3.365,1.620 L3.365,2.855 L1.465,3.085 L2.462,10.822 L3.365,9.529 L3.365,12.911 C3.365,13.281 3.521,13.617 3.772,13.861 C4.023,14.105 4.370,14.256 4.752,14.256 Z"/>
                                    </svg>
                                </el-button>
                                <el-button> 
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="20px" height="20px">
                                        <path fill-rule="evenodd"
                                        d="M18.337,14.812 C17.458,16.317 16.265,17.509 14.759,18.388 C13.253,19.266 11.608,19.705 9.824,19.705 C8.041,19.705 6.396,19.266 4.889,18.388 C3.383,17.509 2.191,16.317 1.312,14.812 C0.433,13.307 -0.007,11.663 -0.007,9.881 C-0.007,8.099 0.433,6.455 1.312,4.949 C2.191,3.444 3.383,2.253 4.890,1.374 C6.396,0.496 8.041,0.057 9.824,0.057 C11.608,0.057 13.253,0.496 14.759,1.374 C16.265,2.253 17.458,3.444 18.337,4.949 C19.216,6.455 19.655,8.099 19.655,9.881 C19.655,11.663 19.216,13.307 18.337,14.812 ZM17.364,6.702 C16.929,5.691 16.346,4.822 15.617,4.092 C14.887,3.363 14.017,2.781 13.005,2.346 C11.994,1.911 10.934,1.694 9.824,1.694 C8.715,1.694 7.655,1.912 6.643,2.346 C5.632,2.781 4.762,3.363 4.032,4.092 C3.302,4.822 2.720,5.692 2.285,6.702 C1.849,7.713 1.632,8.772 1.632,9.881 C1.632,10.990 1.849,12.049 2.285,13.060 C2.720,14.070 3.302,14.940 4.032,15.669 C4.762,16.398 5.632,16.980 6.643,17.415 C7.655,17.850 8.715,18.068 9.824,18.068 C10.934,18.068 11.994,17.850 13.005,17.415 C14.017,16.980 14.887,16.398 15.617,15.669 C16.346,14.940 16.929,14.070 17.364,13.060 C17.799,12.049 18.017,10.990 18.017,9.881 C18.017,8.772 17.799,7.713 17.364,6.702 ZM13.101,8.244 C12.649,8.244 12.263,8.084 11.943,7.764 C11.623,7.444 11.462,7.058 11.462,6.606 C11.462,6.154 11.623,5.768 11.943,5.448 C12.263,5.129 12.649,4.969 13.101,4.969 C13.553,4.969 13.940,5.129 14.260,5.448 C14.580,5.768 14.740,6.154 14.740,6.606 C14.740,7.058 14.580,7.444 14.260,7.764 C13.940,8.083 13.553,8.244 13.101,8.244 ZM12.948,11.685 C13.016,11.463 13.152,11.301 13.357,11.198 C13.562,11.096 13.771,11.079 13.984,11.147 C14.198,11.216 14.355,11.350 14.458,11.550 C14.561,11.750 14.578,11.958 14.509,12.170 C14.193,13.203 13.605,14.034 12.743,14.665 C11.881,15.296 10.908,15.612 9.825,15.612 C8.741,15.612 7.768,15.296 6.906,14.665 C6.044,14.034 5.455,13.202 5.139,12.170 C5.071,11.958 5.088,11.750 5.191,11.550 C5.293,11.350 5.455,11.216 5.677,11.147 C5.890,11.079 6.097,11.096 6.298,11.198 C6.498,11.301 6.633,11.463 6.701,11.685 C6.914,12.367 7.309,12.919 7.885,13.341 C8.461,13.763 9.108,13.975 9.824,13.975 C10.541,13.975 11.188,13.763 11.764,13.341 C12.340,12.919 12.735,12.367 12.948,11.685 ZM6.547,8.244 C6.095,8.244 5.709,8.084 5.389,7.764 C5.069,7.444 4.909,7.058 4.909,6.606 C4.909,6.154 5.069,5.768 5.389,5.448 C5.709,5.129 6.095,4.969 6.547,4.969 C6.999,4.969 7.386,5.129 7.706,5.448 C8.026,5.768 8.186,6.154 8.186,6.606 C8.186,7.058 8.026,7.444 7.706,7.764 C7.386,8.083 6.999,8.244 6.547,8.244 Z"/>
                                    </svg>
                                </el-button>
                                <el-button class="pull-right"> 
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="15px" height="22px">
                                        <path fill-rule="evenodd"
                                        d="M14.315,6.346 L0.664,6.346 C0.292,6.346 -0.009,6.039 -0.009,5.661 L-0.009,3.554 C-0.009,3.175 0.292,2.868 0.664,2.868 L4.351,2.868 L4.351,1.446 C4.351,1.068 4.652,0.761 5.024,0.761 L9.955,0.761 C10.326,0.761 10.628,1.068 10.628,1.446 L10.628,2.868 L14.315,2.868 C14.687,2.868 14.988,3.175 14.988,3.554 L14.988,5.661 C14.988,6.039 14.687,6.346 14.315,6.346 ZM9.281,2.131 L5.697,2.131 L5.697,2.868 L9.281,2.868 L9.281,2.131 ZM13.495,21.112 C13.480,21.479 13.183,21.768 12.823,21.768 L2.156,21.768 C1.795,21.768 1.499,21.479 1.484,21.113 L0.925,7.716 L14.053,7.716 L13.495,21.112 ZM5.454,10.501 C5.454,10.123 5.153,9.816 4.781,9.816 C4.410,9.816 4.108,10.123 4.108,10.501 L4.108,18.983 C4.108,19.361 4.410,19.668 4.781,19.668 C5.153,19.668 5.454,19.361 5.454,18.983 L5.454,10.501 ZM8.162,10.501 C8.162,10.123 7.861,9.816 7.489,9.816 C7.118,9.816 6.816,10.123 6.816,10.501 L6.816,18.983 C6.816,19.361 7.118,19.668 7.489,19.668 C7.861,19.668 8.162,19.361 8.162,18.983 L8.162,10.501 ZM10.870,10.501 C10.870,10.123 10.569,9.816 10.197,9.816 C9.825,9.816 9.524,10.123 9.524,10.501 L9.524,18.983 C9.524,19.361 9.825,19.668 10.197,19.668 C10.569,19.668 10.870,19.361 10.870,18.983 L10.870,10.501 Z"/>
                                    </svg>
                                </el-button>
                            </div>
                        </el-form-item>
                        <el-form-item  class="form-buttons">
                            <el-button @click="submit"> {{ action }}</el-button>
                            <el-button @click="$modal.hide('add-project')">Cancel</el-button>
                        </el-form-item>
                    </div>
                </el-form>
            </div>
        </section>
    </modal>
</template>

<script>

var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();

    export default {
    	data: function () {
        	return {    
                showMembers: false,
                descriptionEditor: false,
                commentEditor: false,
                title: 'Website Redesign Concept',
                action: 'Update',
                id: 0,
                oldName: '',
                isProcessing: false,
                form: {
                    title: '',
                    description: '',
                    comment: '',
                    member: '',
                    end_at: '',
                    start_at: yyyy + '-' + mm + '-' + dd,
                    content: '',
                    client_id: 1,
                    service_id: 1,
                },
                clients: [],
                services: [],
                members: [{
                    value: 'Option1',
                    label: 'Option1'
                    }, {
                    value: 'Option2',
                    label: 'Option2'
                    }, {
                    value: 'Option3',
                    label: 'Option3'
                    }, {
                    value: 'Option4',
                    label: 'Option4'
                    }, {
                    value: 'Option5',
                    label: 'Option5'
                }],
        		error: {
        			title: [],
                    description: [],
                    comment: [],
                    end_at: [],
                    start_at: [],
                    content: [],
                    client_id: [],
                    service_id: [],
        		},
        	}
        },

        methods: {
            beforeOpen (event) {
                if(typeof event.params != 'undefined' && event.params.action == 'update') {
                    this.action = 'Update';
                    this.header = 'Edit Project';
                    this.id = event.params.data.id;
                    var vm = this;
                    axios.get('api/projects/'+this.id)
                        .then( response => {
                            this.form = response.data;
                        });
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
            save: function () {
                this.isProcessing = true;
                axios.post('/api/projects/',this.form)
                .then( response => {
                    this.isProcessing = false;
                    swal('Success!', 'Project is saved!', 'success');
                })
                .catch ( error => {
                    this.isProcessing = false;
                    if(error.response.status == 422){
                    this.errors = error.response.data.errors;
                    }
                })
            },
            update: function () {
                axios.put('/api/projects/'+this.id, this.form)
                .then( response => {
                    this.isProcessing = false;
                    swal('Success!', 'Project is updated!', 'success');
                })
                .catch ( error => {
                    if(error.response.status == 422){
                    this.errors = error.response.data.errors;
                    }
                    this.isProcessing = false;        
                })
            },
            getClients(){
                axios.get('api/clients?all=true')
                .then( response => {
                    this.clients = response.data
                })
            },
            getServices(){
                axios.get('api/services?all=true')
                .then( response => {
                    this.services = response.data
                })
            },
        },
        mounted() {
            this.getClients();
            this.getServices();
        }
    }
</script>