<template>
    <li>
        <el-dropdown trigger="click" placement="bottom" class="member-option">
            <el-button size="small" class="el-dropdown-link"> 
                <img src="/img/temporary/user.png" id="user-image">
                <img src="/img/icons/header/arrowdown.svg">
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
    </li>
</template>