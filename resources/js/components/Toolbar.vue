<template>
    <v-toolbar color="indigo" dark>
        <v-toolbar-side-icon></v-toolbar-side-icon>
        <v-toolbar-title>
            <router-link class="white--text home-link" to="/">Question & Answer</router-link>
        </v-toolbar-title>
        <v-spacer></v-spacer>
        <app-notification v-if="loggedIn"></app-notification>
        <div class="hidden-sm-and-down">

            <router-link
                    v-for="item in items"
                    :key="item.title"
                    :to="item.to"
                    v-if="item.show">
                <v-btn flat>{{ item.title}}</v-btn>
            </router-link>

        </div>
    </v-toolbar>
</template>

<script>
    import AppNotification from './AppNotification'

    export default {
        data() {
            return {
                items: [
                    {title: 'Forum', to: '/forum', show: true},
                    {title: 'Tags', to: '/tags', show: true},
                    {title: 'Login', to: '/login', show: !User.loggedIn()},
                    {title: 'Ask Question', to: '/ask', show: User.loggedIn()},
                    {title: 'Logout', to: '/logout', show: User.loggedIn()},
                ]
            }
        },
        created() {
            EventBus.$on('logout',() => {
                User.logout()
            })
        },
        computed: {
            loggedIn() {
                return User.loggedIn();
            }
        },
        components: {
            AppNotification
        }
    }
</script>

<style scoped>
.home-link {
    text-decoration: none;
}
</style>