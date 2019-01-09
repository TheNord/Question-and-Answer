<template>
    <v-container fluid>
        <v-card>
            <v-form @submit.prevent="update">
                <v-text-field
                        v-model="form.title"
                        label="Title"
                        type="text"
                        required
                ></v-text-field>
                <span class="red--text" v-if="errors.title">{{ errors.title[0] }}</span>

                <markdown-editor v-model="form.body"></markdown-editor>
                <span class="red--text" v-if="errors.body">{{ errors.body[0] }}</span>

                <v-card-actions>
                    <v-btn icon small type="submit">
                        <v-icon color="teal">save</v-icon>
                    </v-btn>

                    <v-btn icon small @click="cancel">
                        <v-icon>cancel</v-icon>
                    </v-btn>
                </v-card-actions>
            </v-form>
        </v-card>
    </v-container>
</template>

<script>
    export default {
        props: {
            form: {
                title: null,
                body: null
            },
        },
        data() {
            return {
                errors: {}
            }
        },
        methods: {
            cancel() {
                EventBus.$emit('cancelEditing')
            },
            update() {
                axios
                    .put(`/api/question/${this.form.slug}`, this.form)
                    .then(res => this.cancel())
                    .catch(error => this.errors = error.response.data.errors)
            }
        },
    }
</script>

<style scoped>

</style>