<template>
    <div class="empty-table">
        <img class="empty-icon" src="img/icons/empty/clients.svg">
        <div class="empty-button">
            <button class="add"  @click="$modal.show('add-report')">
                Add New Report
            </button>
        </div>
    </div>
</template>