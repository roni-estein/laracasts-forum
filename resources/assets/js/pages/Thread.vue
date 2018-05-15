<script>
    import Replies from '../components/Replies';
    import SubscribeButton from '../components/SubScribeButton';

    export default {

        props: ['initialRepliesCount'],
        components: { Replies, SubscribeButton },

        data() {
            return {
                repliesCount: this.initialRepliesCount,
            }
        }

    }
</script>