<template>
    <li>
        <el-dropdown trigger="click" placement="bottom" class="chat-badge">
            <el-badge :value="5" :max="99" class="item">
                <el-button size="small" class="el-dropdown-link">
                    <img src="/img/icons/header/chat.svg" width="28px" class="icon-normal">
                    <img src="/img/icons/header/chatcolor.png" width="28px" class="icon-hover">
                </el-button>
            </el-badge>
            <el-dropdown-menu slot="dropdown" class="header-dropdown header-notifications buzz-scrollbar buzz-scroll">
               
            </el-dropdown-menu>
        </el-dropdown>
    </li>
</template>