<template>
    <li class="dropdown" v-if="notifications.length">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
           aria-expanded="false" aria-haspopup="true">
             <span class="glyphicon glyphicon-bell"></span>
            <!--<span class="caret"></span>-->
        </a>
        <ul class="dropdown-menu">
            <li v-for="(notification, index) in notifications" :key="notification.id">
                <a :href="notification.data.link" v-text="notification.data.message" @click="markAsRead(notification)"></a>
            </li>
            <!--<li>-->
                <!--<a href="#">Notification 1</a>-->
            <!--</li>-->
            <!--<li>-->
                <!--<a href="#">Notification 2</a>-->
            <!--</li>-->
        </ul>
    </li>

</template>

<script>
    export default {
        props: {},

        mixins: [],

        components: {},

        data() {
            return {
                notifications: false,
            }
        },

        computed: {},

        watch: {},

        methods: {
            markAsRead(notification){
                axios.delete(`/profiles/${window.App.user.name}/notifications/${notification.id}`);

            },
        },

        created() {
            axios.get("/profiles/" + window.App.user.name + "/notifications/")
                .then(response => this.notifications = response.data );

        },
    }
</script>