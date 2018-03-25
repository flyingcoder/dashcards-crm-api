 <template>
    <section class="content email-templates">
        <div class="content-header">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-title">
                        <h1> {{title}} </h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <section class="buzz-section">
                <el-tabs type="border-card">
                    <el-tab-pane label="Client Template">
                        <ClientTemplate></ClientTemplate>
                    </el-tab-pane>
                    <el-tab-pane label="Admin Template">
                        <AdminTemplate></AdminTemplate>
                    </el-tab-pane>
                </el-tabs>
            </section>
        </div>
    </section>
</template>

<script>
    import AdminTemplate from './AdminTemplate.vue';
    import ClientTemplate from './ClientTemplate.vue';

    export default {
        components: {
          'AdminTemplate': AdminTemplate,
          'ClientTemplate': ClientTemplate,
      },
      props: ['asset'],
      
      data () {
        return {
            title: 'Email Templates (Client)',
        }
      },
      methods: {

      }
        
    }
</script>