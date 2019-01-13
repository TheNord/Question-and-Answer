<template>
    <div>
        <v-form @submit.prevent="update">
            <markdown-editor v-model="data.reply"></markdown-editor>
            <span class="red--text" v-if="errors.body">{{ errors.body[0] }} <br></span>
            <v-btn icon small type="submit">
                <v-icon color="teal">save</v-icon>
            </v-btn>

            <v-btn icon small @click="cancelEditing">
                <v-icon>cancel</v-icon>
            </v-btn>

        </v-form>
    </div>
</template>

<script>
    export default {
        props: ['data'],
        data() {
            return {
                errors: {}
            }
        },
        methods: {
            update() {
                let slug = this.data.question_slug;
                let id = this.data.id;
                let reply = this.data.reply;
                axios
                    .put(`/api/question/${slug}/reply/${id}`, {body: reply})
                    .then(res => this.cancelEditing())
                    .catch(error => this.errors = error.response.data.errors)
            },
            cancelEditing() {
                EventBus.$emit('cancelEditing')
            }
        }
    }
</script>