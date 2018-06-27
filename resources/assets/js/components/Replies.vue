<template>
    <div class="">
        <div class="" v-for="(reply, index) in items" :key="reply.id">
            <reply :reply="reply" @deleted="remove(index)"></reply>
        </div>

        <paginator :dataSet="dataSet" @changed="fetch"></paginator>

        <new-reply @created="add" v-if="! $parent.locked"></new-reply>
    </div>
</template>

<script>

    import Reply from './Reply'
    import NewReply from '../components/NewReply';
    import collection from '../mixins/Collection';

    export default{

        props: ['data'],


        components: { Reply, NewReply },

        mixins: [collection],

        data() {
            return {
                dataSet: {},

            }
        },

        created() {
            this.fetch();
        },

        methods: {

            fetch(page){
                axios.get(this.url(page))
                    .then(this.refresh)
                    .catch(error=>{
                        // console.log(error.response.data.errors.body);
                        if(error.response.data.errors !== undefined){
                            flash(error.response.data.errors.body[0], 'danger');
                        }else{
                            flash(error.response.data, 'danger');
                        }
                    });
            },

            refresh({data:dataSet}) {
                // console.log(dataSet);
                this.dataSet = dataSet;
                this.items = dataSet.data;

                window.scrollTo(0, 0);

            },

            url(page) {

                if (! page){
                    let query = location.search.match(/page=(\d+)/);
                    page = query ? query[1] : 1;
                }

                return `${location.pathname}/replies?page=${page}`
            },

        }

    }
</script>