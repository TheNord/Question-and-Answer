<template>
    <div class="mt-3">
        <v-card>
            <v-card-title>
                <div class="headline">{{data.user}}</div>
                <div class="ml-2">
                    <vue-moments-ago prefix="said" suffix="ago" :date="data.created_at.date"></vue-moments-ago>
                </div>
                <v-spacer></v-spacer>
                <like :data="data"></like>
            </v-card-title>
            <v-divider></v-divider>
            <edit-reply
                    v-if="editing"
                    :data="data"
            ></edit-reply>
            <span class="red--text" v-if="errors.body">{{ errors.body[0] }}</span>
            <div v-if="!editing">
                <v-card-text v-html="reply"></v-card-text>

                <div v-for="comment in data.comments">
                    <v-divider></v-divider>
                    <v-card-text class="comment">{{comment.body}} - {{comment.user}} <span class="text--grey"><vue-moments-ago prefix="said" suffix="ago" :date="comment.created_at.date"></vue-moments-ago></span>
                    </v-card-text>
                    <v-divider></v-divider>
                </div>

                <add-comment :data="data.id" v-if="commenting"></add-comment>
                <v-card-actions v-if="!commenting">
                    <a href="#" class="comment-link" @click.prevent="comment">add a comment</a>
                </v-card-actions>
                <v-divider></v-divider>
                <v-card-actions v-if="own">
                    <v-btn icon small @click="edit">
                        <v-icon color="orange">edit</v-icon>
                    </v-btn>
                    <v-btn icon small @click="destroy(index)">
                        <v-icon color="red">delete</v-icon>
                    </v-btn>
                </v-card-actions>
            </div>
        </v-card>
    </div>
</template>

<script>
    import EditReply from './EditReply';
    import Like from '../likes/Like';
    import AddComment from './AddComment';
    import VueMomentsAgo from 'vue-moments-ago'

    export default {
        props: ['data', 'index'],
        data() {
            return {
                editing: false,
                commenting: false,
                errors: {}
            }
        },
        created() {
            EventBus.$on('cancelEditing', () => {
                this.editing = false
            });
            EventBus.$on('cancelCommenting', () => {
                this.commenting = false
            });
            EventBus.$on('newComment', (res) => {
                this.addComment(res)

            });
            Echo.channel('replyChannel')
                .listen('NewReplyEvent', e => {
                    if (this.data.id === e.id) {
                        this.data.comments.push(e.comment);
                    }
                });

        },
        computed: {
            own() {
                return User.own(this.data.user_id);
            },
            reply() {
                return md.parse(this.data.reply)
            }
        },
        methods: {
            destroy() {
                EventBus.$emit('deleteReply', this.index)
            },
            edit() {
                this.editing = true
            },
            comment() {
                this.commenting = true;
            },
            addComment(res) {
                this.commenting = false;
                if (this.data.id === res.id) {
                    this.data.comments.push(res.data);
                }
            }
        },
        components: {
            EditReply,
            Like,
            AddComment,
            VueMomentsAgo
        }
    }
</script>

<style scoped>
    .comment-link {
        color: #848d95;
        padding-left: 10px;
        padding-bottom: 4px;
    }

    .text--grey {
        color: #848d95;
    }

    .v-card__text {
        font-size: 15px;
    }

    .comment {
        font-size: 12px;
    }
</style>