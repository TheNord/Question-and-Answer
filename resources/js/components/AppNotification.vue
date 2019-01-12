<template>
    <div class="text-xs-center">
        <v-menu offset-y>
            <v-btn icon slot="activator">
                <v-icon :color="color">add_alert</v-icon>
                {{unreadCount}}
            </v-btn>

            <v-list class="notifications-block">
                <div v-if="unreadCount > 0">
                    <h4 class="notification-title">Unread notifications:</h4>

                    <v-list-tile v-for="item in unread" :key="item.id" class="notification-item">
                        <router-link :to="item.path">
                            <v-list-tile-title @click="readIt(item)">{{item.question}}</v-list-tile-title>
                        </router-link>
                        <span class="notification-item-type"> ({{item.typeNotify}})</span>
                    </v-list-tile>

                    <v-btn @click="readAll" small color="pink" dark>
                        Mark All as Read
                    </v-btn>
                </div>
                <span v-else class="notification-title">No unread notifications</span>
            </v-list>
        </v-menu>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                read: {},
                unread: {},
                unreadCount: 0,
                sound: 'http://soundbible.com/mp3/Air%20Plane%20Ding-SoundBible.com-496729130.mp3'
            }
        },
        created() {
            if (User.loggedIn()) {
                this.getNotifications()
            }

            Echo.private('App.User.' + User.id())
                .notification((notification) => {
                    this.unread.unshift(notification);
                    this.unreadCount++
                    this.playSound()
                });
        },
        methods: {
            getNotifications() {
                axios
                    .post('/api/notifications')
                    .then(res => {
                        this.unread = res.data.unread;
                        this.unreadCount = res.data.unread.length
                    })
                    .catch(error => Exception.handle(error))
            },
            readIt(notification) {
                axios
                    .post('/api/notifications/markAsRead', {id: notification.id})
                    .then(res => {
                        this.unread.splice(notification, 1);
                        this.unreadCount--
                    })
                    .catch(error => console.log(error))
            },
            readAll() {
                axios
                    .post('/api/notifications/markAsReadAll')
                    .then(res => {
                        this.unread = [];
                        this.unreadCount = 0
                    })
                    .catch(error => console.log(error))
            },
            playSound() {
                let alert = new Audio(this.sound);
                alert.play()
            }
        },
        computed: {
            color() {
                return this.unreadCount > 0 ? 'blue' : 'blue lighten-2';
            }
        },
    }
</script>

<style scoped>
    .notifications-block {
        min-width: 400px
    }

    .notification-title {
        margin: 5px 0 0 15px
    }

    .notification-item:not(:last-child) {
        border-bottom: solid #a5aba5 1px
    }

    .notification-item-type {
        font-size: 11px;
        margin-left: 7px;
    }
</style>