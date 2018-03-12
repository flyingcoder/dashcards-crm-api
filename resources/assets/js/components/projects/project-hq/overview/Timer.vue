<template>
  <div class="buzz-box">
    <div class="box-head">
        <h1>Timer</h1>
        <div class="box-options">
            <div class="option-item cut-option">
                <a href="#">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="19px">
                    <path fill-rule="evenodd"  fill="rgb(102, 115, 129)"
                    d="M18.664,2.291 C19.549,3.175 19.993,4.336 19.996,5.497 C19.999,6.658 19.559,7.820 18.678,8.708 C17.797,9.595 16.640,10.042 15.482,10.044 C14.561,10.046 13.640,9.768 12.856,9.210 L11.687,10.387 L9.567,10.387 L11.792,8.147 C11.232,7.363 10.950,6.440 10.948,5.517 C10.946,4.356 11.385,3.194 12.266,2.306 C13.147,1.418 14.304,0.972 15.462,0.970 C16.620,0.967 17.779,1.408 18.664,2.291 L18.664,2.291 ZM0.751,12.416 C0.336,12.416 -0.000,12.079 -0.000,11.663 C-0.000,11.248 0.336,10.910 0.751,10.910 L18.548,10.910 C18.963,10.910 19.299,11.248 19.299,11.663 C19.299,12.079 18.963,12.416 18.548,12.416 L0.751,12.416 L0.751,12.416 ZM8.958,13.136 L3.469,18.665 C3.176,18.961 2.701,18.961 2.407,18.668 C2.113,18.375 2.112,17.898 2.405,17.603 L6.839,13.136 L8.958,13.136 L8.958,13.136 Z"/>
                </svg>
                </a>
            </div>
            <div class="option-item minimize-option">
                <a href="#">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="21px" height="3px">
                    <path fill-rule="evenodd"  fill="rgb(102, 115, 129)"
                    d="M0.813,2.932 C0.362,2.932 -0.004,2.486 -0.004,1.936 C-0.004,1.385 0.362,0.939 0.813,0.939 L20.184,0.939 C20.635,0.939 21.001,1.385 21.001,1.936 C21.001,2.486 20.635,2.932 20.184,2.932 L0.813,2.932 Z"/>
                </svg>
                </a>
            </div>
            <div class="option-item expand-option">
                <a href="#">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="17px">
                    <path fill-rule="evenodd"  fill="rgb(102, 115, 129)"
                    d="M6.450,15.170 C6.503,15.490 6.289,15.793 5.972,15.847 L1.305,16.634 C0.877,16.741 0.470,16.331 0.576,15.899 L1.356,11.190 C1.410,10.870 1.710,10.654 2.028,10.708 C2.344,10.762 2.558,11.065 2.505,11.385 L2.018,14.325 L5.887,10.421 C6.432,9.871 7.279,10.725 6.734,11.276 L2.865,15.180 L5.778,14.688 C6.096,14.634 6.396,14.851 6.450,15.170 ZM6.158,6.515 L2.289,2.611 L2.776,5.551 C2.830,5.871 2.616,6.174 2.299,6.228 C1.982,6.282 1.681,6.066 1.628,5.746 L0.848,1.037 C0.742,0.605 1.148,0.195 1.577,0.302 L6.243,1.089 C6.560,1.142 6.774,1.446 6.721,1.766 C6.667,2.086 6.367,2.302 6.050,2.248 L3.136,1.756 L7.005,5.661 C7.550,6.210 6.704,7.065 6.158,6.515 ZM16.304,16.634 L11.638,15.847 C11.321,15.793 11.107,15.490 11.160,15.170 C11.214,14.850 11.514,14.634 11.831,14.688 L14.745,15.180 L10.876,11.276 C10.331,10.725 11.178,9.871 11.723,10.421 L15.592,14.325 L15.105,11.385 C15.051,11.065 15.265,10.762 15.583,10.708 C15.899,10.654 16.200,10.870 16.253,11.190 L17.033,15.899 C17.139,16.331 16.733,16.741 16.304,16.634 ZM10.889,1.766 C10.835,1.446 11.049,1.142 11.366,1.089 L16.033,0.302 C16.462,0.195 16.868,0.605 16.762,1.037 L15.982,5.746 C15.929,6.066 15.628,6.282 15.311,6.228 C14.994,6.174 14.780,5.871 14.833,5.551 L15.320,2.611 L11.451,6.515 C10.906,7.065 10.060,6.210 10.605,5.661 L14.474,1.756 L11.560,2.248 C11.243,2.302 10.942,2.086 10.889,1.766 Z"/>
                </svg>
                </a>
            </div>
            <div class="option-item x-option">
                <a href="#">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="13px" height="14px">
                    <path fill-rule="evenodd"  fill="rgb(102, 115, 129)"
                    d="M12.738,12.781 C12.404,13.114 11.863,13.114 11.529,12.781 L6.498,7.770 L1.466,12.781 C1.132,13.114 0.591,13.114 0.257,12.781 C-0.078,12.448 -0.078,11.908 0.257,11.576 L5.288,6.565 L0.257,1.555 C-0.078,1.222 -0.078,0.683 0.257,0.350 C0.591,0.017 1.132,0.017 1.466,0.350 L6.498,5.360 L11.529,0.350 C11.863,0.017 12.404,0.017 12.738,0.350 C13.072,0.683 13.072,1.222 12.738,1.555 L7.707,6.565 L12.738,11.576 C13.072,11.908 13.072,12.448 12.738,12.781 Z"/>
                </svg>
                </a>
            </div>
        </div>
    </div>
    <div class="box-content db-timer">
        <div class="header-content">
            <div class="tab-pane fade alarm-info hide">
                <span>Alarm</span>
            </div>
            <div class="tab-pane fade timer-info show">
                <span>Timer</span>
            </div>
        </div>
        <div class="timer-content">
            <div class="tabbable" id="timer-tabs">
                <ul class="nav nav-tabs">
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            width="28px" height="31px">
                            <path fill-rule="evenodd"  fill="rgb(144, 149, 169)" d="M14.442,3.478 C18.165,3.478 21.537,5.019 23.977,7.509 C26.417,9.999 27.926,13.440 27.926,17.240 C27.926,21.040 26.417,24.481 23.977,26.971 C21.537,29.462 18.165,31.003 14.442,31.003 C10.719,31.003 7.348,29.462 4.908,26.971 C2.467,24.481 0.958,21.040 0.958,17.240 C0.958,13.440 2.467,9.999 4.908,7.509 C7.348,5.019 10.719,3.478 14.442,3.478 L14.442,3.478 ZM12.756,10.278 L12.741,18.580 C12.739,19.214 13.205,19.738 13.808,19.811 L13.824,19.814 L13.827,19.814 C13.872,19.819 13.917,19.822 13.962,19.822 L20.497,19.822 C21.169,19.822 21.713,19.266 21.713,18.581 C21.713,17.896 21.169,17.340 20.497,17.340 L15.165,17.340 L15.178,10.287 C15.181,9.605 14.641,9.050 13.972,9.047 C13.303,9.044 12.759,9.596 12.756,10.278 L12.756,10.278 ZM26.001,7.932 C27.811,10.031 29.288,4.255 25.130,1.341 C20.805,-1.691 15.525,1.193 17.842,2.321 C21.621,4.157 22.983,4.433 26.001,7.932 L26.001,7.932 ZM2.744,7.932 C5.762,4.433 7.124,4.157 10.904,2.321 C13.220,1.193 7.940,-1.691 3.616,1.341 C-0.542,4.255 0.935,10.031 2.744,7.932 L2.744,7.932 ZM22.258,9.264 C20.258,7.223 17.495,5.960 14.442,5.960 C11.390,5.960 8.626,7.223 6.627,9.264 C4.627,11.305 3.390,14.125 3.390,17.240 C3.390,20.356 4.627,23.176 6.627,25.217 C8.626,27.258 11.390,28.521 14.442,28.521 C17.495,28.521 20.258,27.258 22.258,25.217 C24.257,23.176 25.495,20.356 25.495,17.240 C25.495,14.125 24.257,11.305 22.258,9.264 Z"/>
                        </svg>
                        <a href="#alarm" data-toggle="tab"> Alarm </a>
                        <label class="color"> <span> 25 </span> </label>
                    </li>
                    <li class="active">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            width="33px" height="31px">
                            <path fill-rule="evenodd"  fill="rgb(144, 149, 169)"
                            d="M16.876,-0.009 C21.291,-0.009 25.287,1.727 28.181,4.533 C31.073,7.339 32.864,11.216 32.864,15.498 C32.864,19.779 31.073,23.657 28.181,26.462 C25.287,29.268 21.291,31.005 16.876,31.005 C12.462,31.005 8.464,29.268 5.572,26.462 C2.678,23.657 0.889,19.779 0.889,15.498 C0.889,11.216 2.678,7.339 5.572,4.533 C8.464,1.727 12.462,-0.009 16.876,-0.009 L16.876,-0.009 ZM18.202,12.677 L18.213,5.850 C18.216,5.133 17.619,4.550 16.880,4.547 C16.141,4.544 15.539,5.124 15.537,5.840 L15.525,12.689 C14.444,13.184 13.696,14.256 13.696,15.498 C13.696,16.693 14.389,17.730 15.404,18.248 C15.871,18.511 16.412,18.661 16.990,18.661 C18.165,18.661 19.192,18.039 19.742,17.113 L25.380,17.113 C26.123,17.113 26.725,16.530 26.725,15.810 C26.725,15.090 26.123,14.507 25.380,14.507 L19.982,14.507 C19.711,13.773 19.165,13.168 18.465,12.810 C18.380,12.762 18.292,12.718 18.202,12.677 L18.202,12.677 ZM26.281,6.376 C23.875,4.042 20.549,2.597 16.876,2.597 C13.203,2.597 9.878,4.042 7.472,6.376 C5.064,8.710 3.576,11.935 3.576,15.498 C3.576,19.060 5.064,22.286 7.472,24.620 C9.878,26.954 13.203,28.398 16.876,28.398 C20.549,28.398 23.875,26.954 26.281,24.620 C28.687,22.286 30.176,19.060 30.176,15.498 C30.176,11.935 28.687,8.710 26.281,6.376 Z"/>
                        </svg>
                        <a href="#timer" data-toggle="tab"> Timer </a>
                        <label class="color"> <span> 14 </span> </label>
                    </li>
                </ul>
            </div>
            <div class="tab-content">
                <div class="tab-pane fade alarm-table" id="alarm">
                    <table>
                        <tbody>
                            <tr>
                                <td>  <div class="color orange-6"> </div> </td>
                                <td>  <span> Product Roadmap 02 </span> </td>
                                <td>  <span> Dec. 12 &nbsp; </span> | <span> &nbsp; 12:00 PM - 1:30 PM</span> </td>
                                <td>  
                                    <a href="">
                                        <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" version="1.1" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
                                            viewBox="0 0 250 250"
                                            xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <g id="Layer_x0020_1">
                                            <metadata id="CorelCorpID_0Corel-Layer"/>
                                            <path class="fil0" d="M192 10l54 56c4,5 4,13 -1,18l-18 17c-5,5 -13,5 -17,0l-54 -56c-5,-5 -5,-13 0,-18l18 -17c5,-5 13,-5 18,0zm-140 202l40 -13 -39 -41 -16 38 15 16zm99 -152l43 45c8,8 7,21 -1,29l-80 77c-1,0 -92,30 -100,32 -2,1 -5,1 -7,0 -4,-2 -6,-7 -4,-12l40 -94 80 -77c8,-8 21,-8 29,0z"/>
                                            </g>
                                        </svg>
                                    </a>
                                    <a href="#">
                                        <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" version="1.1" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
                                            viewBox="0 0 250 250"
                                            xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <g id="Layer_x0020_1">
                                            <metadata id="CorelCorpID_0Corel-Layer"/>
                                            <path class="fil0" d="M61 83l129 0c6,0 11,5 10,10l-3 146c-1,6 -5,11 -11,11l-121 0c-6,0 -11,-5 -11,-11l-4 -146c0,-5 5,-10 11,-10zm37 -83l54 0c5,0 9,2 12,5l0 0c3,3 4,7 4,11l0 10 33 0c6,0 11,4 11,10l0 23c0,6 -5,11 -11,11l-152 0c-6,0 -11,-5 -11,-11l0 -23c0,-6 5,-10 11,-10l33 0 0 -10c0,-4 2,-8 5,-11 3,-3 7,-5 11,-5zm1 26l53 0 0 -9 -53 0 0 9zm-5 83l0 0c5,0 9,4 9,10l0 95c0,5 -4,9 -9,9l0 0c-6,0 -10,-4 -10,-9l0 -95c0,-6 4,-10 10,-10zm64 0l0 0c5,0 9,4 9,10l0 95c0,5 -4,9 -9,9l0 0c-5,0 -10,-4 -10,-9l0 -95c0,-6 5,-10 10,-10zm-32 0l0 0c5,0 9,4 9,10l0 95c0,5 -4,9 -9,9l0 0c-5,0 -10,-4 -10,-9l0 -95c0,-6 5,-10 10,-10z"/>
                                            </g>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>  <div class="color blue-5"> </div> </td>
                                <td>  <span> XYZ Ranking Report </span> </td>
                                <td>  <span> Dec. 14 &nbsp; </span> | <span> &nbsp; 2:00 PM - 3:30 PM</span> </td>
                                <td>  
                                    <a href="">
                                        <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" version="1.1" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
                                        viewBox="0 0 250 250"
                                        xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <g id="Layer_x0020_1">
                                        <metadata id="CorelCorpID_0Corel-Layer"/>
                                        <path class="fil0" d="M192 10l54 56c4,5 4,13 -1,18l-18 17c-5,5 -13,5 -17,0l-54 -56c-5,-5 -5,-13 0,-18l18 -17c5,-5 13,-5 18,0zm-140 202l40 -13 -39 -41 -16 38 15 16zm99 -152l43 45c8,8 7,21 -1,29l-80 77c-1,0 -92,30 -100,32 -2,1 -5,1 -7,0 -4,-2 -6,-7 -4,-12l40 -94 80 -77c8,-8 21,-8 29,0z"/>
                                        </g>
                                        </svg>
                                    </a>
                                    <a href="#">
                                        <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" version="1.1" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
                                        viewBox="0 0 250 250"
                                        xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <g id="Layer_x0020_1">
                                        <metadata id="CorelCorpID_0Corel-Layer"/>
                                        <path class="fil0" d="M61 83l129 0c6,0 11,5 10,10l-3 146c-1,6 -5,11 -11,11l-121 0c-6,0 -11,-5 -11,-11l-4 -146c0,-5 5,-10 11,-10zm37 -83l54 0c5,0 9,2 12,5l0 0c3,3 4,7 4,11l0 10 33 0c6,0 11,4 11,10l0 23c0,6 -5,11 -11,11l-152 0c-6,0 -11,-5 -11,-11l0 -23c0,-6 5,-10 11,-10l33 0 0 -10c0,-4 2,-8 5,-11 3,-3 7,-5 11,-5zm1 26l53 0 0 -9 -53 0 0 9zm-5 83l0 0c5,0 9,4 9,10l0 95c0,5 -4,9 -9,9l0 0c-6,0 -10,-4 -10,-9l0 -95c0,-6 4,-10 10,-10zm64 0l0 0c5,0 9,4 9,10l0 95c0,5 -4,9 -9,9l0 0c-5,0 -10,-4 -10,-9l0 -95c0,-6 5,-10 10,-10zm-32 0l0 0c5,0 9,4 9,10l0 95c0,5 -4,9 -9,9l0 0c-5,0 -10,-4 -10,-9l0 -95c0,-6 5,-10 10,-10z"/>
                                        </g>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>  <div class="color green-4"> </div> </td>
                                <td>  <span> Project brainstorming </span> </td>
                                <td>  <span> Dec. 18 &nbsp; </span> | <span> &nbsp;  3:00 PM - 4:30 PM</span> </td>
                                <td>  
                                    <a href="">
                                    <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" version="1.1" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
                                        viewBox="0 0 250 250"
                                        xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <g id="Layer_x0020_1">
                                        <metadata id="CorelCorpID_0Corel-Layer"/>
                                        <path class="fil0" d="M192 10l54 56c4,5 4,13 -1,18l-18 17c-5,5 -13,5 -17,0l-54 -56c-5,-5 -5,-13 0,-18l18 -17c5,-5 13,-5 18,0zm-140 202l40 -13 -39 -41 -16 38 15 16zm99 -152l43 45c8,8 7,21 -1,29l-80 77c-1,0 -92,30 -100,32 -2,1 -5,1 -7,0 -4,-2 -6,-7 -4,-12l40 -94 80 -77c8,-8 21,-8 29,0z"/>
                                        </g>
                                    </svg>
                                    </a>
                                    <a href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" version="1.1" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
                                        viewBox="0 0 250 250"
                                        xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <g id="Layer_x0020_1">
                                        <metadata id="CorelCorpID_0Corel-Layer"/>
                                        <path class="fil0" d="M61 83l129 0c6,0 11,5 10,10l-3 146c-1,6 -5,11 -11,11l-121 0c-6,0 -11,-5 -11,-11l-4 -146c0,-5 5,-10 11,-10zm37 -83l54 0c5,0 9,2 12,5l0 0c3,3 4,7 4,11l0 10 33 0c6,0 11,4 11,10l0 23c0,6 -5,11 -11,11l-152 0c-6,0 -11,-5 -11,-11l0 -23c0,-6 5,-10 11,-10l33 0 0 -10c0,-4 2,-8 5,-11 3,-3 7,-5 11,-5zm1 26l53 0 0 -9 -53 0 0 9zm-5 83l0 0c5,0 9,4 9,10l0 95c0,5 -4,9 -9,9l0 0c-6,0 -10,-4 -10,-9l0 -95c0,-6 4,-10 10,-10zm64 0l0 0c5,0 9,4 9,10l0 95c0,5 -4,9 -9,9l0 0c-5,0 -10,-4 -10,-9l0 -95c0,-6 5,-10 10,-10zm-32 0l0 0c5,0 9,4 9,10l0 95c0,5 -4,9 -9,9l0 0c-5,0 -10,-4 -10,-9l0 -95c0,-6 5,-10 10,-10z"/>
                                        </g>
                                    </svg>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade in show active" id="timer">
                    <table>
                        <tbody>
                            <tr>
                                <td>  <img src="img/temporary/user1.png">  </td>
                                <td class="buzz-overflow"> <span> Brian McBurn </span> </td>
                                <td class="buzz-overflow">  <span> Website Design Wireframe Website Design Wireframe </span> </td>
                                <td>  <span> 2:25:03 </span> </td>
                            </tr>
                            <tr>
                                <td>  <img src="img/temporary/user2.png">  </td>
                                <td class="buzz-overflow">  <span> William Stomhson </span> </td>
                                <td class="buzz-overflow">  <span> Social Media </span> </td>
                                <td>  <span> 3:25:30 </span> </td>
                            </tr>
                            <tr>
                                <td>  <img src="img/temporary/user3.png">  </td>
                                <td class="buzz-overflow">  <span> Jhon Doe </span> </td>
                                <td class="buzz-overflow">  <span> Facebook Marketing </span> </td>
                                <td>  <span> 3:12:09 </span> </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <a href=""> View More </a>
    </div>
</div>
</template>

<script>
export default {

}
</script>

<style>

</style>
