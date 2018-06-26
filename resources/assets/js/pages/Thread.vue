<script>
    import Replies from '../components/Replies';
    import SubscribeButton from '../components/SubScribeButton';

    export default {

        props: ['thread'],
        components: { Replies, SubscribeButton },

        data() {
            return {
                repliesCount: this.thread.replies_count,
                locked: this.thread.locked,
                editing: false,
            }
        },

        methods: {
            toggleLock() {

                axios[ this.locked ? 'delete' : 'post']('/locked-threads/' + this.thread.slug);

                this.locked =  ! this.locked;

            }
        }

    }
</script>