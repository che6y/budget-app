<template>
    <div class="users">
        <div class="loading" v-if="loading">
            Loading...
        </div>

        <div v-if="error" class="error">
            {{ error }}
        </div>

        <ul v-if="purchases"class="purchase-items">
            <li v-for="purchase in purchases" class="purchase-item">
                {{ purchase.title }} {{ purchase.cost * purchase.amount}} baht
                <router-link :to="{ name: 'purchases.edit', params: { idgit  } }">Edit</router-link>
            </li>
        </ul>
    </div>
</template>
<script>
    import axios from 'axios';
    export default {
        data() {
            return {
                loading: false,
                purchases: null,
                error: null,
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
            }
        }
    }
</script>