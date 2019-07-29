<template>
    <div>
        <div class="container">
            <router-view :total="total" :last-total="lastTotal" :categories="categories"></router-view>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        name: "App",
        props: [ 'total', 'lastTotal' ],
        data() {
            return {
                message: null,
                error: null,
                categories: null,
            };
        },
        created() {
            this.fetchCategoryData();
        },
        methods: {
            fetchCategoryData() {
                this.error = this.categories = null;
                this.loading = true;
                axios
                    .get( '/api/categories' )
                    .then( response => {
                        this.loading = false;
                        this.categories = response.data.data;
                    } ).catch( error => {
                    this.loading = false;
                    this.error = error.response.data.message || error.message;
                } );
            },
        },
    }
</script>

<style scoped>

</style>