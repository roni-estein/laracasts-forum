<template>
    <div>
        <div v-if="signedIn">

            <div class="form-group">
                <textarea v-model="body" class="form-control" placeholder="Have something to say?" rows="5" required></textarea>
            </div>
            <button class="btn bt-primary" @click="addReply">Post</button>


        </div>
        <div class="text-center">Please <a href="/login">Sign In</a> to participate</div>
    </div>


</template>

<script>

    export default {
        // props: ['endpoint'],

        data() {
            return {
                body: '',
            }
        },

        computed: {
            signedIn() {
                return window.App.signedIn;
            },

            endpoint(){
                return location.pathname + '/replies'
            }
        },

        methods: {

            addReply() {
                axios.post(this.endpoint, {
                    body: this.body
                }).then(({data}) =>{
                    this.body = '';

                    flash('Your reply has been posted');
                    this.$emit('created', data);

                }).catch(error=>{
                    flash(error.response.data, 'danger');
                })

            }
        },
    }

</script>