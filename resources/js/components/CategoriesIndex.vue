<template>
    <div>
        <ul v-if="categories" class="list-group">
            <li v-for="category in categories" class="list-group-item list-group-item-action">
                <div class="row justify-content-end">
                    <div class="col-4">
                        {{ category.title }}
                    </div>
                    <div class="col-4">
                        <i v-if="category.icon" class="fas" v-bind:class="[category.icon ? 'fa-' + category.icon : '']"></i>
                    </div>
                    <div class="col-4 ">
                        <div class="dropdown">
                            <button class="btn btn-outline-info dropdown-toggle btn-sm" type="button"
                                    id="caegory-actions"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="caegory-actions">
                                <button class="dropdown-item" >Edit</button>
                                <button class="dropdown-item" @click.prevent="onDelete( $event,
                                category.id )">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
    
</template>

<script>
    import axios from 'axios';
    import categories_api from '../api/categories';

    export default {
        data(){
            return {
                message: null,
                error: null,
                categories: null,
                category: {
                    id: null,
                    title: "",
                    icon: null
                },
            }
        },
        created() {
            this.fetchCategoryData();
        },
        methods: {
            fetchCategoryData() {
                this.error = this.categories = null;
                axios
                    .get( '/api/categories' )
                    .then( response => {
                        this.loading = false;
                        this.categories = response.data.data;
                    }).catch( error => {
                    this.error = error.response.data.message || error.message;
                });
            },
            onCategorySubmit( event ) {
                categories_api.post( {
                    title: this.category.title,
                    icon: this.category.icon,
                }).then( (response) => {
                    $('#add-category-form').modal('hide')
                    this.message = 'Category Added';
                    setTimeout(() => this.message = null, 1500);
                    this.categories.unshift(response.data.data);
                    this.category.title = '';
                    this.category.icon = null;
                }).catch( error => {
                    this.error = 'Something went wrong, please try again later';
                    setTimeout(() => this.categoryFormError = null, 1500);
                });
            },
            onDelete( event, id ) {
                this.saving = true;
                $(event.target).closest('.list-group-item').hide();
                categories_api.delete( id )
                    .then(( response ) => {
                        this.message = 'Item Deleted';
                    });
            },
        },

    }
</script>

<style scoped>

</style>