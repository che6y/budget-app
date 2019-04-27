<template>
    <div>
        <div v-if="message" class="alert">{{ message }}</div>
        <div v-if="! loaded">Loading...</div>
        <form @submit.prevent="onSubmit($event)">
            <div class="form-group">
                <label for="purchase_title">Title</label>
                <input id="purchase_title" v-model="purchase.title" />
            </div>
            <div class="form-group">
                <label for="purchase_cost">Price</label>
                <input id="purchase_cost" type="number" v-model="purchase.cost" />
            </div>
            <div class="form-group">
                <button type="submit" :disabled="saving">Update</button>
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
                    cost: ""
                }
            };
        },
        methods: {
            onSubmit(event) {
                this.saving = true;

                api.update(this.purchase.id, {
                    title: this.purchase.title,
                    cost: this.purchase.cost,
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
            api.find(this.$route.params.id).then((response) => {
                this.loaded = true;
                this.purchase = response.data.data;
            });
        }
    };
</script>