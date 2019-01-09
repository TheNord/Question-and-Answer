<template>
    <div class="mt-3">
        <v-card>
            <v-card-title>
                <div class="headline">{{data.user}}</div>
                <div class="ml-2">said {{data.created_at}}</div>
                <v-spacer></v-spacer>
                <like :data="data"></like>
            </v-card-title>
            <v-divider></v-divider>
            <edit-reply
                    v-if="editing"
                    :reply="data"
            ></edit-reply>
            <span class="red--text" v-if="errors.body">{{ errors.body[0] }}</span>
            <div v-else>
                <v-card-text v-html="reply"></v-card-text>
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

    export default {
        props: ['data', 'index'],
        data() {
            return {
                editing: false,
                errors: {}
            }
        },
        created() {
            EventBus.$on('cancelEditing', () => {
                this.editing = false
            });
            EventBus.$on('updateReply', (reply) => {
                this.update(reply);
            })
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
            update(reply) {
                axios
                    .put(`/api/question/${this.data.question_slug}/reply/${this.data.id}`, {body: reply})
                    .then(res => this.editing = false)
                    .catch(error => this.errors = error.response.data.errors)
            }
        },
        components: {
            EditReply,
            Like
        }
    }
</script>

<style scoped>

</style>