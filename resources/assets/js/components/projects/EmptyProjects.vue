<template>
    <div class="empty-table">
        <img class="empty-icon" src="img/icons/empty/projects.svg">
        <div class="empty-button">
            <button class="add"  @click="$modal.show('add-project')">
                Add New Project
            </button>
        </div>

        <add-project></add-project>
    </div>
</template>

<script>
    import AddProject from './AddProject.vue';
    export default {
        components: {
          'add-project': AddProject,
      },
        mounted(){

        }
    }
</script>