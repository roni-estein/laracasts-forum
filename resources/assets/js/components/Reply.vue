<template>
    <div :id="'reply-'+id" class="panel" :class="isBest ? 'panel-success' : 'panel-default'">
        <div class="panel-heading">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/'+data.owner.name" v-text="data.owner.name"></a>
                    said <span v-text="ago"></span>
                </h5>


                <div v-if="signedIn">
                    <favorite :reply="data"></favorite>
                </div>

            </div>
        </div>
        <div class="panel-body">
            <div v-if="editing">
                <form @submit.prevent="update">
                    <div class="form-group">
                        <textarea class="form-control" v-model="body" required></textarea>
                    </div>
                    <button class="btn btn-xs btn-primary">Update</button>
                    <button class="btn btn-xs btn-link" @click="editing=false" type="button">Cancel</button>
                </form>
            </div>
            <div v-else>
                <div class="body" v-html="body"></div>
            </div>
        </div>
        <!--@can('update',$reply)-->
        <div class="panel-footer level" v-if="authorize('updateReply', reply)">
            <button class="btn btn-xs mr-1" @click="editing=true">Edit</button>
            <button type="submit" class="btn btn-danger btn-xs" @click="destroy">Delete</button>
            <button type="submit" class="btn btn-default btn-xs ml-auto" @click="markBest" v-show="!isBest">Best Reply</button>
        </div>
        <!--@endcan-->

    </div>
</template>

<script>
    import Favorite from './Favorite';
    import moment from 'moment';

    export default {

        props: ['data'],

        components: {
            Favorite
        },

        data() {
            return {
                editing: false,
                id: this.data.id,
                body: this.data.body,
                isBest: this.data.isBest,
                reply: this.data,
            }
        },

        computed: {

            ago() {
                return moment(this.data.created_at).subtract(5, 'hours').fromNow() + '...';
            },

        },

        created() {
            window.events.$on('best-reply-selected', id => {
                this.isBest = (id === this.id);
            });
        },

        methods: {
            update() {
                axios.patch('/replies/' + this.id, {
                    body: this.body,
                }).catch(error => {
                    // console.log(error.response.data.errors.body);
                    if (error.response.data.errors !== undefined) {
                        flash(error.response.data.errors.body[0], 'danger');
                    } else {
                        flash(error.response.data, 'danger');
                    }
                }).then(({}) => {
                    this.editing = false;
                    flash('Updated Reply!')
                });


            },

            markBest(){
                // this.isBest = true;
                window.events.$emit('best-reply-selected', this.reply.id );
                axios.post('/replies/'+ this.reply.id +'/best')
            },

            destroy() {
                axios.delete('/replies/' + this.data.id);

                this.$emit('deleted', this.data.id);

            },

        },


    }
</script>
