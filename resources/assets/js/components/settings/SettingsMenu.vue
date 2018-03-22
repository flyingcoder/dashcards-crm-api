<template>
    <section class="content settings-menu">
        <div class="row">
            <div class="col-md-12">
                <ul>
                    <li>
                        <div class="menu general-settings">
                             <router-link to="/" > <span> General </span> </router-link>
                        </div>
                    </li>
                    <li>
                        <div class="menu company-settings">
                            <router-link to="/" > <span> Company </span> </router-link>
                        </div>
                    </li>
                    <li>
                        <div class="menu invoices-settings">
                            <router-link to="/" >  <span> Invoices </span> </router-link>
                        </div>
                    </li>
                    <li class="sort">
                        <el-dropdown placement="bottom" class="menu payment-settings">
                            <router-link to="/" size="small" class="el-dropdown-link"> <span> Payment Methods </span> </router-link>
                            <el-dropdown-menu slot="dropdown" class="sort-dropdown">
                                <el-dropdown-item>
                                    <a href="#"> Cash </a>
                                </el-dropdown-item>
                                <el-dropdown-item>
                                    <a href="#"> Bank Transfer </a>
                                </el-dropdown-item>
                                <el-dropdown-item>
                                    <a href="#"> Paypal </a>
                                </el-dropdown-item>
                            </el-dropdown-menu>  
                        </el-dropdown>
                    </li>
                    <li>
                        <div class="menu email-settings">
                            <router-link to="/" > <span> Email Templates </span> </router-link>
                        </div>
                    </li>
                    <li>
                        <div class="menu support-settings">
                            <router-link to="/" > <span> Support </span> </router-link>
                        </div>
                    </li>
                    <li>
                        <div class="menu form-settings">
                            <router-link to="/" > <span> Form Fields </span> </router-link>
                        </div>
                    </li>
                    <li>
                        <div class="menu cron-settings">
                            <router-link to="/" > <span> Cron </span> </router-link>
                        </div>
                    </li>
                    <li>
                        <div class="menu db-settings">
                            <router-link to="/" > <span> DB Backup </span> </router-link>
                        </div>
                    </li>
                    <li>
                        <div class="menu updates-settings">
                            <router-link to="/" > <span> Updates </span> </router-link>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </section>
</template>