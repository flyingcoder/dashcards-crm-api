<template>
    <li>
        <a href="#">
            <img src="/img/temporary/user.png" id="user-image">
            <el-dropdown trigger="click" placement="bottom" class="member-option">
                <el-button size="small" class="el-dropdown-link"> 
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="40px" height="17px">
                        <path fill-rule="evenodd"  fill="rgb(103, 116, 130)"
                            d="M39.599,0.335 C39.061,-0.103 38.187,-0.103 37.648,0.335 L20.497,14.318 L3.311,0.335 C2.773,-0.103 1.899,-0.103 1.361,0.335 C0.823,0.773 0.823,1.484 1.361,1.922 L19.488,16.671 C19.757,16.890 20.093,17.000 20.463,17.000 C20.799,17.000 21.169,16.890 21.439,16.671 L39.565,1.922 C40.137,1.484 40.137,0.773 39.599,0.335 Z"/>
                    </svg>
                </el-button>
                <el-dropdown-menu slot="dropdown" class="header-dropdown header-notifications buzz-scrollbar buzz-scroll">
                    <el-dropdown-item>
                        <div style="text-align:center; padding:5px">
                            <a href="#">
                                <h3>Account</h3> 
                            </a>
                        </div>
                    </el-dropdown-item>
                </el-dropdown-menu>
            </el-dropdown>
        </a>
    </li>
</template>