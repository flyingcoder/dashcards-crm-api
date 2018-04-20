<template>
    <section class="content settings-menu">
        <ul>
            <li>
                <div class="menu general-settings">
                        <router-link to="/" > <span> General </span> </router-link>
                </div>
            </li>
            <li>
                <div class="menu company-settings">
                    <router-link to="/Company" > <span> Company </span> </router-link>
                </div>
            </li>
            <li>
                <div class="menu invoices-settings">
                    <router-link to="/Invoice" >  <span> Invoices </span> </router-link>
                </div>
            </li>
            <li class="sort">
                <el-dropdown placement="bottom" class="menu payment-settings">
                    <span> Payment Methods </span>
                    <el-dropdown-menu slot="dropdown" class="sort-dropdown">
                        <el-dropdown-item>
                                <router-link to="/Cash" size="small" class="el-dropdown-link"> <span> Cash </span> </router-link>
                        </el-dropdown-item>
                        <el-dropdown-item>
                            <router-link to="/Bank" size="small" class="el-dropdown-link"> <span> Bank Transfer </span> </router-link>
                        </el-dropdown-item>
                        <el-dropdown-item>
                            <router-link to="/Paypal" size="small" class="el-dropdown-link"> <span> Paypal </span> </router-link>
                        </el-dropdown-item>
                    </el-dropdown-menu>  
                </el-dropdown>
            </li>
            <li>
                <div class="menu email-settings">
                    <router-link to="/Email Templates" > <span> Email Templates </span> </router-link>
                </div>
            </li>
            <li>
                <div class="menu support-settings">
                    <router-link to="/Support" > <span> Support </span> </router-link>
                </div>
            </li>
            <li>
                <div class="menu form-settings">
                    <router-link to="/Form Fields" > <span> Form Fields </span> </router-link>
                </div>
            </li>
            <li>
                <div class="menu cron-settings">
                    <router-link to="/Cron" > <span> Cron </span> </router-link>
                </div>
            </li>
            <li>
                <div class="menu db-settings">
                    <router-link to="/DB Backup" > <span> DB Backup </span> </router-link>
                </div>
            </li>
            <li>
                <div class="menu updates-settings">
                    <router-link to="/Updates" > <span> Updates </span> </router-link>
                </div>
            </li>
        </ul>
    </section>
</template>