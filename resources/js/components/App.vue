<template>
    <div>
        this is an app
        <router-link :to="{ name: 'hello' }">Hello World</router-link>
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