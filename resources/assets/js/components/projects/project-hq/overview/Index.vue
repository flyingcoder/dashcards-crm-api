<template>
  <div class="content hq-overview">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <!-- Overview -->
              <div class="buzz-box hq-overview">
                    <div class="box-head">
                        <h1>Overview</h1>
                        <div class="box-options">
                            <box-options></box-options>
                        </div>
                    </div>
                    <project-overview :project-id="$parent.projectId"></project-overview>
                    <div class="box-footer">
                        <a href=""> View More </a>
                    </div>
                </div>
            <!-- Tasks -->
                <div class="buzz-box hq-tasks">
                  <div class="box-head">
                      <h1>Tasks</h1>
                      <div class="box-options tasks-view">
                          <box-options></box-options>
                      </div>
                  </div>
                  <tasks></tasks>
                  <div class="box-footer">
                      <a href=""> View More </a>
                  </div>
                </div>
            <!-- Calendar -->
                <div class="buzz-box hq-calendar">
                    <div class="box-head">
                        <h1>Calendar</h1>
                        <box-options></box-options>
                    </div>
                    <calendar></calendar>
                    <div class="box-footer">
                        <a href=""> View More </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <!-- Timeline -->
                 <div class="buzz-box">
                    <div class="box-head">
                        <h1>Timeline</h1>
                        <box-options></box-options>
                    </div>
                    <timeline :project-id="$parent.projectId"></timeline>
                    <div class="box-footer">
                        <a href=""> View More </a>
                    </div>
                </div>
            <!-- Timer -->
                <div class="buzz-box hq-timer">
                    <div class="box-head" >
                        <h1>Timer</h1>
                        <box-options></box-options>
                    </div>
                    <timer></timer>
                    <div class="box-footer">
                        <a href=""> View More </a>
                    </div>
                </div>
            <!-- Passbox -->
                <div class="buzz-box">
                    <div class="box-head">
                        <h1>Pass Box</h1>
                        <box-options></box-options>
                    </div>
                    <div class="box-content hq-passbox">
                        <passbox></passbox>
                    </div>
                    <div class="box-footer">
                        <a href=""> View More </a>
                    </div>
                </div>
            </div>
        </div>
  </div>
</template>

<script>
    import BoxOptions from './BoxOptions.vue';
    import ProjectOverview from './overview/ProjectOverview.vue';
    import Tasks from './tasks/Index.vue';
    import Calendar from './calendar/Index.vue';
    import Timeline from './timeline/Index.vue';
    import Timer from './timer/Index.vue';
//   import Passbox from './passbox/PassboxEdit.vue';
  // import Passbox from './passbox/PassboxClient.vue'; 
    import Passbox from './passbox/PassboxLogin.vue';

  export default {
    components: {
        'box-options': BoxOptions,
        'project-overview': ProjectOverview,
        'tasks': Tasks,
        'calendar': Calendar,
        'timeline': Timeline,
        'timer': Timer, 
        'passbox': Passbox,
    },
  }
</script>