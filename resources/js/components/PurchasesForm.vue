<template>
    <form class="form-row justify-content-start" @submit.prevent="onSubmit($event)">
        <input v-model="purchase.cat_id" type="hidden" />
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
</template>

<script>
    export default {
        name: "PurchasesForm"
    }
</script>

<style scoped>

</style>