 <template>
    <el-tabs :tab-position="tabPosition" style="height: 1000px;">
        <el-tab-pane label="New Team Member">
            <NewTeamMember></NewTeamMember>
        </el-tab-pane>
        <el-tab-pane label="New Payment">
            <NewPayment></NewPayment>
        </el-tab-pane>
        <el-tab-pane label="Reset Password">
            <ResetPassword></ResetPassword>
        </el-tab-pane>
        <el-tab-pane label="New User">
            <NewUser></NewUser>
        </el-tab-pane>
        <el-tab-pane label="New Project">
            <NewProject></NewProject>
        </el-tab-pane>
        <el-tab-pane label="New Notification">
            <NewNotification></NewNotification>
        </el-tab-pane>
        <el-tab-pane label="New Client">
            <NewClient></NewClient>
        </el-tab-pane>
        <el-tab-pane label="New Task">
            <NewTask></NewTask>
        </el-tab-pane>
        <el-tab-pane label="Task Update">
            <TaskUpdate></TaskUpdate>
        </el-tab-pane>
        <el-tab-pane label="Questionnaire">
            <Questionnaire></Questionnaire>
        </el-tab-pane>
    </el-tabs>
</template>

<script>
    import NewTeamMember from './AdminTemplates/NewTeamMember.vue';
    import NewPayment from './AdminTemplates/NewPayment.vue';
    import ResetPassword from './AdminTemplates/ResetPassword.vue';
    import NewUser from './AdminTemplates/NewUser.vue';
    import NewProject from './AdminTemplates/NewProject.vue';
    import NewNotification from './AdminTemplates/NewNotification.vue';
    import NewClient from './AdminTemplates/NewClient.vue';
    import NewTask from './AdminTemplates/NewTask.vue';
    import TaskUpdate from './AdminTemplates/TaskUpdate.vue';
    import Questionnaire from './AdminTemplates/Questionnaire.vue';

    export default {
        components: {
          'NewTeamMember': NewTeamMember,
          'NewPayment': NewPayment,
          'ResetPassword': ResetPassword,
          'NewUser': NewUser,
          'NewProject': NewProject,
          'NewNotification': NewNotification,
          'NewClient': NewClient,
          'NewTask': NewTask,
          'TaskUpdate': TaskUpdate,
          'Questionnaire': Questionnaire,
      },
      props: ['asset'],
      
      data () {
        return {
            tabPosition: 'left',
            title: 'Email Templates (Admin)',
        }
      },
      methods: {

      }
        
    }
</script>