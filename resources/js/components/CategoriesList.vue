<template>
    <div>
        <div class="flex-wrap categories-list" role="group">
            <button v-for="category in categories" @click="onCategoryIconClick($event, category.id,
            category.icon )" type="button" class="btn btn-outline-info btn-sm">{{ category.title }}
                <i v-if="category.icon" class="fas" v-bind:class="[category.icon ? 'fa-' + category.icon : '']"></i>
            </button><button class="btn btn-outline-info btn-sm" data-toggle="modal" data-target="#add-category-form" type="button">
                <i class="fas fa-plus"></i>
            </button>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="add-category-form" tabindex="-1" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-title">Add new category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div v-if="error" class="alert alert-danger" role="alert">
                            {{ error }}
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
                                    <button v-for="icon in icons" v-on:click="changeCategoryIcon( $event, icon )" type="button"
                                            class="btn btn-outline-info" >
                                        <i class="fas" v-bind:class="[ 'fa-' + icon]"></i>
                                    </button>
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
    import categories_api from '../api/categories';
    import icons from '../icons';

    export default {
        props: ['onCategoryIconClick', 'categories'],
        data(){
            return {
                message: null,
                error: null,
                icons: icons,
                category: {
                    id: null,
                    title: "",
                    icon: null
                },
            }
        },
        methods: {

            onCategorySubmit( event ) {
                categories_api.post( {
                    title: this.category.title,
                    icon: this.category.icon,
                }).then( (response) => {
                    $('#add-category-form').modal('hide');
                    this.message = 'Category Added';
                    setTimeout(() => this.message = null, 3000);
                    this.categories.unshift(response.data.data);
                    this.category.title = '';
                    this.category.icon  = null;
                }).catch( error => {
                    this.error = 'Something went wrong, please try again later';
                    setTimeout(() => this.error = null, 3000);
                });
            },
            changeCategoryIcon ( event, icon ) {
                this.category.icon = icon;
                let closest_btn    = $( event.target ).closest('button');
                if ( closest_btn.hasClass('active') ){
                    closest_btn.removeClass('active');
                } else {
                    closest_btn.addClass('active').siblings().removeClass('active');
                    $( event.target ).closest('.category-icon').val(icon);
                }
            },
        },

    }
</script>

<style scoped>
    .categories-list {
        margin: 0 0 20px 0;
    }
    .btn-outline-info:focus,
    .btn-outline-info.focus {
        box-shadow: none;
    }
    .btn.btn-outline-info.btn-sm {
        border: 1px solid #6cb2eb;
    }
</style>