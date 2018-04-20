 <template>
    <el-tabs :tab-position="tabPosition" style="height: 1000px;">
        <el-tab-pane label="Questionnaire Email">
            <QuestionnaireEmail></QuestionnaireEmail>
        </el-tab-pane>
        <el-tab-pane label="New Quotation">
            <NewQuotation></NewQuotation>
        </el-tab-pane>
        <el-tab-pane label="Reset Password">
            <ResetPassword></ResetPassword>
        </el-tab-pane>
        <el-tab-pane label="New User">
            <NewUser></NewUser>
        </el-tab-pane>
        <el-tab-pane label="New Notification">
            <NewNotification></NewNotification>
        </el-tab-pane>
        <el-tab-pane label="New Project">
            <NewProject></NewProject>
        </el-tab-pane>
        <el-tab-pane label="Overdue Invoice">
            <OverdueInvoice></OverdueInvoice>
        </el-tab-pane>
        <el-tab-pane label="New Invoice">
            <NewInvoice></NewInvoice>
        </el-tab-pane>
        <el-tab-pane label="New Client">
            <NewClient></NewClient>
        </el-tab-pane>
        <el-tab-pane label="Task Update">
            <TaskUpdate></TaskUpdate>
        </el-tab-pane>
    </el-tabs>
</template>

<script>
    import QuestionnaireEmail from './ClientTemplates/QuestionnaireEmail.vue';
    import NewQuotation from './ClientTemplates/NewQuotation.vue';
    import ResetPassword from './ClientTemplates/ResetPassword.vue';
    import NewUser from './ClientTemplates/NewUser.vue';
    import NewNotification from './ClientTemplates/NewNotification.vue';
    import NewProject from './ClientTemplates/NewProject.vue';
    import OverdueInvoice from './ClientTemplates/OverdueInvoice.vue';
    import NewInvoice from './ClientTemplates/NewInvoice.vue';
    import NewClient from './ClientTemplates/NewClient.vue';
    import TaskUpdate from './ClientTemplates/TaskUpdate.vue';

    export default {
        components: {
          'QuestionnaireEmail': QuestionnaireEmail,
          'NewQuotation': NewQuotation,
          'ResetPassword': ResetPassword,
          'NewUser': NewUser,
          'NewNotification': NewNotification,
          'NewProject': NewProject,
          'OverdueInvoice': OverdueInvoice,
          'NewInvoice': NewInvoice,
          'NewClient': NewClient,
          'TaskUpdate': TaskUpdate,
      },
      props: ['asset'],
      
      data () {
        return {
            tabPosition: 'left',
            title: 'Email Templates (Client)',
        }
      },
      methods: {

      }
        
    }
</script>