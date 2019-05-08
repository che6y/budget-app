<template>
    <div>
        <ul v-if="categories" class="list-group">
            <li v-for="category in categories" class="list-group-item list-group-item-action">
                <div class="row justify-content-end">
                    <div class="col-10">
                        <i v-if="category.icon" class="fas" v-bind:class="[category.icon ? 'fa-' + category.icon : '']"></i>  {{
                        category.title }}
                    </div>
                    <div class="col-2 justify-content-between align-items-center">
                        <div class="dropdown">
                            <button class="btn btn-outline-info dropdown-toggle btn-sm" type="button"
                                    id="caegory-actions"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="caegory-actions">
                                <button class="dropdown-item" data-toggle="modal" type="button"
                                        @click.prevent="onEditClicked($event)">Edit</button>
                                <button class="dropdown-item" @click.prevent="onDelete( $event, category.id )">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>

        <!-- Modal -->
        <div class="modal fade" id="add-category-form" tabindex="-1" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-title">Edit category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
<!--                        <div v-if="" class="alert alert-danger" role="alert">-->
                        </div>
                        <form class="form-row justify-content-start" @submit.prevent="onCategorySubmit($event)">
                            <div class="form-group col-md-6">
                                <label for="category-title">Category Name</label>
                                <input class="form-control" id="category-title" v-model="category.title" />
                            </div>
                            <div class="form-group col-md-6">
                                <label for="category-icon">Choose icon</label>
                                <input class="form-control" id="category-icon" type="hidden" v-model="category.icon" />

                                <div class="flex-wrap icons-list" role="group">
<!--                                    <button v-for="icon in icons" v-on:click="onIconClick( $event, icon )" type="button"-->
<!--                                            class="btn btn-outline-info" >-->
<!--                                        <i class="fas" v-bind:class="[ 'fa-' + icon]"></i>-->
<!--                                    </button>-->
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <button type="submit" class="btn btn-outline-primary" >Add</button>
                                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
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
                        this.message = response.content;
                    });
            },
            onEditClicked( ){
                $('#add-category-form').modal('show');
            }
        },

    }
</script>

<style scoped>
    .dropdown-toggle::after {
        display: none;
    }

    .btn-outline-info:focus,
    .btn-outline-info.focus {
        box-shadow: none;
    }
    .dropdown-toggle.btn-sm {
        border: none;
    }
</style>