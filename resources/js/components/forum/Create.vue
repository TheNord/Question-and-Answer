<template>
    <v-container>
        <v-form @submit.prevent="create">
            <v-text-field
                    v-model="form.title"
                    label="Title"
                    type="text"
                    required
            ></v-text-field>
            <span class="red--text" v-if="errors.title">{{ errors.title[0] }}</span>

            <!--<v-autocomplete-->
            <!--:items="categories"-->
            <!--v-model="form.tags_id"-->
            <!--item-text="name"-->
            <!--item-value="id"-->
            <!--label="Tag"-->
            <!--multiple-->
            <!--autocomplete-->
            <!--&gt;-->
            <!--</v-autocomplete>-->

            <v-autocomplete
                    :items="tags"
                    v-model="form.tags_id"
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
            <span class="red--text" v-if="errors.body">{{ errors.body[0] }} <br></span>

            <v-btn
                    color="green"
                    type="submit"
                    :disabled="disabled"
            >Create</v-btn>
        </v-form>
    </v-container>
</template>

<script>
    export default {
        data() {
            return {
                form: {
                    title: null,
                    tags_id: [],
                    body: null
                },
                tags: [],
                errors: {}
            }
        },
        created() {
            axios
                .get('/api/tags')
                .then(res => this.tags = res.data.data)
                .catch(error => console.log(error))
        },
        methods: {
            create() {
                axios
                    .post('/api/question', this.form)
                    .then(res => this.$router.push(res.data.path))
                    .catch(error => this.errors = error.response.data.errors)
            },
            remove(item) {
                const index = this.form.tags_id.indexOf(item.item.id);
                if (index >= 0) this.form.tags_id.splice(index, 1)
            }
        },
        computed:{
            disabled() {
                return !(this.form.title && this.form.tags_id && this.form.body)
            }
        }
    }
</script>

<style scoped>

</style>