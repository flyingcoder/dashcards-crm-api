<template>
    <div class="empty-table">
        <img class="empty-icon" src="img/icons/empty/clients.svg">
        <div class="empty-button">
            <button class="add"  @click="$modal.show('add-client')">
                Add New Clients
            </button>
        </div>
    </div>
</template>