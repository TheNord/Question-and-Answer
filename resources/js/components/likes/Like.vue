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
        created() {
            Echo
                .channel('likeChannel')
                .listen('LikeEvent', e => {
                    if(this.data.id === e.id) {
                        e.type === 1 ? this.count++ : this.count --
                    }
                });
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
                    .catch(error => {
                        let toast = this.$toasted.show(error.response.data.error, {
                            theme: "bubble",
                            position: "bottom-left",
                            duration : 5000,
                            icon : {
                                name : 'error'
                            }
                        });
                    })
            },
            decr() {
                axios
                    .delete(`/api/like/${this.data.id}`)
                    .then(res => this.count--)
                    .catch(error => {
                        let toast = this.$toasted.show(error.response.data.error, {
                            theme: "bubble",
                            position: "bottom-left",
                            duration : 5000,
                            icon : {
                                name : 'error'
                            }
                        });
                    })
            }
        },
    }
</script>

<style scoped>

</style>