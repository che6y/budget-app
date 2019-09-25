<template>
    <div>
        <div class="container">
            <router-view :total="total" :monthly-spendings="monthlySpendings" :categories="categories"
                         :add-category="addCategory"></router-view>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import category_api from '../api/categories';

    export default {
        name: "App",
        props: [ 'total', 'monthlySpendings'],
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
            addCategory( title, icon ) {
                var result = false;
                category_api.post( {
                    title: title,
                    icon: icon,
                }).then( (response) => {
                    this.categories.unshift(response.data.data);
                    result = true;
                }).catch( error => {
                    result = error;
                });
            },
        },
    }
</script>

<style scoped>

</style>