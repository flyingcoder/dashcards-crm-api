<template>
    <div class="empty-table">
        <img class="empty-icon" src="img/icons/empty/projects.svg">
        <div class="empty-button">
            <button class="add"  @click="$modal.show('add-alarm')">
                Add New Alarm
            </button>
        </div>
    </div>
</template>