<template>

    <div class="scheduled-purchases">

    </div>

</template>
<script>
    import axios from 'axios';
    import scheduled_api from '../api/scheduled';
    import * as moment from 'moment';

    export default {
        props: [ 'categories', 'addCategory' ],
        data() {
            return {
                message    : null,
                error      : null,
                saving     : false,
                purchases  : null,
            };
        },

        created() {
            this.fetchPurchaseData();
        },
        methods: {
            fetchPurchaseData() {
                this.error = this.purchases = null;
                this.saving = true;
                axios
                    .get('/api/s-purchases')
                    .then(response => {
                        this.purchases = response.data.data;
                        this.saving = false;
                    }).catch(error => {
                    this.saving = false;
                    this.error = error.response.data.message || error.message;
                });
            },

            newPurchaseToArr( newPurchase ) {
                this.purchases.unshift(newPurchase);
            },

            onPurchaseEdit( event, purchase ) {
                this.saving = true;

                scheduled_api.update(purchase.id, {
                    title: purchase.title,
                    cost: purchase.cost,
                    amount: purchase.amount,
                    category_id: purchase.category_id,
                    created_at: purchase.created_at
                }).then((response) => {
                    this.message = 'Purchase updated';
                    setTimeout(() => this.message = null, 3000);
                    $('.purchase-edit').hide();
                    this.saving = false;
                }).catch(error => {
                    console.log(error);
                    this.saving = false;
                });
            },
            onPurchaseDelete( event, purchase ) {
                this.saving = true;
                const sum   = purchase.amount * purchase.cost;
                const id    = purchase.id;

                $(event.target).closest('.list-group-item').hide();
                scheduled_api
                    .delete( purchase.id )
                    .then(( response ) => {
                        this.changeSummary( sum, '-');
                        const index = this.purchases.findIndex(function ( purchase ) {
                            return purchase.id === id;
                        });
                        this.purchases.slice(index, 1);

                        this.message = 'Item Deleted';
                        setTimeout(() => this.message = null, 3000);
                        $('.purchase-edit').hide();
                    })
                    .catch( error => {
                        console.log(error);
                        this.error = 'Something went wrong, please try again later';
                        setTimeout(() => this.error = null, 3000);
                    }).then(_ => this.saving = false);
            },
            onBtnClick( event, index, purchase ) {
                const parent = $(event.target).closest('#purchase-'+index);

                if ( parent.find('.purchase-edit').css('display') === 'none' ){
                    $('#purchase_date').datepicker({
                        dateFormat: "yy-mm-dd",
                        changeMonth: true,
                        minDate: '-1y',
                        maxDate: '+1m',
                        onSelect:function(selectedDate, datePicker) {
                            purchase.created_at = selectedDate;
                        }
                    });

                    parent.find('.purchase-edit').show();
                    parent.siblings().find('.purchase-edit').hide();
                } else {
                    parent.find('.purchase-edit').hide();
                }
            },
            changeIcon( event, purchase, categories ){
                let value = $(event.target).val();
                for ( var i in categories ){
                    if ( parseInt( value ) === categories[i].id ){
                        return purchase.icon = categories[i].icon;
                    }
                }
            },
            formatDate( date ) {
                return moment( date ).format( 'D MMM' );
            },
        },

    }
</script>

<style scoped>
    .fa {
        padding-right: 10px;
    }
    .purchase-edit {
        display: none;
    }
