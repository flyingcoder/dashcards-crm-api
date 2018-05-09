<template>
    <div class="empty-table">
        <img class="empty-icon" :src="icon">
        <div class="empty-button">
            <button class="add"  @click="$modal.show(modal,{ action: 'Save'})">
                {{ title }}
            </button>
        </div>
    </div>
</template>
<script>
export default {
  props:['title','modal','icon']
}
</script>
