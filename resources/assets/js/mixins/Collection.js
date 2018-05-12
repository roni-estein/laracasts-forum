export default {

    data() {
        return {
            items: [],
        }
    },

    methods:{

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