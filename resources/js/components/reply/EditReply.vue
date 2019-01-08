<template>
    <div>
        <v-form @submit.prevent="update">
            <markdown-editor v-model="reply.reply"></markdown-editor>
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
        props: ['reply'],
        data() {
            return {
                errors: {}
            }
        },
        methods: {
            update() {
                EventBus.$emit('updateReply', this.reply.reply)
            },
            cancelEditing() {
                EventBus.$emit('cancelEditing')
            }
        }
    }
</script>