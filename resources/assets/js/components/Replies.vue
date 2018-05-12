<template>
    <div class="">
        <div class="" v-for="(reply, index) in items" :key="reply.id">
            <reply :data="reply" @deleted="remove(index)"></reply>
        </div>

        <new-reply @created="add"></new-reply>
    </div>
</template>

<script>

    import Reply from './Reply'
    import NewReply from '../components/NewReply';

    export default{

        props: ['data'],

        components: { Reply, NewReply },

        data() {
            return {
                items: this.data,
            }
        },

        methods: {
            add(reply) {
                this.items.push(reply);

                this.$emit('created');
                flash('Reply Created');
            },

            remove(index){
                this.items.splice(index, 1);

                this.$emit('removed');
                flash('Delete Reply');
            }
        }

    }
</script>