<template>
    <div>
        <div class="level">
            <img :src="avatar" width="50" class="mr-1">
            <h1>
                {{ profileUser.name }}
                <small>Since {{ profileUser.created_at }} ...</small>
            </h1>
        </div>


        <form v-if="canUpdate" method="post" enctype="multipart/form-data">
            <image-upload  name="avatar" @loaded="onLoad"></image-upload>
        </form>



    </div>
</template>

<script>
    import ImageUpload from './ImageUpload'

    export default {
        props: ['profileUser'],

        mixins: [],

        components: { ImageUpload },

        data() {
            return {
                avatar: this.profileUser.avatar_path

            }
        },

        computed:{
            canUpdate(){
                return this.authorize(signedInUser => signedInUser.id === this.profileUser.id)
            },
        },

        watch: {},

        methods: {

            onLoad(avatar){
                this.avatar = avatar.src;
                this.persist( avatar.file )
            },

            persist(avatar){
                let data = new FormData();

                data.append('avatar', avatar);

                axios.post(`/api/users/${this.profileUser.name}/avatar`, data )
                    .then(() => flash('Avatar Uploaded'))

                    .catch(({data}) => {
                    console.log('data');
                    console.log('---------------------------------');
                    console.log(data);
                        flash('ERROR','danger')
                });

            },
        },

        mounted(){
        },
    }
</script>