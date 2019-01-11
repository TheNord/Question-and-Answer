<template>
    <v-layout align-center justify-center column xs1 class="rating">

        <svg aria-hidden="true" @click="voteUp" class="iconArrUp" width="36" height="36"
             viewBox="0 0 36 36" :class="{disabled: earlyVoted === 1}">
            <path d="M2 26h32L18 10z"></path>
        </svg>


        <span class="ratingCount">{{count}}</span>

        <svg aria-hidden="true" @click="voteDwn" class="iconArrDwn" width="36" height="36"
             viewBox="0 0 36 36" :class="{disabled: earlyVoted === 0}">
            <path d="M2 10h32L18 26z"></path>
        </svg>

        <v-btn icon>
            <v-icon color="orange darken-2">star</v-icon>
        </v-btn>

    </v-layout>
</template>

<script>
    export default {
        props: ['data'],
        data() {
            return {
                earlyVoted: this.data.voted,
                count: this.data.vote_count
            }
        },
        created() {
            Echo
                .channel('voteChannel')
                .listen('VoteEvent', e => {
                    if (this.data.slug === e.slug) {
                        e.type === 1 ? this.count++ : this.count--
                    }
                });
        },
        methods: {
            likeIt() {
                if (User.loggedIn()) {
                    this.liked ? this.decr() : this.incr();
                    this.liked = !this.liked
                }
            },
            voteUp() {
                if(this.earlyVoted === 0 || this.earlyVoted == null) {
                    axios
                        .post(`/api/voteUp/${this.data.slug}`)
                        .then(res => {
                            if(this.earlyVoted === 0){
                                this.count++;
                            }
                            this.count++;
                            this.earlyVoted = 1
                        })
                        .catch(error => {
                            let toast = this.$toasted.show(error.response.data.error, {
                                theme: "bubble",
                                position: "bottom-left",
                                duration: 5000,
                                icon: {
                                    name: 'error'
                                }
                            });
                        })
                }
            },
            voteDwn() {
                if(this.earlyVoted === 1 || this.earlyVoted == null) {
                    axios
                        .post(`/api/voteDwn/${this.data.slug}`)
                        .then(res => {
                            if(this.earlyVoted === 1){
                                this.count--;
                            }
                            this.count--;
                            this.earlyVoted = 0
                        })
                        .catch(error => {
                            let toast = this.$toasted.show(error.response.data.error, {
                                theme: "bubble",
                                position: "bottom-left",
                                duration: 5000,
                                icon: {
                                    name: 'error'
                                }
                            });
                        })
                }

            }
        },
    }
</script>

<style scoped>
    .ratingCount {
        font-size: 25px;
    }

    .rating {

    }

    .iconArrUp {
        fill: #4d93cc
    }

    .iconArrDwn {
        fill: #4d93cc
    }

    .iconFavorite {
        fill: #ff9a05
    }

    .disabled {
        fill: #92a0ab
    }
</style>