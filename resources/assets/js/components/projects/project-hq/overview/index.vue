<template>
  <section class="content hq-overview">
    <div class="col-md-6">
      <project-overview :project-id="$parent.projectId"></project-overview>
      <task :project-id="$parent.projectId"></task>
      <calendar></calendar>
    </div>
    <div class="col-md-6">
      <timeline></timeline>
      <timer></timer>
      <pass-box></pass-box>
    </div>
  </section>
</template>

<script>
import ProjectOverview from './ProjectOverview'
import Timeline from './Timeline'
import Task from './Task'
import Timer from './Timer'
import Calendar from './Calendar'
import PassBox from './PassBox'
export default {
  components: {
    'project-overview': ProjectOverview,
    'timeline': Timeline,
    'task': Task,
    'timer': Timer,
    'pass-box': PassBox,
  },
}
</script>

<style>

</style>
