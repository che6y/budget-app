<template>
    <div>
        <router-link :to="{ name: 'purchases.index' }">Purcheses</router-link>
        <div class="container">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
    export default {
        name: "App"
    }
</script>

<style scoped>

</style>