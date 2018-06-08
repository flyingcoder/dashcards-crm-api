<template>
    <li>
        <el-dropdown trigger="click" placement="bottom" class="head-icons email-badge">
            <el-badge :value="20" :max="99" class="item">
                <el-button size="small" class="el-dropdown-link">
                    <img src="/img/icons/header/email.svg" width="35px" class="icon-normal">
                    <img src="/img/icons/header/emailcolor.png" width="35px" class="icon-hover">
                </el-button>
            </el-badge>
            <el-dropdown-menu slot="dropdown" class="header-dropdown header-messages buzz-scrollbar buzz-scroll">
               
            </el-dropdown-menu>
        </el-dropdown>
    </li>
</template>