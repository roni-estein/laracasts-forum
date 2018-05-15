<template>
    <div>
        <button :class="classes" @click="subscribe" v-text="this.actionName"></button>
    </div>
</template>

<script>
    export default {
        props: ['active'],

        mixins: [],

        components: {},

        data() {
            return {
                subscribed: this.active,
            }
        },

        computed:{
            classes(){
                return ['btn',this.subscribed ? 'btn-primary' : 'btn-default'];
            },
            actionName(){
                return this.subscribed ? 'Unsubscribe':'Subscribe';
            }
        },

        watch: {},

        methods: {
            subscribe(){

                 axios[this.subscribed ? 'delete':'post'](location.pathname + '/subscriptions');


                 flash(this.actionName + 'd');

                this.subscribed = ! this.subscribed;
            },
        },

        mounted(){},
    }
</script>