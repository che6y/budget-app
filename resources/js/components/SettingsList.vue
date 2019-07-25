<template>
    <div class="list-of-settings">

        <div v-if="message" class="alert alert-success" role="alert">{{ message }}</div>
        <div v-if="error" class="alert alert-danger" role="alert">{{ error }}</div>

        <ul v-if="options" class="list-group">
            <li v-for="option in options" class="list-group-item">
                <form class="row" @submit.prevent="updateOption(option)">
                    <input type="hidden" name="key" v-model="option.key" />
                    <div class="form-group col-md-6">
                        <label for="option-value">{{ option.name }}</label>
                        <input class="form-control" id="option-value" v-model="option.value" required />
                    </div>
                    <div class="form-group col-md-2">
                        <button class="btn btn-outline-primary" type="submit">Update</button>
                    </div>
                </form>
            </li>
        </ul>
    </div>
</template>

<script>
    import axios from 'axios';
    import options_api from '../api/options';

    export default {
        props: [],
        data() {
            return {
                message: null,
                error: null,
                options: null,
            };
        },

        created() {
            this.fetchOptions();
        },
        methods: {
            fetchOptions(){
                this.error = this.categories = null;
                this.loading = true;
                axios
                    .get( '/api/options' )
                    .then( response => {
                        this.loading = false;
                        this.options = response.data;
                    }).catch( error => {
                    this.loading = false;
                    this.error = error.response.data.message || error.message;
                });
            },
            updateOption( option ) {
                options_api.update(option.id, {
                    value : option.value
                }).then( (response) => {
                    this.saving = true;
                    this.message = response.data.message;
                    setTimeout(() => this.message = null, 3000);
                }).catch( error => {
                    this.error = 'Something went wrong, please try again later';
                    setTimeout(() => this.error = null, 3000);
                }).then(_ => this.saving = false);
            }
        }

    }
</script>

<style scoped>

</style>