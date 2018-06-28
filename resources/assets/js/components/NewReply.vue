<template>
    <div>
        <div v-if="signedIn">

            <div class="form-group">
                <wysiwyg name="body" v-model="body" placeholder="Have something to say?" :shouldClean="completed"></wysiwyg>
            </div>
            <button class="btn bt-primary" @click="addReply">Post</button>


        </div>
        <div v-else="signedIn" class="text-center">Please <a href="/login">Sign In</a> to participate</div>
    </div>


</template>

<script>

    import 'jquery.caret';
    import 'at.js';
    export default {
        // props: ['endpoint'],

        data() {
            return {
                body: '',
                completed: false,
            }
        },

        computed: {

            endpoint(){
                return location.pathname + '/replies'
            }
        },

        mounted(){
            $('#body').atwho({
                at: "@",
                delay: 750,
                callbacks:{
                    remoteFilter: function(query, callback){
                        // console.log('CALLED');
                        $.getJSON("/api/users", {name: query}, function(usernames){
                           callback(usernames)
                        });
                    }
                }
            });
        },

        methods: {

            addReply() {
                axios.post(this.endpoint, {
                    body: this.body
                }).then(({data}) =>{
                    this.body = '';
                    this.completed = true;

                    flash('Your reply has been posted');
                    this.$emit('created', data);

                }).catch(error=>{
                    // console.log(error.response.data.errors.body);
                    if(error.response.data.errors !== undefined){
                        flash(error.response.data.errors.body[0], 'danger');
                    }else{
                        flash(error.response.data, 'danger');
                    }
                })

            }
        },
    }

</script>