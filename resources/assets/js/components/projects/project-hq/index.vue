<template>
    <div>
        <!-- <hq-menu></hq-menu> -->

        <!-- <hq></hq> -->
        <!-- <files></files> -->
        <!-- <milestones></milestones> -->
        <!-- <tasks></tasks> -->
        <!-- <members> </members> -->
        <profile></profile>
    </div>
</template>

<script>
    import HQMenu from './hq-menu.vue';
    import PageHeader from '../../page-header.vue';    
    import BoxOption from '../../box-option.vue';
    import HQ from './overview/index.vue';
    import Files from './files/index.vue';
    import Milestones from './milestones/index.vue';
    import Tasks from './tasks/index.vue';
    import Members from './members/index.vue';
    import Profile from '../../teams/profile/index.vue';

    export default {
        components: {
          'hq-menu': HQMenu,
          'page-header': PageHeader,
          'box-option': BoxOption,
          'hq': HQ,
          'files': Files,
          'milestones': Milestones,
          'tasks': Tasks,
          'members': Members,
          'profile': Profile,
        }
    }
</script>