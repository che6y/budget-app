<template>
    <div>
        <div class="purchase-edit">
            <form class="form-row align-items-end" @submit.prevent="onSubmit($event, purchase_i)">
                <div class="form-group col-md-6">
                    <label for="purchase_title">Title</label>
                    <input class="form-control" id="purchase_title" v-model="purchase_i.title" required />
                </div>
                <div class="form-group col-md-3">
                    <label for="purchase_cost">Price</label>
                    <input class="form-control" id="purchase_cost" type="number" v-model="purchase_i.cost" required />
                </div>

                <div class="form-group col-md-3">
                    <label for="purchase_amount">Amount</label>
                    <input class="form-control mx-sm-6" id="purchase_amount" type="number" v-model="purchase_i.amount" min="1" step="1" required />
                </div>
                <div class="form-group col-md-6">
                    <label for="category-id">Category</label>
                    <select v-if="categories" id="category-id" v-model="purchase_i.category_id"
                            class="form-control" @change="changeIcon($event, purchase_i, categories)">
                        <option v-for="category in categories" :selected="category.id === purchase_i.category_id"
                                :value="category.id" >{{category.title }}</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="purchase_date">Date</label>
                    <input class="form-control mx-sm-6" id="purchase_date" type="text"
                           v-model="purchase.created_at" />
                </div>
                <div class="form-group col-md-3">
                    <button class="btn btn-outline-primary" type="submit" :disabled="saving">Update</button>
                    <button class="btn btn-outline-danger" :disabled="saving"
                            @click.prevent="onDelete($event, purchase_i)">Delete</button>
                </div>
            </form>
        </div>
    </div>
</template>
<script>
    import $ from 'jquery';
    import datepickerFactory from 'jquery-datepicker';

    datepickerFactory($);

    export default {
        props: [ 'purchase', 'categories', 'onSubmit', 'onDelete', 'changeIcon' ],
        data() {
            return {
                message: null,
                saving:false,
                loaded: false,
                purchase_i: this.purchase
            };
        },
        methods: {
        },
        created() {
        }
    };

</script>
<style scoped>

</style>