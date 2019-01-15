<template>
    <v-card>
        <v-container fluid grid-list-md>
            <v-layout row wrap>
                <v-flex xs12>
                    <alert :alert="deleteAlert"></alert>
                    <v-card-title>
                        <div>
                            <div class="headline">
                                {{ data.title }}
                            </div>
                            <span class="grey--text">{{data.user}} asked {{data.created_at}}</span>
                        </div>
                        <v-spacer></v-spacer>
                        <v-btn icon>
                            <v-icon color="light-blue darken-4" class="views">
                                remove_red_eye
                            </v-icon>
                        </v-btn>
                        <span class="count">{{data.views}}</span>

                        <v-btn icon>
                            <v-icon color="pink" class="answers">
                                question_answer
                            </v-icon>
                        </v-btn>
                        <span class="count">{{data.reply_count}}</span>
                    </v-card-title>
                    <v-divider></v-divider>
                </v-flex>

                <vote :data="data"></vote>

                <v-flex xs11>
                    <v-card-text v-html="body"></v-card-text>
                    <div class="question-tags">
                        <div v-for="tag in data.tags">
                            <a class="question-tag">{{tag.name}}</a>
                        </div>
                    </div>
                    <v-card-actions v-if="own">
                        <v-btn icon small @click="edit">
                            <v-icon color="orange">edit</v-icon>
                        </v-btn>
                        <v-btn icon small @click="deleteAlert.show = true">
                            <v-icon color="red">delete</v-icon>
                        </v-btn>
                    </v-card-actions>
                </v-flex>
            </v-layout>
        </v-container>
    </v-card>
</template>

<script>
    import alert from '../Alert';
    import Vote from '../votes/Vote'

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

            Echo.channel('replyChannel')
                .listen('CreateReplyEvent', (e) => {
                    this.data.reply_count++;
                    this.data.replies.push(e.reply)
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
            });

            EventBus.$on('deleteReply', () => {
                this.data.reply_count--
            });
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
                    .catch(error => {
                        let toast = this.$toasted.show(error.response.data.error, {
                            theme: "bubble",
                            position: "bottom-left",
                            duration : 5000,
                            icon : {
                                name : 'error'
                            }
                        });
                    })
            }
        },
        components: {
            alert, Vote
        }
    }
</script>

<style scoped>
    .v-card__text {
        font-size: 15px;
    }

    .views {
        font-size: 30px;
    }

    .answers {
        font-size: 26px;
    }

    .count {
        font-size: 15px;
    }

    .question-tag {
        position: relative;
        display: inline-block;
        padding: .4em .5em;
        margin: 2px 8px 2px 0;
        font-size: 11px;
        line-height: 1;
        white-space: nowrap;
        text-decoration: none;
        text-align: center;
        border-width: 1px;
        border-style: solid;
        border-radius: 3px;
        transition: all .15s ease-in-out;

        color: #39739d;
        background-color: #E1ECF4;
        border-color: #E1ECF4;
    }

    .question-tags {
        display: flex;
        padding-top: 12px;
        padding-left: 18px;
    }
</style>