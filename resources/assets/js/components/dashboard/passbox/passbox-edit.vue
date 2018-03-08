<template>
    <div class="passbox-content passbox-edit">
        <div class="passbox-text">
            <span> PASS  </span>
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                width="57px" height="61px">
                <path fill-rule="evenodd"  fill="rgb(59, 88, 158)"
                d="M6.177,18.528 L7.368,18.528 L7.368,7.273 C7.368,5.337 8.245,3.579 9.657,2.304 C11.069,1.031 13.019,0.240 15.164,0.240 L41.472,0.240 C43.617,0.240 45.566,1.031 46.979,2.304 C48.391,3.579 49.267,5.337 49.267,7.273 L49.267,18.528 L51.051,18.528 C54.290,18.528 56.940,20.918 56.940,23.840 L56.940,55.531 C56.940,58.453 54.290,60.843 51.051,60.843 L6.177,60.843 C2.938,60.843 0.289,58.453 0.289,55.531 L0.289,23.840 C0.289,20.918 2.938,18.528 6.177,18.528 L6.177,18.528 ZM10.752,18.528 L45.883,18.528 L45.883,7.273 C45.883,6.180 45.386,5.185 44.586,4.463 C43.786,3.741 42.683,3.293 41.472,3.293 L15.164,3.293 C13.953,3.293 12.850,3.741 12.049,4.463 C11.249,5.185 10.752,6.180 10.752,7.273 L10.752,18.528 L10.752,18.528 ZM28.614,39.239 C25.740,39.239 23.411,41.340 23.411,43.933 C23.411,45.694 24.486,47.228 26.076,48.031 L26.076,52.832 C26.076,54.091 27.218,55.122 28.614,55.122 L28.614,55.122 C30.010,55.122 31.152,54.091 31.152,52.832 L31.152,48.031 C32.742,47.228 33.817,45.694 33.817,43.933 C33.817,41.340 31.488,39.239 28.614,39.239 Z"/>
            </svg>
            <span> BOX </span> 
        </div> 
        <div class="passbox-edit-form">
            <form>
                <div class="form-group buzz-group">
                    <label for="url">
                       URL
                    </label>
                    <input type="url" class="form-control" id="URL" />
                </div>
                <div class="form-group buzz-group">
                    <label for="name">
                       Name
                    </label>
                    <input type="text" class="form-control" id="name" />
                </div>
                <div class="form-group buzz-group buzz-addon select">
                    <label for="category">
                       Category
                    </label>
                    <input type="text" class="form-control" id="category" />
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                        width="25px" height="12px">
                        <path fill-rule="evenodd"  fill="rgb(179, 182, 195)"
                        d="M23.963,1.153 C23.642,0.888 23.120,0.888 22.799,1.153 L12.571,9.638 L2.322,1.153 C2.001,0.888 1.479,0.888 1.159,1.153 C0.838,1.419 0.838,1.851 1.159,2.117 L11.969,11.066 C12.129,11.199 12.330,11.265 12.551,11.265 C12.751,11.265 12.972,11.199 13.132,11.066 L23.943,2.117 C24.283,1.851 24.283,1.419 23.963,1.153 Z"/>
                    </svg>
                </div>
                <div class="form-group buzz-group">
                    <label for="username">
                       Username
                    </label>
                    <input type="text" class="form-control" id="username" />
                </div>
                <div class="form-group buzz-group buzz-addon">
                    <label for="password">
                       Password
                    </label>
                    <input type="password" class="form-control" id="password" />
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                        width="42px" height="24px">
                        <path fill-rule="evenodd"  fill="rgb(179, 182, 195)"
                        d="M0.917,10.850 C0.971,10.785 19.577,-11.915 40.367,10.897 L41.369,11.996 L40.422,13.138 C40.364,13.208 20.469,37.698 0.893,13.006 L0.026,11.913 L0.917,10.850 L0.917,10.850 ZM20.330,7.048 C17.596,7.048 15.379,9.401 15.379,12.304 C15.379,15.208 17.596,17.561 20.330,17.561 C23.064,17.561 25.281,15.208 25.281,12.304 C25.281,9.401 23.064,7.048 20.330,7.048 L20.330,7.048 ZM28.068,5.666 C29.450,7.480 30.278,9.790 30.278,12.304 C30.278,14.633 29.568,16.786 28.366,18.532 C32.458,16.564 35.570,13.656 37.079,12.076 C33.987,8.926 30.961,6.886 28.068,5.666 L28.068,5.666 ZM12.459,18.764 C11.157,16.978 10.382,14.737 10.382,12.304 C10.382,10.028 11.060,7.920 12.214,6.195 C8.399,8.009 5.570,10.588 4.187,12.008 C6.970,15.269 9.752,17.428 12.459,18.764 L12.459,18.764 ZM25.713,6.588 C24.336,5.126 22.432,4.221 20.330,4.221 C18.228,4.221 16.325,5.126 14.947,6.588 C13.569,8.051 12.717,10.072 12.717,12.304 C12.717,14.537 13.569,16.558 14.947,18.020 C16.325,19.483 18.228,20.388 20.330,20.388 C22.432,20.388 24.336,19.483 25.713,18.020 C27.091,16.558 27.943,14.537 27.943,12.304 C27.943,10.072 27.091,8.051 25.713,6.588 Z"/>
                    </svg>
                </div>
                <div class="form-group buzz-group">
                    <label for="notes">
                       Notes
                    </label>
                    <input type="text" class="form-control" id="notes" />
                </div>
                <div class="buzz-form-buttons">
                    <ul>
                        <li class="Cancel"> 
                            <button type="cencel"> <span> Cancel </span> </button> 
                        </li>
                        <li class="save"> 
                            <button type="submit"> <span> Save </span> </button> 
                        </li>
                    </ul>
                </div>
            </form>
        </div>
    </div> 
</template>