<template>
    <v-card>
        <v-container fluid>
            <alert :alert="deleteAlert"></alert>
            <v-card-title>
                <div>
                    <div class="headline">
                        {{ data.title }}
                    </div>
                    <span class="grey--text">{{data.user}} said {{data.created_at}}</span>
                    <span
                          class="grey--text"
                          v-if="data.updated_at !== data.created_at"
                    >
                        (updated {{data.updated_at}})
                    </span>
                </div>
                <v-spacer></v-spacer>
                <v-btn color="teal" dark>{{data.reply_count}} Replies</v-btn>
            </v-card-title>

            <v-card-text v-html="body"></v-card-text>
            <v-card-actions v-if="own">
                <v-btn icon small @click="edit">
                    <v-icon color="orange">edit</v-icon>
                </v-btn>
                <v-btn icon small @click="deleteAlert.show = true">
                    <v-icon color="red">delete</v-icon>
                </v-btn>
            </v-card-actions>
        </v-container>
    </v-card>
</template>

<script>
    import alert from '../Alert';

    export default {
        props: ['data'],
        data() {
            return {
                own: User.own(this.data.user_id),
                slug: this.data.slug,
                deleteAlert: {
                    show: false,
                    header: 'Waring',
                    text: 'Do you really want to delete the question?'
                }
            }
        },
        created() {
            Echo.private('App.User.' + User.id())
                .notification((notification) => {
                    this.data.reply_count++
                });

            Echo.channel('deleteReplyChannel')
                .listen('DeleteReplyEvent', (e) => {
                    this.data.reply_count--
                });

            EventBus.$on('newReply', () => {
                this.data.reply_count++
            });

            EventBus.$on('deleteReply', () => {
                this.data.reply_count--
            })
        },
        mounted() {
            EventBus.$on('alert-canceled', () => {
              this.deleteAlert.show = false;
            });

            EventBus.$on('alert-confirmed', () => {
                this.deleteAlert.show = false;
                this.destroy()
            });
        },
        computed: {
            body() {
                return md.parse(this.data.body);
            }
        },
        methods: {
            edit() {
                EventBus.$emit('startEditing')

            },
            destroy() {
                axios
                    .delete(`/api/question/${this.slug}`)
                    .then(res => this.$router.push('/forum'))
                    .catch(error => console.log(error))
            }
        },
        components: {
            alert
        }
    }
</script>

<style scoped>

</style>