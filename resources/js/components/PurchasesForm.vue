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

        <categories-index :on-category-click="onCategoryClick" :categories="categories"></categories-index>
    </div>
</template>

<script>
    import purchases_api from '../api/purchases';

    export default {
        name: "PurchasesForm",
        props: ['purchases','categories'],
        data() {
            return {
                error: null,
                message: null,
                saving : false,
                purchase: {
                    title: "",
                    cost: "",
                    amount: 1,
                    icon: null,
                    category_id: null,
                    user_id: null
                },
            }
        },
        methods: {
            onSubmit( event ) {
                event.preventDefault();

                if ( this.purchase.category_id === null ) {
                    this.error = 'Please choose category';
                    setTimeout(() => this.error = null, 1500);
                    return true;
                }

                this.saving = true;
                purchases_api.post( {
                    title: this.purchase.title,
                    cost: this.purchase.cost,
                    amount: this.purchase.amount,
                    category_id: this.purchase.category_id,
                    user_id: document.querySelector("meta[name='user-id']").getAttribute('content')
                }).then( (response) => {
                    var purchase_arr = response.data.data;
                    this.purchases.unshift(purchase_arr);
                    this.purchase.title = '';
                    this.purchase.cost = '';
                    this.purchase.amount = 1;

                    this.message = 'Purchase Added';
                    setTimeout(() => this.message = null, 3000);

                }).catch( error => {
                    this.error = 'Something went wrong, please try again later';
                    setTimeout(() => this.error = null, 3000);
                }).then(_ => this.saving = false);
            },
            onCategoryClick( event, categoryID, categoryIcon ) {
                this.purchase.category_id = categoryID;
                this.purchase.icon   = categoryIcon;
                $( event.target )
                    .closest('button')
                    .addClass('active')
                    .siblings()
                    .removeClass('active');
            },

        }
    }
</script>

<style scoped>
    .btn.btn-outline-primary {
        width: 100%;
    }
</style>