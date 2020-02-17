<template>
    <div>

        <categories-list :add-category="addCategory" :on-category-icon-click="onCategoryIconClick"
                         :categories="categories"></categories-list>

        <form class="form-row align-items-end" @submit.prevent="onSubmit()">
            <input type="hidden" name="user_id"/>

            <div class="form-group col-md-6">
                <label for="purchase_title">Title</label>
                <input class="form-control" id="purchase_title" v-model="purchase.title" required />
            </div>
            <div class="form-group col-md-2">
                <label for="purchase_cost">Price</label>
                <input class="form-control" id="purchase_cost" type="text" v-model="purchase.cost" required />
            </div>
<!--            <div class="form-group col-md-2">-->
<!--                <label for="purchase_amount">Amount</label>-->
                <input class="form-control mx-sm-6" id="purchase_amount" type="hidden" min="1" step="1" value="1"
                       v-model="purchase.amount" />
<!--            </div>-->
            <div class="form-group col-md-2">
                <button class="btn btn-outline-primary" type="submit">Add</button>
            </div>
        </form>

    </div>
</template>

<script>
    import purchases_api from '../api/purchases';

    export default {
        name: "PurchasesForm",
        props: ['categories', 'changeSummary', 'newPurchaseToArr', 'addCategory', 'showMessage'],
        data() {
            return {
                saving  : false,
                purchase: {
                    title      : "",
                    cost       : "",
                    amount     : 1,
                    user_id    : null,
                    category   : null
                },
            }
        },
        methods: {
            onSubmit() {
                this.saving = true;

                if ( /\s/.test( this.purchase.cost ) ){
                    let costsArr       = this.purchase.cost.split(' ');
                    this.purchase.cost = costsArr.reduce((a, b) => parseInt(a) + (parseInt(b) || 0), 0);
                }

                if ( this.purchase.category === null ) {
                    this.saving = false;
                    this.showMessage('Please choose category', 'danger');
                    return true;
                }

                purchases_api.post( {
                    title      : this.purchase.title,
                    cost       : this.purchase.cost,
                    amount     : this.purchase.amount,
                    category_id: this.purchase.category.id,
                    user_id    : document.querySelector("meta[name='user-id']").getAttribute('content')
                }).then( (response) => {
                    this.changeSummary( this.purchase.amount * this.purchase.cost, '+' );
                    this.newPurchaseToArr( response.data.data );
                    this.purchase.title    = '';
                    this.purchase.cost     = '';
                    this.purchase.amount   = 1;
                    this.purchase.category = null;

                    $('.btn-outline-info').removeClass('active');
                    this.showMessage('Purchase successfully added', 'success');
                }).catch( error => {
                    console.log( error );
                    this.showMessage('Something went wrong, please try again later', 'danger');
                }).then(_ => this.saving = false);
            },
            onCategoryIconClick( event, category ) {
                let closest_btn = $( event.target ).closest('button');

                if ( closest_btn.hasClass('active') ){
                    this.purchase.title    = '';
                    this.purchase.category = null;
                    closest_btn.removeClass('active');
                } else {
                    $( '#purchase_title' ).focus();
                    this.purchase.title    = category.title;
                    this.purchase.category = category;
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
    form {
        margin: 0 0 20px 0;
        font-size: 16px;
    }
</style>