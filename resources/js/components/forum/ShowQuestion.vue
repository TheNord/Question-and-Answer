<template>
    <v-card>
        <v-container fluid>
            <alert :dialog="dialog"></alert>
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
            <v-card-actions v-if="own" @click="edit">
                <v-btn icon small>
                    <v-icon color="orange">edit</v-icon>
                </v-btn>
                <v-btn icon small @click="dialog.show = true">
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
                dialog: {
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
            EventBus.$on('dialog-canceled', () => {
              this.dialog.show = false;
            });

            EventBus.$on('dialog-confirmed', () => {
                this.dialog.show = false;
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
                    .delete(`/api/question/${this.data.slug}`)
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