<template>
    <div class="purchases">
        <div v-if="message" class="alert alert-success" role="alert">{{ message }}</div>

        <div v-if="error" class="alert alert-danger" role="alert">
            {{ error }}
        </div>

        <form class="form-row justify-content-start" @submit.prevent="onSubmit($event)">
            <div class="form-group col-md-6">
                <label for="purchase_title">Title</label>
                <input class="form-control" id="purchase_title" v-model="purchase.title" />
            </div>
            <div class="form-group col-md-3">
                <label for="purchase_cost">Price</label>
                <input class="form-control" id="purchase_cost" type="number" v-model="purchase.cost" />
            </div>
            <div class="form-group col-md-3">
                <label for="purchase_amount">Amount</label>
                <input class="form-control mx-sm-6" id="purchase_amount" v-model="purchase.amount" type="number" min="1" step="1" />
            </div>
            <div class="form-group col-md-6">
                <button class="btn btn-outline-primary" type="submit" :disabled="saving">Create</button>
            </div>
        </form>


        <ul v-if="purchases"class="list-group">
            <li v-for="purchase in purchases" class="list-group-item">
                <div class="row justify-content-end">
                    <div class="col-sm-3 col-4">
                        {{ purchase.cost * purchase.amount}} baht
                    </div>
                    <div class="col-sm-4 col-4">
                        {{ purchase.title }}
                    </div>
                    <div class="col-sm-3 col-4">
                        {{ purchase.created_at }}
                    </div>
                    <div class="col-sm-2 col-4 ">
                        <router-link :to="{ name: 'purchases.edit', params: { purchase: purchase.id } }">Edit</router-link>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</template>
<script>
    import axios from 'axios';
    import api from '../api/purchases';

    export default {
        data() {
            return {
                message: null,
                loading: false,
                saving: false,
                purchases: null,
                error: null,
                purchase: {
                    id: null,
                    title: "",
                    cost: "",
                    amount: 1
                }
            };
        },
        created() {
            this.fetchData();
        },
        methods: {
            fetchData() {
                this.error = this.purchases = null;
                this.loading = true;
                axios
                    .get('/api/purchases')
                    .then(response => {
                        this.loading = false;
                        this.purchases = response.data.data;
                    }).catch(error => {
                        this.loading = false;
                        this.error = error.response.data.message || error.message;
                    });
            },
            onSubmit(event) {
                this.saving = true;

                api.post( {
                    title: this.purchase.title,
                    cost: this.purchase.cost,
                    amount: this.purchase.amount,
                }).then((response) => {
                    this.message = 'Purchase Added';
                    setTimeout(() => this.message = null, 1500);
                    this.purchases.unshift(response.data.data);
                    this.purchase.title = '';
                    this.purchase.cost = '';
                    this.purchase.amount = 1;
                }).catch(error => {
                    console.log(error);
                    this.error = 'Something went wrong, please try again later';
                }).then(_ => this.saving = false);
            },
        }
    }
</script>