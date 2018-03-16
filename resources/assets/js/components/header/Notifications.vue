<template>
     <li>
        <el-dropdown trigger="click" placement="bottom">
            <el-badge :value="200" :max="99" class="item">
                <el-button size="small" class="el-dropdown-link">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="40px" height="42px">
                        <path fill-rule="evenodd"  fill="rgb(102, 115, 129)"
                        d="M38.046,33.082 C36.972,34.146 35.492,34.807 33.865,34.807 L27.583,34.807 C27.569,36.789 26.753,38.582 25.442,39.883 C24.120,41.195 22.292,42.007 20.274,42.007 C18.256,42.007 16.428,41.195 15.105,39.883 C13.794,38.582 12.979,36.789 12.965,34.807 L6.474,34.807 C4.843,34.807 3.362,34.146 2.289,33.082 C1.216,32.017 0.549,30.547 0.549,28.930 C0.549,27.318 1.216,25.849 2.289,24.782 L2.294,24.778 C3.090,23.989 4.111,23.421 5.246,23.181 C5.546,19.649 7.114,16.474 9.498,14.109 C11.248,12.373 13.438,11.072 15.888,10.386 C15.807,10.314 15.728,10.240 15.651,10.163 L15.646,10.159 C14.560,9.079 13.888,7.592 13.888,5.954 C13.888,4.315 14.560,2.828 15.646,1.749 L15.651,1.744 C16.739,0.667 18.238,-0.000 19.889,-0.000 C21.541,-0.000 23.040,0.667 24.128,1.744 L24.133,1.749 C25.219,2.828 25.891,4.315 25.891,5.954 C25.891,7.592 25.219,9.079 24.133,10.159 L24.128,10.163 C24.051,10.240 23.972,10.314 23.891,10.386 C26.341,11.072 28.530,12.373 30.281,14.109 C32.644,16.453 34.206,19.593 34.524,23.089 C35.892,23.240 37.124,23.858 38.050,24.778 C39.123,25.842 39.790,27.312 39.790,28.930 C39.790,30.544 39.123,32.012 38.050,33.077 L38.046,33.082 ZM22.397,3.471 L22.392,3.466 C21.755,2.834 20.870,2.442 19.889,2.442 C18.908,2.442 18.024,2.834 17.387,3.466 L17.382,3.471 C16.745,4.102 16.350,4.980 16.350,5.954 C16.350,6.927 16.745,7.805 17.382,8.436 L17.387,8.441 C18.024,9.073 18.908,9.465 19.889,9.465 C20.870,9.465 21.755,9.073 22.392,8.441 L22.397,8.436 C23.034,7.805 23.429,6.927 23.429,5.954 C23.429,4.980 23.034,4.102 22.397,3.471 ZM28.540,15.836 C26.326,13.640 23.268,12.281 19.889,12.281 C16.511,12.281 13.452,13.640 11.239,15.836 C9.330,17.729 8.049,20.246 7.733,23.052 L32.046,23.052 C31.730,20.246 30.448,17.729 28.540,15.836 ZM36.310,26.505 C35.682,25.881 34.816,25.495 33.865,25.495 L6.474,25.495 C5.520,25.495 4.654,25.880 4.030,26.500 L4.025,26.505 C3.400,27.125 3.012,27.983 3.012,28.930 C3.012,29.873 3.402,30.732 4.030,31.355 C4.657,31.978 5.523,32.365 6.474,32.365 L15.427,32.365 L15.427,34.807 C15.441,36.115 15.981,37.298 16.846,38.156 C17.723,39.026 18.935,39.564 20.274,39.564 C21.613,39.564 22.825,39.026 23.702,38.156 C24.567,37.298 25.106,36.115 25.120,34.808 L20.683,34.808 C20.003,34.808 19.452,34.261 19.452,33.586 C19.452,32.912 20.003,32.365 20.683,32.365 L23.121,32.365 L33.865,32.365 C34.818,32.365 35.684,31.978 36.312,31.357 C36.938,30.734 37.327,29.875 37.327,28.930 C37.327,27.986 36.938,27.128 36.310,26.505 Z"/>
                    </svg>
                </el-button>
            </el-badge>
            <el-dropdown-menu slot="dropdown" class="header-dropdown header-notifications buzz-scrollbar buzz-scroll">
                <el-dropdown-item>
                    <a href="#">
                        <div class="image">
                            <img src="img/temporary/user1.png" class="user-image">
                            <img src="img/icons/notifications/files.svg" class="type-image">
                        </div>
                        <div class="info">
                            <p>Brian Howar <span class="action"> Uploaded </span> Questionnaire for Client Buzzooka Inc. </p>
                            <span class="date"> 5 mins ago </span>
                        </div>
                    </a>
                </el-dropdown-item>
                <el-dropdown-item>
                     <a href="#">
                        <div class="image">
                            <img src="img/temporary/user2.png" class="user-image">
                            <img src="img/icons/notifications/images.svg" class="type-image">
                        </div>
                        <div class="info">
                            <p> Alan Podemski <span class="action"> Uploaded </span> 24 files for client x for following project </p>
                            <span class="date"> 1 hr ago </span>
                        </div>
                    </a>
                </el-dropdown-item>
                 <el-dropdown-item>
                    <a href="#">
                        <div class="image">
                            <img src="img/temporary/user3.png" class="user-image">
                            <img src="img/icons/notifications/files2.svg" class="type-image">
                        </div>
                        <div class="info">
                            <p> Jhon Smith <span class="action"> Shared </span> a GOOGLE SHEET and  GOOGLE DOC files </p>
                            <span class="date"> 3 hrs ago </span>
                        </div>
                    </a>
                </el-dropdown-item>
                <el-dropdown-item>
                    <a href="#">
                        <div class="image">
                            <img src="img/temporary/user1.png" class="user-image">
                            <img src="img/icons/notifications/edit.svg" class="type-image">
                        </div>
                        <div class="info">
                            <p> Tommy Jeffrey <span class="action"> Modified </span> folder for the client Y for the specific folder </p>
                            <span class="date"> 1 day ago </span>
                        </div>
                    </a>
                </el-dropdown-item>
                 <el-dropdown-item>
                    <a href="#">
                        <div class="image">
                            <img src="img/temporary/user1.png" class="user-image">
                            <img src="img/icons/notifications/files.svg" class="type-image">
                        </div>
                        <div class="info">
                            <p>Brian Howar <span class="action"> Uploaded </span> Questionnaire for Client Buzzooka Inc. </p>
                            <span class="date"> 5 mins ago </span>
                        </div>
                    </a>
                </el-dropdown-item>
                <el-dropdown-item>
                     <a href="#">
                        <div class="image">
                            <img src="img/temporary/user2.png" class="user-image">
                            <img src="img/icons/notifications/images.svg" class="type-image">
                        </div>
                        <div class="info">
                            <p> Alan Podemski <span class="action"> Uploaded </span> 24 files for client x for following project </p>
                            <span class="date"> 1 hr ago </span>
                        </div>
                    </a>
                </el-dropdown-item>
                 <el-dropdown-item>
                    <a href="#">
                        <div class="image">
                            <img src="img/temporary/user3.png" class="user-image">
                            <img src="img/icons/notifications/files2.svg" class="type-image">
                        </div>
                        <div class="info">
                            <p> Jhon Smith <span class="action"> Shared </span> a GOOGLE SHEET and  GOOGLE DOC files </p>
                            <span class="date"> 3 hrs ago </span>
                        </div>
                    </a>
                </el-dropdown-item>
                <el-dropdown-item>
                    <a href="#">
                        <div class="image">
                            <img src="img/temporary/user1.png" class="user-image">
                            <img src="img/icons/notifications/edit.svg" class="type-image">
                        </div>
                        <div class="info">
                            <p> Tommy Jeffrey <span class="action"> Modified </span> folder for the client Y for the specific folder </p>
                            <span class="date"> 1 day ago </span>
                        </div>
                    </a>
                </el-dropdown-item>
                 <el-dropdown-item>
                    <a href="#">
                        <div class="image">
                            <img src="img/temporary/user1.png" class="user-image">
                            <img src="img/icons/notifications/files.svg" class="type-image">
                        </div>
                        <div class="info">
                            <p>Brian Howar <span class="action"> Uploaded </span> Questionnaire for Client Buzzooka Inc. </p>
                            <span class="date"> 5 mins ago </span>
                        </div>
                    </a>
                </el-dropdown-item>
                <el-dropdown-item>
                     <a href="#">
                        <div class="image">
                            <img src="img/temporary/user2.png" class="user-image">
                            <img src="img/icons/notifications/images.svg" class="type-image">
                        </div>
                        <div class="info">
                            <p> Alan Podemski <span class="action"> Uploaded </span> 24 files for client x for following project </p>
                            <span class="date"> 1 hr ago </span>
                        </div>
                    </a>
                </el-dropdown-item>
                 <el-dropdown-item>
                    <a href="#">
                        <div class="image">
                            <img src="img/temporary/user3.png" class="user-image">
                            <img src="img/icons/notifications/files2.svg" class="type-image">
                        </div>
                        <div class="info">
                            <p> Jhon Smith <span class="action"> Shared </span> a GOOGLE SHEET and  GOOGLE DOC files </p>
                            <span class="date"> 3 hrs ago </span>
                        </div>
                    </a>
                </el-dropdown-item>
                <el-dropdown-item>
                    <a href="#">
                        <div class="image">
                            <img src="img/temporary/user1.png" class="user-image">
                            <img src="img/icons/notifications/edit.svg" class="type-image">
                        </div>
                        <div class="info">
                            <p> Tommy Jeffrey <span class="action"> Modified </span> folder for the client Y for the specific folder </p>
                            <span class="date"> 1 day ago </span>
                        </div>
                    </a>
                </el-dropdown-item>
                 <el-dropdown-item>
                    <a href="#">
                        <div class="image">
                            <img src="img/temporary/user1.png" class="user-image">
                            <img src="img/icons/notifications/files.svg" class="type-image">
                        </div>
                        <div class="info">
                            <p>Brian Howar <span class="action"> Uploaded </span> Questionnaire for Client Buzzooka Inc. </p>
                            <span class="date"> 5 mins ago </span>
                        </div>
                    </a>
                </el-dropdown-item>
                <el-dropdown-item>
                     <a href="#">
                        <div class="image">
                            <img src="img/temporary/user2.png" class="user-image">
                            <img src="img/icons/notifications/images.svg" class="type-image">
                        </div>
                        <div class="info">
                            <p> Alan Podemski <span class="action"> Uploaded </span> 24 files for client x for following project </p>
                            <span class="date"> 1 hr ago </span>
                        </div>
                    </a>
                </el-dropdown-item>
                 <el-dropdown-item>
                    <a href="#">
                        <div class="image">
                            <img src="img/temporary/user3.png" class="user-image">
                            <img src="img/icons/notifications/files2.svg" class="type-image">
                        </div>
                        <div class="info">
                            <p> Jhon Smith <span class="action"> Shared </span> a GOOGLE SHEET and  GOOGLE DOC files </p>
                            <span class="date"> 3 hrs ago </span>
                        </div>
                    </a>
                </el-dropdown-item>
                <el-dropdown-item>
                    <a href="#">
                        <div class="image">
                            <img src="img/temporary/user1.png" class="user-image">
                            <img src="img/icons/notifications/edit.svg" class="type-image">
                        </div>
                        <div class="info">
                            <p> Tommy Jeffrey <span class="action"> Modified </span> folder for the client Y for the specific folder </p>
                            <span class="date"> 1 day ago </span>
                        </div>
                    </a>
                </el-dropdown-item>
            </el-dropdown-menu>
        </el-dropdown>
        <!-- <ul class="dropdown-menu header-dropdown notifications buzz-scrollbar" id="buzz-scroll">
            <li>
                <a href="#">
                    <div class="image">
                        <img src="img/temporary/user1.png" class="user-image">
                        <img src="img/icons/notifications/files.svg" class="type-image">
                    </div>
                    <div class="info">
                        <p>Brian Howar <span class="action"> Uploaded </span> Questionnaire for Client Buzzooka Inc. </p>
                        <span class="date"> 5 mins ago </span>
                    </div>
                </a>
            </li>
            <li>
                <a href="#">
                    <div class="image">
                        <img src="img/temporary/user2.png" class="user-image">
                        <img src="img/icons/notifications/images.svg" class="type-image">
                    </div>
                    <div class="info">
                        <p> Alan Podemski <span class="action"> Uploaded </span> 24 files for client x for following project </p>
                        <span class="date"> 1 hr ago </span>
                    </div>
                </a>
            </li>
            <li>
                <a href="#">
                    <div class="image">
                        <img src="img/temporary/user3.png" class="user-image">
                        <img src="img/icons/notifications/files2.svg" class="type-image">
                    </div>
                    <div class="info">
                        <p> Jhon Smith <span class="action"> Shared </span> a GOOGLE SHEET and  GOOGLE DOC files </p>
                        <span class="date"> 3 hrs ago </span>
                    </div>
                </a>
            </li>
            <li>
                <a href="#">
                    <div class="image">
                        <img src="img/temporary/user1.png" class="user-image">
                        <img src="img/icons/notifications/edit.svg" class="type-image">
                    </div>
                    <div class="info">
                        <p> Tommy Jeffrey <span class="action"> Modified </span> folder for the client Y for the specific folder </p>
                        <span class="date"> 1 day ago </span>
                    </div>
                </a>
            </li>
            <li>
                <a href="#">
                    <div class="image">
                        <img src="img/temporary/user1.png" class="user-image">
                        <img src="img/icons/notifications/files.svg" class="type-image">
                    </div>
                    <div class="info">
                        <p>Brian Howar <span class="action"> Uploaded </span> Questionnaire for Client Buzzooka Inc. </p>
                        <span class="date"> 5 mins ago </span>
                    </div>
                </a>
            </li>
            <li>
                <a href="#">
                    <div class="image">
                        <img src="img/temporary/user2.png" class="user-image">
                        <img src="img/icons/notifications/images.svg" class="type-image">
                    </div>
                    <div class="info">
                        <p> Alan Podemski <span class="action"> Uploaded </span> 24 files for client x for following project </p>
                        <span class="date"> 1 hr ago </span>
                    </div>
                </a>
            </li>
            <li>
                <a href="#">
                    <div class="image">
                        <img src="img/temporary/user3.png" class="user-image">
                        <img src="img/icons/notifications/files2.svg" class="type-image">
                    </div>
                    <div class="info">
                        <p> Jhon Smith <span class="action"> Shared </span> a GOOGLE SHEET and  GOOGLE DOC files </p>
                        <span class="date"> 3 hrs ago </span>
                    </div>
                </a>
            </li>
            <li>
                <a href="#">
                    <div class="image">
                        <img src="img/temporary/user1.png" class="user-image">
                        <img src="img/icons/notifications/edit.svg" class="type-image">
                    </div>
                    <div class="info">
                        <p> Tommy Jeffrey <span class="action"> Modified </span> folder for the client Y for the specific folder </p>
                        <span class="date"> 1 day ago </span>
                    </div>
                </a>
            </li>
        </ul> -->
    </li>
</template>