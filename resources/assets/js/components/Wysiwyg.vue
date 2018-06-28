<template>
    <div>
        <input id="trix" type="hidden" :name="name" :value="value">

        <trix-editor ref=trix input="trix" :placeholder="placeholder"></trix-editor>
    </div>
</template>

<script>
    import Trix from 'trix';
    export default {
        props: ['name', 'value', 'placeholder', 'shouldClear'],

        mixins: [],

        components: {},

        data() {
            return {
                data: '',
            }
        },

        computed:{},

        watch: {},

        methods: {},

        mounted(){
            this.$refs.trix.addEventListener('trix-change', e => {
                this.$emit('input', e.target.innerHTML);
            });

            this.$watch('shouldClear', () => {
               this.$refs.trix.value = '';
            });
        },
    }
</script>