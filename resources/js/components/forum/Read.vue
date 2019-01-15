<template>
    <div v-if="question">
        <edit-question
            v-if="editing"
            :form="question"
        ></edit-question>

        <show-question
             v-else
            :data="question"
        ></show-question>

        <replies
            :replies="question.replies"
            :question-slug="question.slug"
        ></replies>

        <new-reply v-if="loggedIn && showCreate" :question-slug="question.slug"></new-reply>
        <div v-else>
            <v-container>
                <router-link to="/login">Login</router-link> in to Reply or
                <router-link to="/signup">sign up</router-link>
            </v-container>
        </div>

    </div>
</template>

<script>
    import ShowQuestion from './ShowQuestion'
    import EditQuestion from  './EditQuestion'
    import Replies from  '../reply/Replies'
    import NewReply from  '../reply/NewReply'

    export default {
        data() {
            return {
                question: null,
                editing: false,
                showCreate: true
            }
        },
        created() {
            this.listen();
            this.getQuestion();
        },
        computed: {
            loggedIn() {
                return User.loggedIn()
            }
        },
        methods: {
            listen() {
                EventBus.$on('newReply', () => {
                    this.showCreate = false;
                    this.$nextTick(() => {
                        this.showCreate = true;
                    });
                });

                EventBus.$on('startEditing', () => {
                    this.editing = true;
                });

                EventBus.$on('cancelEditing', () => {
                    this.editing = false;
                });

                EventBus.$on('updateQuestion', (res) => {
                    this.question = res;
                })
            },
            getQuestion() {
                axios
                    .get(`/api/question/${this.$route.params.slug}`)
                    .then(res => this.question = res.data.data)
            }
        },
        components: {
            ShowQuestion,
            EditQuestion,
            Replies,
            NewReply
        }
    }
</script>