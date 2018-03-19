<template>
    <li>
        <el-dropdown trigger="click" placement="bottom-start">
            <el-button size="small" class="el-dropdown-link">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="41px" height="41px">
                    <path fill-rule="evenodd"  fill="rgb(102, 115, 129)"
                    d="M35.876,9.315 C33.535,9.315 31.637,7.400 31.637,5.039 C31.637,2.677 33.535,0.763 35.876,0.763 C38.217,0.763 40.114,2.677 40.114,5.039 C40.114,7.400 38.217,9.315 35.876,9.315 ZM18.951,12.448 C19.623,12.448 20.168,12.997 20.168,13.675 L20.168,19.734 L26.175,19.734 C26.846,19.734 27.391,20.284 27.391,20.962 C27.391,21.639 26.846,22.189 26.175,22.189 L20.168,22.189 L20.168,28.248 C20.168,28.926 19.623,29.476 18.951,29.476 C18.279,29.476 17.734,28.926 17.734,28.248 L17.734,22.189 L11.728,22.189 C11.056,22.189 10.511,21.639 10.511,20.962 C10.511,20.284 11.056,19.734 11.728,19.734 L17.734,19.734 L17.734,13.675 C17.734,12.997 18.279,12.448 18.951,12.448 ZM28.344,4.993 C20.949,4.993 12.966,5.000 5.565,5.000 C4.703,5.000 3.919,5.356 3.350,5.930 C2.781,6.504 2.428,7.295 2.428,8.164 L2.428,34.517 C2.428,35.388 2.780,36.180 3.348,36.753 L3.350,36.751 C3.919,37.325 4.703,37.682 5.565,37.682 L33.023,37.682 C33.887,37.682 34.671,37.326 35.241,36.753 C35.808,36.180 36.160,35.388 36.160,34.517 C36.160,26.242 36.158,21.503 36.158,13.228 C36.158,12.551 36.703,12.001 37.375,12.001 C38.046,12.001 38.591,12.551 38.591,13.228 C38.591,21.503 38.594,26.241 38.594,34.517 C38.594,36.059 37.967,37.464 36.958,38.482 L36.954,38.486 C35.944,39.504 34.552,40.136 33.023,40.136 L5.565,40.136 C4.032,40.136 2.639,39.504 1.630,38.486 L1.630,38.482 C0.621,37.464 -0.005,36.059 -0.005,34.517 L-0.005,8.164 C-0.005,6.617 0.621,5.213 1.630,4.194 C2.639,3.177 4.032,2.545 5.565,2.545 C12.963,2.545 20.946,2.538 28.344,2.538 C29.016,2.538 29.561,3.088 29.561,3.765 C29.561,4.443 29.016,4.993 28.344,4.993 Z"/>
                </svg>
            </el-button>
            <el-dropdown-menu slot="dropdown" class="header-dropdown header-data_box">
                <div class="row">
                    <div class="col-md-12">
                        <h1> Data Boxes </h1>
                        <div class="template-checkbox all-template">
                            <input type="checkbox" value="none" id="all" name="check" checked="checked">
                            <label for="all"></label>
                            <span> Add All Data Box </span >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="template-box">
                            <div class="template-checkbox">
                                <input type="checkbox" value="none" id="task" name="check" checked="checked">
                                <label for="task"></label>
                            </div>
                            <div class="template-image">
                                <img src="img/icons/templates/task.svg" alt="task">
                            </div>
                            <div class="template-name">
                                <h3> Task </h3>
                            </div>
                        </div>
                        <div class="template-box">
                            <div class="template-checkbox">
                                <input type="checkbox" value="none" id="client" name="check" checked="checked">
                                <label for="client"></label>
                            </div>
                            <div class="template-image">
                                <img src="img/icons/templates/client.svg" alt="client">
                            </div>
                            <div class="template-name">
                                <h3> Client </h3>
                            </div>
                        </div>
                        <div class="template-box">
                            <div class="template-checkbox">
                                <input type="checkbox" value="none" id="payment" name="check" checked="checked">
                                <label for="payment"></label>
                            </div>
                            <div class="template-image">
                                <img src="img/icons/templates/payment.svg" alt="payment">
                            </div>
                            <div class="template-name">
                                <h3> Payment </h3>
                            </div>
                        </div>
                        <div class="template-box">
                            <div class="template-checkbox">
                                <input type="checkbox" value="none" id="calendar" name="check" checked="checked">
                                <label for="calendar"></label>
                            </div>
                            <div class="template-image">
                                <img src="img/icons/templates/calendar.svg" alt="calendar">
                            </div>
                            <div class="template-name">
                                <h3> Calendar </h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="template-box">
                            <div class="template-checkbox">
                                <input type="checkbox" value="none" id="timeline" name="check" checked="checked">
                                <label for="timeline"></label>
                            </div>
                            <div class="template-image">
                                <img src="img/icons/templates/timeline.svg" alt="timeline">
                            </div>
                            <div class="template-name">
                                <h3> Timeline </h3>
                            </div>
                        </div>
                        <div class="template-box">
                            <div class="template-checkbox">
                                <input type="checkbox" value="none" id="timer" name="check" checked="checked">
                                <label for="timer"></label>
                            </div>
                            <div class="template-image">
                                <img src="img/icons/templates/timer.svg" alt="timer">
                            </div>
                            <div class="template-name">
                                <h3> Timer </h3>
                            </div>
                        </div>
                        <div class="template-box">
                            <div class="template-checkbox">
                                <input type="checkbox" value="none" id="invoice" name="check" checked="checked">
                                <label for="invoice"></label>
                            </div>
                            <div class="template-image">
                                <img src="img/icons/templates/invoice.svg" alt="invoice">
                            </div>
                            <div class="template-name">
                                <h3> Invoice </h3>
                            </div>
                        </div>
                        <div class="template-box">
                            <div class="template-checkbox">
                                <input type="checkbox" value="none" id="passbox" name="check" checked="checked">
                                <label for="passbox"></label>
                            </div>
                            <div class="template-image">
                                <img src="img/icons/templates/passbox.svg" alt="passbox">
                            </div>
                            <div class="template-name">
                                <h3> Passbox </h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <button class="reset-template"> Reset Default </button>
                    </div>
                    <div class="col-md-6">
                        <button class="save-template"> Save </button>
                    </div>
                </div>
            </el-dropdown-menu>
        </el-dropdown>
    </li>
</template>