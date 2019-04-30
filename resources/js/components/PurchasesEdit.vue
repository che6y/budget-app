<template>
    <div>
        <div v-if="message" class="alert alert-success" role="alert">{{ message }}</div>
        <div v-if="! loaded">Loading...</div>
        <form class="form-row justify-content-start" @submit.prevent="onSubmit($event)">
            <div class="form-group col-md-6">
                <label for="purchase_title">Title</label>
                <input class="form-control" id="purchase_title" v-model="purchase.title" required />
            </div>
            <div class="form-group col-md-3">
                <label for="purchase_cost">Price</label>
                <input class="form-control" id="purchase_cost" type="number" v-model="purchase.cost" required />
            </div>
            <div class="form-group col-md-3">
                <label for="purchase_amount">Amount</label>
                <input class="form-control mx-sm-6" id="purchase_amount" type="number" v-model="purchase.amount" min="1" step="1" required />
            </div>
            <div class="form-group col-md-8">
                <button class="btn btn-outline-primary" type="submit" :disabled="saving">
                    Update
                </button>
                <button class="btn btn-outline-secondary" type="button" @click="$router.go(-1)">Cancel</button>
                <button class="btn btn-outline-danger" :disabled="saving" @click.prevent="onDelete($event)">Delete</button>
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
            onDelete() {
                this.saving = true;

                api.delete(this.purchase.id)
                    .then((response) => {
                        this.message = 'Item Deleted';
                        setTimeout(() => this.$router.push({ name: 'purchases.index' }), 2000);
                    });
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