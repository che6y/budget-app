<template>
    <div>
        <div v-if="message" class="alert">{{ message }}</div>
        <div v-if="! loaded">Loading...</div>
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
                <input class="form-control mx-sm-6" id="purchase_amount" type="number" v-model="purchase.amount" min="1" step="1" />
            </div>
            <div class="form-group col-md-6">
                <button class="btn btn-primary" type="submit" :disabled="saving">Update</button>
            </div>
        </form>
    </div>
</template>
<script>
    import api from '../api/purchases';

    export default {
        data() {
            return {
                message: null,
                saving:false,
                loaded: false,
                purchase: {
                    id: null,
                    title: "",
                    cost: "",
                    amount: null
                }
            };
        },
        methods: {
            onSubmit(event) {
                this.saving = true;

                api.update(this.purchase.id, {
                    title: this.purchase.title,
                    cost: this.purchase.cost,
                    amount: this.purchase.amount,
                }).then((response) => {
                    this.message = 'Purchase updated';
                    setTimeout(() => this.message = null, 2000);
                    this.purchase = response.data.data;
                }).catch(error => {
                    console.log(error)
                }).then(_ => this.saving = false);
            },
        },
        created() {
            api.find(this.$route.params.purchase).then((response) => {
                this.loaded = true;
                this.purchase = response.data.data;
            });
        }
    };
</script>