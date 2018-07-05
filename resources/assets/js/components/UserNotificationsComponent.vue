<template>
    <li class="dropdown" v-if="notifications.length">
        <a href="#" data-toggle="dropdown">
            <span class="glyphicon glyphicon-bell"></span>
        </a>
        <ul class="dropdown-menu">
            <li v-for="(notification, index) in notifications">
                <a :href="notification.data.link" v-text="notification.data.message" @click="markAsRead(notification.id, index)"></a>
            </li>
        </ul>
    </li>
</template>

<script>
    export default {
        data () {
            return {
                notifications: false,
            }
        },
        methods: {
            markAsRead (notificationId, index) {
                axios
                    .delete(`/profiles/${window.App.user.name}/notifications/${notificationId}`);

                this.notifications.splice(index, 1);
            }
        },
        created () {
            axios
                .get(`/profiles/${window.App.user.name}/notifications`)
                .then(function (response) {
                    this.notifications = response.data;
                }.bind(this))
        }
    }
</script>

<style scoped>

</style>