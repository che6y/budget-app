<template>
    <div>
        <div v-if="message" class="alert alert-success" role="alert">{{ message }}</div>
        <ul v-if="categories" class="list-group">
            <li v-for="(category, index) in categories" :id="['category-' + index]" class="list-group-item list-group-item-action">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <i v-if="category.icon" class="fa" :class="[category.icon ? 'fa-' + category.icon : '']"></i>
                        {{ category.title }}
                    </div>
                    <div class="align-items-center">
                        <button class="btn btn-outline-info btn-sm" type="button" @click.prevent="onBtnClick($event, index)">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                    </div>
                </div>

                <div class="category-edit">
                    <form class="form-row justify-content-start" @submit.prevent="onCategorySubmit($event, category)">
                        <div class="form-group col-md-6">
                            <label for="category-title">Category Name</label>
                            <input class="form-control" id="category-title" v-model="category.title" />
                        </div>
                        <div class="form-group col-md-6">
                            <label>Choose icon</label>
                            <div class="flex-wrap icons-list" role="group">
                                <input class="form-control category-icon" type="hidden" name="category-icon" :value="category.icon" />

                                <button v-for="icon in icons" @click.prevent="changeCategoryIcon( $event, icon, category )"
                                        type="button"
                                        class="btn btn-outline-info" :class="[category.icon === icon ? 'active' : '']" >
                                    <i class="fas" v-bind:class="[ 'fa-' + icon]"></i>
                                </button>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <button type="submit" class="btn btn-outline-primary">Update</button>
                            <button type="button" class="btn btn-outline-danger" @click.prevent="onDelete($event, category.id)">
                                Delete
                            </button>
                        </div>
                    </form>
                </div>

            </li>
        </ul>
    </div>

</template>

<script>
    import axios from 'axios';
    import categories_api from '../api/categories';
    import icons from '../icons';

    export default {
        data(){
            return {
                message: null,
                error: null,
                categories: null,
                icons: icons,
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
            onCategorySubmit( event, category ) {
                console.log(typeof category.icon);
                categories_api.update( category.id, {
                    id: category.id,
                    title: category.title,
                    icon : category.icon
                }).then( (response) => {
                    this.message = 'Category Updated';
                    setTimeout(() => this.message = null, 3000);
                }).catch( error => {
                    this.error = 'Something went wrong, please try again later';
                    setTimeout(() => this.error = null, 3000);
                });
            },
            onDelete( event, id ) {
                categories_api.delete( id )
                    .then(( response ) => {
                        if ( response.data.deleted ) {
                            $(event.target).closest('.list-group-item').hide();
                        }
                        this.message = response.data.text;
                        setTimeout(() => this.message = null, 3000);
                    });
            },
            onBtnClick( event, index ) {
                const parent = $(event.target).closest('#category-' + index);

                if ( parent.find('.category-edit').css('display') === 'none' ){
                    parent.find('.category-edit').show();
                    parent.siblings().find('.category-edit').hide();
                } else {
                    parent.find('.category-edit').hide();
                }
            },
            changeCategoryIcon( event, icon, category ) {
                category.icon = icon;
            },
        },

    }
</script>

<style scoped>
    .category-edit {
        display: none;
    }
    .fa {
        padding-right: 10px;
    }
</style>