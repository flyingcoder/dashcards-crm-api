<template>
    <li>
        <el-dropdown trigger="click" placement="bottom" class="chat-badge">
            <el-badge :value="5" :max="99" class="item">
                <el-button size="small" class="el-dropdown-link">
                    <img src="/img/icons/header/chat.svg" width="28px" class="icon-normal">
                    <img src="/img/icons/header/chatcolor.png" width="28px" class="icon-hover">
                </el-button>
            </el-badge>
            <el-dropdown-menu slot="dropdown" class="header-dropdown header-chat buzz-scrollbar buzz-scroll">
               <div class="row">
                   <div class="col-md-12">
                        <h1> Chat </h1>
                        <div class="members">
                            <ul>
                                <el-tooltip content="Test" placement="top" effect="light">
                                    <li>
                                        <img src="/img/temporary/user1.png">
                                        <span class="online"></span>
                                    </li>
                                </el-tooltip>
                                <el-tooltip content="Test" placement="top" effect="light">
                                    <li>
                                        <img src="/img/temporary/user1.png">
                                        <span class="online"></span>
                                    </li>
                                </el-tooltip>
                                <el-tooltip content="Test" placement="top" effect="light">
                                    <li>
                                        <img src="/img/temporary/user1.png">
                                        <span class="online"></span>
                                    </li>
                                </el-tooltip>
                                <el-tooltip content="Test" placement="top" effect="light">
                                    <li>
                                        <img src="/img/temporary/user1.png">
                                        <span class="online"></span>
                                    </li>
                                </el-tooltip>
                                <el-tooltip content="Test" placement="top" effect="light">
                                    <li>
                                        <img src="/img/temporary/user1.png">
                                        <span class="online"></span>
                                    </li>
                                </el-tooltip>
                                <el-tooltip content="Test" placement="top" effect="light">
                                    <li>
                                        <img src="/img/temporary/user1.png">
                                        <span class="online"></span>
                                    </li>
                                </el-tooltip>
                                <el-tooltip content="Test" placement="top" effect="light">
                                    <li>
                                        <img src="/img/temporary/user1.png">
                                        <span class="online"></span>
                                    </li>
                                </el-tooltip>
                                <el-tooltip content="Test" placement="top" effect="light">
                                    <li>
                                        <img src="/img/temporary/user1.png">
                                        <span class="online"></span>
                                    </li>
                                </el-tooltip>
                                <el-tooltip content="Test" placement="top" effect="light">
                                    <li>
                                        <img src="/img/temporary/user1.png">
                                        <span class="online"></span>
                                    </li>
                                </el-tooltip>
                            </ul>
                        </div>
                        <div class="chat-box">
                            <div class="chat-content">
                                <div class="chat-body" id="buzz-scroll">
                                    <div class="message member">
                                        <div class="user-info">
                                            <img src="img/temporary/user1.png">
                                            <h5 class="name"> Brian Howard </h5>
                                            <span class="date"> 1 hour ago </span>
                                        </div>
                                        <p> Lorem ipsum dolor sit amet consectetur, adipisicing elit. Doloremque atque voluptas ipsam reprehenderit provident? Id, officia. Ut corrupti assumenda architecto molestiae quaerat necessitatibus beatae, nesciunt quo minus facilis iste ab? </p>
                                    </div>
                                    <div class="message me">
                                        <div class="user-info">
                                            <img src="img/temporary/user2.png">
                                            <h5 class="name"> Me </h5>
                                            <span class="date"> Just now </span>
                                        </div>
                                        <p> Okay Thanks! </p>
                                    </div>
                                    <div class="message member">
                                        <div class="user-info">
                                            <img src="img/temporary/user1.png">
                                            <h5 class="name"> Brian Howard </h5>
                                            <span class="date"> 1 hour ago </span>
                                        </div>
                                        <p> Nice! </p>
                                    </div>
                                    <div class="message me">
                                        <div class="user-info">
                                            <img src="img/temporary/user2.png">
                                            <h5 class="name"> Me </h5>
                                            <span class="date"> Just now </span>
                                        </div>
                                        <p> Contact a G Suite sales representative by phone or email to learn more about how to set up G Suite for your business. </p>
                                    </div>
                                    <div class="message member">
                                        <div class="user-info">
                                            <img src="img/temporary/user1.png">
                                            <h5 class="name"> Brian Howard </h5>
                                            <span class="date"> 1 hour ago </span>
                                        </div>
                                        <p> Lorem ipsum dolor sit amet consectetur, adipisicing elit. Doloremque atque voluptas ipsam reprehenderit provident? Id, officia. Ut corrupti assumenda architecto molestiae quaerat necessitatibus beatae, nesciunt quo minus facilis iste ab? </p>
                                    </div>
                                    <div class="message me">
                                        <div class="user-info">
                                            <img src="img/temporary/user2.png">
                                            <h5 class="name"> Me </h5>
                                            <span class="date"> Just now </span>
                                        </div>
                                        <p> Gmail is email that's intuitive, efficient, and useful. </p>
                                    </div>
                                    <div class="message member">
                                        <div class="user-info">
                                            <img src="img/temporary/user1.png">
                                            <h5 class="name"> Brian Howard </h5>
                                            <span class="date"> 1 hour ago </span>
                                        </div>
                                        <p> Lorem ipsum dolor sit amet consectetur, adipisicing elit. Doloremque atque voluptas ipsam reprehenderit provident? Id, officia. Ut corrupti assumenda architecto molestiae quaerat necessitatibus beatae, nesciunt quo minus facilis iste ab? </p>
                                    </div>
                                    <div class="message me">
                                        <div class="user-info">
                                            <img src="img/temporary/user2.png">
                                            <h5 class="name"> Me </h5>
                                            <span class="date"> Just now </span>
                                        </div>
                                        <p> Okay Thanks! </p>
                                    </div>
                                    <div class="message member">
                                        <div class="user-info">
                                            <img src="img/temporary/user1.png">
                                            <h5 class="name"> Brian Howard </h5>
                                            <span class="date"> 1 hour ago </span>
                                        </div>
                                        <p> Lorem ipsum dolor sit amet consectetur, adipisicing elit. Doloremque atque voluptas ipsam reprehenderit provident? Id, officia. Ut corrupti assumenda architecto molestiae quaerat necessitatibus beatae, nesciunt quo minus facilis iste ab? </p>
                                    </div>
                                    <div class="message me">
                                        <div class="user-info">
                                            <img src="img/temporary/user2.png">
                                            <h5 class="name"> Me </h5>
                                            <span class="date"> Just now </span>
                                        </div>
                                        <p> Okay Thanks! </p>
                                    </div>
                                    <div class="message member">
                                        <div class="user-info">
                                            <img src="img/temporary/user1.png">
                                            <h5 class="name"> Brian Howard </h5>
                                            <span class="date"> 1 hour ago </span>
                                        </div>
                                        <p> Lorem ipsum dolor sit amet consectetur, adipisicing elit. Doloremque atque voluptas ipsam reprehenderit provident? Id, officia. Ut corrupti assumenda architecto molestiae quaerat necessitatibus beatae, nesciunt quo minus facilis iste ab? </p>
                                    </div>
                                    <div class="message me">
                                        <div class="user-info">
                                            <img src="img/temporary/user2.png">
                                            <h5 class="name"> Me </h5>
                                            <span class="date"> Just now </span>
                                        </div>
                                        <p> Okay Thanks! </p>
                                    </div>
                                </div>
                                <div class="chat-write">
                                    <el-form ref="form" :model="form" label-position="top" v-loading="isProcessing" style="width: 100%">
                                        <el-form-item class="chat-item">
                                            <textarea placeholder="Type here and press enter to send"></textarea>
                                        </el-form-item>
                                        <div class="chat-buttons">
                                            <el-button class=""> 
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                    width="26px" height="26px">
                                                    <path fill-rule="evenodd"  fill="rgb(188, 194, 207)"
                                                    d="M24.154,18.906 C23.035,20.818 21.517,22.333 19.599,23.450 C17.682,24.566 15.588,25.124 13.317,25.124 C11.047,25.124 8.953,24.566 7.036,23.450 C5.118,22.333 3.600,20.818 2.481,18.906 C1.362,16.992 0.802,14.903 0.802,12.638 C0.802,10.373 1.362,8.284 2.481,6.371 C3.600,4.457 5.118,2.943 7.036,1.827 C8.953,0.710 11.047,0.152 13.318,0.152 C15.588,0.152 17.682,0.710 19.600,1.827 C21.517,2.943 23.035,4.458 24.154,6.371 C25.273,8.283 25.832,10.373 25.832,12.638 C25.832,14.903 25.273,16.992 24.154,18.906 ZM22.916,8.598 C22.362,7.313 21.620,6.208 20.691,5.281 C19.762,4.355 18.654,3.615 17.367,3.062 C16.080,2.509 14.730,2.233 13.318,2.233 C11.905,2.233 10.555,2.509 9.268,3.062 C7.981,3.615 6.873,4.355 5.944,5.281 C5.015,6.208 4.274,7.314 3.720,8.598 C3.166,9.883 2.888,11.229 2.888,12.638 C2.888,14.047 3.166,15.394 3.720,16.678 C4.274,17.963 5.015,19.068 5.944,19.995 C6.873,20.921 7.981,21.661 9.268,22.214 C10.556,22.767 11.905,23.043 13.318,23.043 C14.730,23.043 16.080,22.767 17.367,22.214 C18.654,21.661 19.763,20.921 20.691,19.995 C21.620,19.068 22.361,17.963 22.916,16.678 C23.470,15.394 23.747,14.047 23.747,12.638 C23.747,11.229 23.470,9.882 22.916,8.598 ZM17.489,10.557 C16.913,10.557 16.422,10.354 16.014,9.947 C15.607,9.541 15.403,9.051 15.403,8.476 C15.403,7.902 15.607,7.411 16.014,7.005 C16.422,6.598 16.913,6.395 17.489,6.395 C18.065,6.395 18.556,6.598 18.964,7.005 C19.371,7.411 19.575,7.902 19.575,8.476 C19.575,9.051 19.371,9.541 18.964,9.947 C18.556,10.354 18.065,10.557 17.489,10.557 ZM17.294,14.931 C17.380,14.649 17.554,14.442 17.815,14.313 C18.076,14.183 18.342,14.160 18.613,14.248 C18.885,14.335 19.086,14.505 19.217,14.759 C19.347,15.014 19.369,15.278 19.281,15.548 C18.879,16.860 18.130,17.916 17.033,18.719 C15.936,19.521 14.697,19.921 13.318,19.921 C11.938,19.921 10.700,19.521 9.602,18.719 C8.505,17.916 7.755,16.860 7.354,15.548 C7.267,15.278 7.289,15.014 7.419,14.759 C7.549,14.505 7.756,14.334 8.038,14.248 C8.310,14.160 8.573,14.183 8.828,14.313 C9.084,14.443 9.255,14.649 9.342,14.931 C9.613,15.798 10.116,16.499 10.849,17.036 C11.582,17.572 12.405,17.841 13.318,17.841 C14.230,17.841 15.053,17.572 15.786,17.036 C16.520,16.500 17.023,15.798 17.294,14.931 ZM9.146,10.557 C8.570,10.557 8.079,10.354 7.671,9.947 C7.263,9.541 7.060,9.051 7.060,8.476 C7.060,7.902 7.264,7.411 7.671,7.005 C8.078,6.598 8.570,6.395 9.146,6.395 C9.721,6.395 10.213,6.598 10.621,7.005 C11.028,7.411 11.232,7.902 11.232,8.476 C11.232,9.051 11.028,9.541 10.621,9.947 C10.213,10.354 9.721,10.557 9.146,10.557 Z"/>
                                                </svg>
                                            </el-button>
                                            <el-button class="">
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                    width="23px" height="25px">
                                                    <path fill-rule="evenodd"  fill="rgb(188, 194, 207)"
                                                    d="M22.025,8.967 C21.640,8.578 21.019,8.579 20.636,8.970 L8.830,21.042 C7.298,22.600 4.811,22.600 3.276,21.040 C1.743,19.480 1.743,16.950 3.276,15.390 L15.432,2.963 C16.388,1.990 17.943,1.990 18.902,2.965 C19.861,3.940 19.861,5.521 18.902,6.496 L8.832,16.737 C8.831,16.738 8.831,16.739 8.830,16.739 C8.446,17.127 7.826,17.127 7.444,16.738 C7.060,16.348 7.060,15.715 7.444,15.326 L12.304,10.381 C12.688,9.991 12.687,9.359 12.304,8.969 C11.920,8.578 11.299,8.579 10.915,8.969 L6.054,13.913 C4.904,15.083 4.904,16.980 6.055,18.150 C7.205,19.320 9.070,19.320 10.221,18.150 C10.222,18.149 10.223,18.147 10.225,18.146 L20.290,7.909 C22.017,6.153 22.017,3.308 20.290,1.552 C18.564,-0.203 15.766,-0.203 14.041,1.552 L1.885,13.979 C-0.413,16.317 -0.413,20.112 1.887,22.453 C4.189,24.793 7.920,24.793 10.221,22.453 L22.029,10.379 C22.411,9.988 22.410,9.356 22.025,8.967 Z"/>
                                                </svg>
                                            </el-button>
                                            <el-button class="">
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                    width="26px" height="25px">
                                                    <path fill-rule="evenodd"  fill="rgb(188, 194, 207)"
                                                    d="M24.943,1.355 L19.967,19.456 C19.687,20.477 19.452,21.311 19.447,21.309 C19.443,21.307 19.437,21.289 19.437,21.283 C19.436,21.278 18.625,20.946 17.634,20.548 L14.369,19.233 C13.378,18.832 11.750,18.192 10.754,17.811 L10.410,17.678 C9.414,17.294 9.196,16.348 9.928,15.577 L20.033,4.872 C20.764,4.098 20.673,4.001 19.833,4.657 L7.050,14.631 C6.207,15.284 4.701,15.496 3.708,15.107 L0.765,13.955 C-0.229,13.565 -0.268,12.844 0.680,12.352 L23.731,0.390 C24.678,-0.101 25.223,0.332 24.943,1.355 ZM7.775,17.976 L10.033,18.892 C11.023,19.292 11.356,20.347 10.771,21.234 C10.771,21.234 8.699,24.375 8.760,24.200 C8.818,24.024 6.722,19.025 6.721,19.025 C6.311,18.046 6.785,17.576 7.775,17.976 Z"/>
                                                </svg>
                                            </el-button>
                                        </div>
                                    </el-form>
                                </div>
                            </div>
                        </div>
                    </div>
               </div>
            </el-dropdown-menu>
        </el-dropdown>
    </li>
</template>