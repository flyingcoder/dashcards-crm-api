 <template>
    <section class="content cron">
        <v-layout row wrap>
            <div class="content-header col-md-12">
                <div class="page-title">
                    <h1> Cron Job URL </h1>
                </div>
            </div>
            <div class="col-md-12">
                <div class="content-body">
                    <section class="buzz-section">
                        <el-input placeholder="Please input" v-model="input"></el-input>
                        <div class="cron-info">
                            <p class="tip1"> <i class="fas fa-info-circle"></i> This Cron Job Link needs to be added to your cron jobs via your web hosting control panel. 
                                Cron Jobs are used to auomated processes such as sending email notifications 
                            </p>
                            <p class="tip2"> <i class="fas fa-info-circle"></i>  IMPORTANT: Cron jobs are essential for this application to work correctly. 
                                You must set your cron job to run a minimum or EVERY 5 MINUTES.
                            </p>
                            <p class="tip3"> <i class="fas fa-info-circle"></i> For added security, you must change the config["security_key"].
                                You do this by editing the file /application/config/settings.php
                            </p>
                        </div>
                    </section>
                </div>
            </div>
        </v-layout>
    </section>
</template>

<script>
    export default {
        data() {
            return {
            input: 'http://buzzmanager.ca/crmproject1/admin/cronjobs/general/AHusEwFd8HDg630sk'
            }
        }
    }
</script>