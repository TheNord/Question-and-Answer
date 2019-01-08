<template>
    <v-container>
        <div>
            <h2>New reply</h2>
            <v-form @submit.prevent="create">
                <markdown-editor v-model="body"></markdown-editor>
                <span class="red--text" v-if="errors.body">{{ errors.body[0] }} <br></span>

                <v-btn
                        color="green"
                        type="submit"
                        dark
                >Create
                </v-btn>
            </v-form>
        </div>
    </v-container>
</template>

<script>
    export default {
        props: ['QuestionSlug'],
        data() {
            return {
                errors: {},
                body: null
            }
        },
        methods: {
            create() {
                axios
                    .post(`/api/question/${this.QuestionSlug}/reply`, {body: this.body})
                    .then(res => {
                        this.body = null;
                        EventBus.$emit('newReply', res.data.reply);
                    })
                    .catch(error => this.errors = error.response.data.errors)
            }
        }
    }
</script>

<style scoped>

</style>