<template>
    <v-container>
        <reply
            v-for="(reply, index) in content"
            :data="reply"
            :key="reply.id"
            :index="index"
            v-if="replies"
        ></reply>
    </v-container>
</template>

<script>
    import Reply from './Reply'

    export default {
        props:['replies', 'questionSlug'],
        components: {
            Reply
        },
        data() {
           return {
               content: this.replies
           }
        },
        created() {
            this.listen()
        },
        methods: {
            listen() {
                EventBus.$on('newReply', (reply) => {
                    this.content.push(reply)
                });

                EventBus.$on('deleteReply', (index) => {
                    axios
                        .delete(`/api/question/${this.questionSlug}/reply/${this.content[index].id}`)
                        .then(res => this.content.splice(index, 1))
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
                });

                Echo.channel('deleteReplyChannel')
                    .listen('DeleteReplyEvent', (e) => {
                        for(let index = 0; index < this.content.length; index++) {
                            if(this.content[index].id === e.id) {
                                this.content.splice(index, 1)
                            }
                        }
                    })
            }
        },
    }
</script>