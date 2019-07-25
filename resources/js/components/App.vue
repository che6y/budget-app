<template>
    <div>
        <div class="container">
            <router-view :total="total" :last-total="lastTotal"></router-view>
        </div>
    </div>
</template>

<script>
    export default {
        name: "App",
        props: [ 'total', 'lastTotal' ]
    }
</script>

<style scoped>

</style>