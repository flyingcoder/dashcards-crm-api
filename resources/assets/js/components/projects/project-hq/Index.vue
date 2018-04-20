<template>
    <div>
        <hq-menu></hq-menu>
        <section>
            <transition name="fade" mode="out-in">
                <router-view></router-view>
            </transition>
        </section>
    </div>
</template>

<script>
    
    export default {
        props: ['asset','projectId'],
    }
</script>