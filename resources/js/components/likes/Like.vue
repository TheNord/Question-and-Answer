<template>
    <div>
        <v-btn icon @click="likeIt">
            <v-icon :color="color">favorite</v-icon>
            {{ count }}
        </v-btn>
    </div>
</template>

<script>
    export default {
        props: ['data'],
        data() {
            return {
                liked: this.data.liked,
                count: this.data.like_count
            }
        },
        computed: {
            color() {
                return this.liked ? 'red' : 'red lighten-4'
            }
        },
        methods: {
            likeIt() {
                if (User.loggedIn()) {
                    this.liked ? this.decr() : this.incr();
                    this.liked = !this.liked
                }
            },
            incr() {
                axios
                    .post(`/api/like/${this.data.id}`)
                    .then(res => this.count++)
                    .catch(error => console.log(error))
            },
            decr() {
                axios
                    .delete(`/api/like/${this.data.id}`)
                    .then(res => this.count--)
                    .catch(error => console.log(error))
            }
        },
    }
</script>

<style scoped>

</style>