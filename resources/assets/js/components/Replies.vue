<template>
    <div class="">
        <div class="" v-for="(reply, index) in items">
            <reply :data="reply" @deleted="remove(index)"></reply>
        </div>
    </div>
</template>

<script>

    import Reply from './Reply'

    export default{

        props: ['data'],

        components: { Reply },

        data() {
            return {
                items: this.data,
            }
        },

        methods: {
            remove(index){
                this.items.splice(index, 1);

                this.$emit('removed');

                flash('Delete Reply');
            }
        }

    }
</script>