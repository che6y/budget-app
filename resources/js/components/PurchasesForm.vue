<template>
    <div>
        <div v-if="message" class="alert alert-success" role="alert">{{ message }}</div>
        <div v-if="error" class="alert alert-danger" role="alert">{{ error }}</div>

        <form class="form-row align-items-end" @submit.prevent="onSubmit($event)">
            <input type="hidden" name="user_id"/>

            <div class="form-group col-md-6">
                <label for="purchase_title">Title</label>
                <input class="form-control" id="purchase_title" v-model="purchase.title" required />
            </div>
            <div class="form-group col-md-2">
                <label for="purchase_cost">Price</label>
                <input class="form-control" id="purchase_cost" type="number" v-model="purchase.cost" required />
            </div>
            <div class="form-group col-md-2">
                <label for="purchase_amount">Amount</label>
                <input class="form-control mx-sm-6" id="purchase_amount" type="number" min="1" step="1" value="1"
                       v-model="purchase.amount" />
            </div>
            <div class="form-group col-md-2">
                <button class="btn btn-outline-primary" type="submit">Add</button>
            </div>
        </form>

        <categories-list :add-category="addCategory" :on-category-icon-click="onCategoryIconClick"
                         :categories="categories"></categories-list>
    </div>
</template>

<script>
    import purchases_api from '../api/purchases';

    export default {
        name: "PurchasesForm",
        props: ['categories', 'changeSummary', 'newPurchaseToArr', 'addCategory'],
        data() {
            return {
                error   : null,
                message : null,
                saving  : false,
                purchase: {
                    title      : "",
                    cost       : "",
                    amount     : 1,
                    icon       : '',
                    category_id: null,
                    user_id    : null
                },
            }
        },
        methods: {
            onSubmit( event ) {
                this.saving = true;

                if ( this.purchase.category_id === null ) {
                    this.error  = 'Please choose category';
                    this.saving = false;
                    setTimeout(() => this.error = null, 3000);

                    return true;
                }

                purchases_api.post( {
                    title      : this.purchase.title,
                    cost       : this.purchase.cost,
                    amount     : this.purchase.amount,
                    category_id: this.purchase.category_id,
                    user_id    : document.querySelector("meta[name='user-id']").getAttribute('content')
                }).then( (response) => {
                    this.changeSummary( this.purchase.amount * this.purchase.cost, '+' );
                    this.newPurchaseToArr(response.data.data);
                    this.message              = 'Purchase Added';
                    this.purchase.title       = '';
                    this.purchase.cost        = '';
                    this.purchase.amount      = 1;
                    this.purchase.category_id = null;
                    this.purchase.icon        = '';

                    $('.btn-outline-info').removeClass('active');
                    setTimeout(() => this.message = null, 3000);

                }).catch( error => {
                    console.log(error);
                    this.error = 'Something went wrong, please try again later';
                    setTimeout(() => this.error = null, 3000);
                }).then(_ => this.saving = false);
            },
            onCategoryIconClick( event, categoryID, categoryIcon ) {
                let closest_btn = $( event.target ).closest('button');

                if ( closest_btn.hasClass('active') ){
                    this.purchase.category_id = null;
                    this.purchase.icon        = '';
                    closest_btn.removeClass('active');
                } else {
                    this.purchase.category_id = categoryID;
                    this.purchase.icon        = categoryIcon;
                    closest_btn.addClass('active').siblings().removeClass('active');
                }
            },

        }
    }
</script>
<style scoped>
    .btn.btn-outline-primary {
        width: 100%;
    }
</style>