<template>
    <v-container>
        <v-form @submit.prevent="submit">
            <v-text-field
                    v-model="form.name"
                    label="Tag Name"
                    type="text"
                    required
            ></v-text-field>
            <span class="red--text" v-if="errors.name">{{ errors.name[0] }} <br></span>

            <v-btn type="submit" color="pink" v-if="editSlug" :disabled="!form.name">Update</v-btn>
            <v-btn type="submit" color="teal" v-else :disabled="!form.name">Create</v-btn>
        </v-form>

        <v-card class="mt-4">
            <v-toolbar color="indigo" dark dense>
                <v-toolbar-title>tags</v-toolbar-title>
            </v-toolbar>

            <v-list>
                <div v-for="(tag, index) in tags" :key="tag.id">
                    <v-list-tile>

                        <v-list-tile-action>
                            <v-btn icon small @click="edit(index)">
                                <v-icon color="orange">edit</v-icon>
                            </v-btn>
                        </v-list-tile-action>

                        <v-list-tile-content>
                            <v-list-tile-title>
                                {{tag.name}}
                            </v-list-tile-title>
                        </v-list-tile-content>


                        <v-list-tile-action>
                            <v-btn icon small @click="destroy(tag.slug, index)">
                                <v-icon color="red">delete</v-icon>
                            </v-btn>
                        </v-list-tile-action>

                    </v-list-tile>
                    <v-divider></v-divider>
                </div>

            </v-list>
        </v-card>

    </v-container>
</template>

<script>
    export default {
        data() {
            return {
                form: {
                    name: null
                },
                errors: {},
                tags: {},
                editSlug: null
            }
        },
        methods: {
            submit() {
                this.editSlug ? this.update() : this.create()
            },
            create() {
                axios
                    .post('/api/tags', this.form)
                    .then(res => {
                        this.tags.unshift(res.data);
                        this.form.name = null
                    })
                    .catch(error => this.errors = error.response.data.errors)
            },
            update() {
                axios
                    .put(`/api/tags/${this.editSlug}`, this.form)
                    .then(res => {
                        this.tags.unshift(res.data);
                        this.form.name = null
                    })
                    .catch(error => this.errors = error.response.data.errors)
            },
            destroy(slug, index) {
                axios
                    .delete(`/api/tags/${slug}`)
                    .then(res => this.tags.splice(index, 1))
                    .catch(error => console.log(error))
            },
            edit(index) {
                this.form.name = this.tags[index].name;
                this.editSlug = this.tags[index].slug;
                this.tags.splice(index, 1)
            }
        },
        created() {
            axios
                .get('/api/tags')
                .then(res => this.tags = res.data.data)
                .catch(error => console.log(error))
        },
    }
</script>

<style>
    hr {
        margin-top: 0;
        margin-bottom: 0;
    }
</style>