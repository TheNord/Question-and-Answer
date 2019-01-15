<template>
    <v-card>
        <v-toolbar color="cyan" dark dense>
            <v-toolbar-title>Tags</v-toolbar-title>
        </v-toolbar>

        <v-list>
            <v-list-tile v-for="tag in tags" :key="tag.id">
                <v-list-tile-content>
                    <v-list-tile-title>{{tag.name}}</v-list-tile-title>
                </v-list-tile-content>
            </v-list-tile>
        </v-list>
    </v-card>
</template>

<script>
    export default {
        data() {
            return {
                tags: {}
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