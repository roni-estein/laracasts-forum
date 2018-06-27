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
                title: this.thread.title,
                body: this.thread.body,
                form: {}
            }
        },

        created() {
            this.resetForm();
        },

        methods: {
            toggleLock() {

                let uri = `/locked-threads/${this.thread.slug}`;

                axios[ this.locked ? 'delete' : 'post'](uri);

                this.locked =  ! this.locked;

            },

            update() {
                let uri = `/threads/${this.thread.channel.slug}/${this.thread.slug}`;

                axios.patch(uri, this.form).then(() => {
                    flash('Your thread has been updated');

                    this.title = this.form.title;
                    this.body = this.form.body;

                    this.editing = false;

                }).catch(error=>{
                    // console.log(error.response.data.errors.body);
                    if(error.response.data.errors !== undefined){
                        flash(error.response.data.errors.body[0], 'danger');
                    }else{
                        flash(error.response.data, 'danger');
                    }
                });


            },

            resetForm() {
                this.form.title = this.title;
                this.form.body = this.body;

                this.editing = false;
            },

        }

    }
</script>