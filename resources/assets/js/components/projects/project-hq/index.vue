<template>
    <div>
        <hq-menu></hq-menu>

        <milestones></milestones>
        <!-- <members> </members> -->
        <!-- <profile></profile> -->
    </div>
</template>

<script>
    import HQMenu from './hq-menu.vue';
    import PageHeader from '../../page-header.vue';    
    import BoxOption from '../../box-option.vue';
    import Milestones from './milestones/index.vue';
    import Members from './members/index.vue';
    import Profile from '../../teams/profile/index.vue';

    export default {
        components: {
          'hq-menu': HQMenu,
          'page-header': PageHeader,
          'box-option': BoxOption,
          'milestones': Milestones,
          'members': Members,
          'profile': Profile,
        }
    }
</script>