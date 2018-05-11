<template>
    <section class="content settings-menu">
        <v-layout row wrap>
            <div class="col-md-12">
                <ul>
                    <li>
                        <router-link to="/" >
                            <div class="menu-item general-settings">
                                <span> General </span> 
                            </div>
                        </router-link>
                    </li>
                    <li>
                        <router-link to="/company" > 
                            <div class="menu-item company-settings">
                                <span> Company </span> 
                            </div>  
                        </router-link>
                    </li>
                    <li>
                        <router-link to="/invoice" >  
                            <div class="menu-item invoices-settings">
                                <span> Invoices </span> 
                            </div>
                        </router-link>
                    </li>
                    <li>
                        <el-dropdown placement="bottom" class="menu-item payment-settings">
                            <span class="payment-text"> Payment Methods </span>
                            <el-dropdown-menu slot="dropdown" class="sort-dropdown">
                                <el-dropdown-item>
                                        <router-link to="/cash" size="small" class="el-dropdown-link"> <span> Cash </span> </router-link>
                                </el-dropdown-item>
                                <el-dropdown-item>
                                    <router-link to="/bank" size="small" class="el-dropdown-link"> <span> Bank Transfer </span> </router-link>
                                </el-dropdown-item>
                                <el-dropdown-item>
                                    <router-link to="/paypal" size="small" class="el-dropdown-link"> <span> Paypal </span> </router-link>
                                </el-dropdown-item>
                            </el-dropdown-menu>  
                        </el-dropdown>
                    </li>
                    <li>
                        <router-link to="/email-templates" > 
                            <div class="menu-item email-settings">
                                <span> Email Templates </span> 
                            </div>
                        </router-link>
                    </li>
                    <li>
                        <router-link to="/support" > 
                            <div class="menu-item support-settings">
                                <span> Support </span> 
                            </div>
                        </router-link>
                    </li>
                    <li>
                        <router-link to="/form-fields" > 
                            <div class="menu-item form-settings">
                                <span> Form Fields </span> 
                            </div>
                        </router-link>
                    </li>
                    <li>
                        <router-link to="/cron" > 
                            <div class="menu-item cron-settings">
                                <span> Cron </span> 
                            </div>
                        </router-link>
                    </li>
                    <li>
                        <router-link to="/db-backup" > 
                            <div class="menu-item db-settings">
                                <span> DB Backup </span> 
                            </div>
                        </router-link>
                    </li>
                    <li>
                        <router-link to="/updates" > 
                            <div class="menu-item updates-settings">
                                <span> Updates </span> 
                            </div>
                        </router-link>
                    </li>
                </ul>
            </div>
        </v-layout>
    </section>
</template>