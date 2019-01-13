<template>
    <v-container fluid>
        <v-card class="question-edit">
            <v-form @submit.prevent="update">
                <v-text-field
                        v-model="form.title"
                        label="Title"
                        type="text"
                        required
                ></v-text-field>
                <span class="red--text" v-if="errors.title">{{ errors.title[0] }}</span>

                <v-autocomplete
                        :items="tags"
                        v-model="tags_id"
                        item-text="name"
                        item-value="id"
                        label="Tag"
                        multiple
                        chips
                        autocomplete
                >
                    <template
                            slot="selection"
                            slot-scope="data"
                    >
                        <v-chip
                                :selected="data.selected"
                                close
                                class="chip--select-multi"
                                @input="remove(data)"
                        >
                            {{ data.item.name }}
                        </v-chip>
                    </template>
                </v-autocomplete>
                <span class="red--text" v-if="errors.tag_id">{{ errors.tag_id[0] }}</span>

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
                body: null,
                tags: []
            },
        },
        data() {
            return {
                errors: {},
                tags: [],
                tags_id: []
            }
        },
        created() {
            this.form.tags.forEach((item) => {
                this.tags_id.push(item.id)
            });

            axios
                .get('/api/tags')
                .then(res => this.tags = res.data.data)
                .catch(error => console.log(error))
        },
        methods: {
            cancel() {
                EventBus.$emit('cancelEditing')
            },
            update() {
                axios
                    .put(`/api/question/${this.form.slug}`, {
                        body: this.form.body,
                        title: this.form.title,
                        tags: this.tags_id
                    })
                    .then(res => {
                        EventBus.$emit('updateQuestion', res.data);
                        this.cancel()
                    })
                    .catch(error => this.errors = error.response.data.errors)
            },
            remove(item) {
                const index = this.tags_id.indexOf(item.item.id);
                if (index >= 0) this.tags_id.splice(index, 1)
            }
        },
    }
</script>

<style scoped>
    .question-edit {
        padding: 25px;
    }
</style>