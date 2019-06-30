<template>
    <div>
        <div class="container">
            <router-view :total="total"></router-view>
        </div>
    </div>
</template>

<script>
    export default {
        name: "App",
        props: [ 'total' ]
    }
</script>

<style scoped>

</style>