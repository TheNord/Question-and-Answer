<template>
    <v-container>
        <v-form @submit.prevent="submit">
            <v-text-field
                    v-model="form.name"
                    label="Category Name"
                    type="text"
                    required
            ></v-text-field>
            <span class="red--text" v-if="errors.name">{{ errors.name[0] }} <br></span>

            <v-btn type="submit" color="pink" v-if="editSlug">Update</v-btn>
            <v-btn type="submit" color="teal" v-else>Create</v-btn>
        </v-form>

        <v-card class="mt-4">
            <v-toolbar color="indigo" dark dense>
                <v-toolbar-title>Categories</v-toolbar-title>
            </v-toolbar>

            <v-list>
                <div v-for="(category, index) in categories" :key="category.id">
                    <v-list-tile>

                        <v-list-tile-action>
                            <v-btn icon small @click="edit(index)">
                                <v-icon color="orange">edit</v-icon>
                            </v-btn>
                        </v-list-tile-action>

                        <v-list-tile-content>
                            <v-list-tile-title>
                                {{category.name}}
                            </v-list-tile-title>
                        </v-list-tile-content>


                        <v-list-tile-action>
                            <v-btn icon small @click="destroy(category.slug, index)">
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
                categories: {},
                editSlug: null
            }
        },
        methods: {
            submit() {
                this.editSlug ? this.update() : this.create()
            },
            create() {
                axios
                    .post('/api/category', this.form)
                    .then(res => {
                        this.categories.unshift(res.data);
                        this.form.name = null
                    })
                    .catch(error => this.errors = error.response.data.errors)
            },
            update() {
                axios
                    .put(`/api/category/${this.editSlug}`, this.form)
                    .then(res => {
                        this.categories.unshift(res.data);
                        this.form.name = null
                    })
                    .catch(error => this.errors = error.response.data.errors)
            },
            destroy(slug, index) {
                axios
                    .delete(`/api/category/${slug}`)
                    .then(res => this.categories.splice(index, 1))
                    .catch(error => console.log(error))
            },
            edit(index) {
                this.form.name = this.categories[index].name;
                this.editSlug = this.categories[index].slug;
                this.categories.splice(index, 1)
            }
        },
        created() {
            axios
                .get('/api/category')
                .then(res => this.categories = res.data.data)
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