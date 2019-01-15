<template>
    <v-form @submit.prevent="addComment">
        <div class="comment">
            <v-textarea
                    outline
                    clearable
                    hide-details
                    name="input-7-4"
                    label="Write your comment"
                    v-model="body"
            ></v-textarea>
            <span class="red--text" v-if="errors.body">{{ errors.body[0] }} <br></span>
        </div>

        <div class="actions">
            <v-btn icon small type="submit">
                <v-icon color="teal">save</v-icon>
            </v-btn>

            <v-btn icon small @click="cancel">
                <v-icon>cancel</v-icon>
            </v-btn>
        </div>

    </v-form>
</template>

<script>
    export default {
        props: ['data'],
        data() {
            return {
                body: null,
                errors: {}
            }
        },
        methods: {
            addComment() {
                axios
                    .post(`/api/reply/${this.data}/comment`, {body: this.body})
                    .then(res => {
                        this.body = null;
                        EventBus.$emit('newComment', {data: res.data, id: this.data});
                    })
                    .catch(error => this.errors = error.response.data.errors)
            },
            cancel() {
                EventBus.$emit('cancelCommenting');
            }
        }
    }
</script>

<style scoped>
    .actions {
        margin-left: 6px;
        margin-top: 8px;
    }

    .comment {
        width: 600px;
        margin-left: 18px;
        padding-top: 10px;
    }
</style>