<template>
    <v-card class="mt-2">
        <v-layout row wrap>
            <v-flex xs2>
                <div class="question-stat">
                    <div class="rating">
                        <div class="count">{{ question.vote_count }}</div>
                        <div>rating</div>
                    </div>

                    <div class="answers">
                        <div class="count">{{ question.reply_count }}</div>
                        <div>answers</div>
                    </div>

                    <div class="views">
                        <div class="count">{{ question.views }}</div>
                        <div>views</div>
                    </div>
                </div>

                <div class="question-tags">
                    <router-link class="question-tag" :to="question.path">laravel</router-link>
                    <router-link class="question-tag" :to="question.path">php</router-link>
                    <router-link class="question-tag" :to="question.path">javascript</router-link>
                    <router-link class="question-tag" :to="question.path">vuejs</router-link>
                </div>
            </v-flex>
            <v-flex xs10>
                <v-card-title primary-title>
                    <div>
                        <h3 class="headline mb-0">
                            <router-link :to="question.path">
                                {{ question.title }}
                            </router-link>
                        </h3>
                        <div class="grey--text">
                            {{ question.user }} said
                            {{ question.created_at }}
                        </div>
                    </div>
                </v-card-title>
                <v-card-text>
                    {{ question.body }}
                </v-card-text>
            </v-flex>
        </v-layout>
    </v-card>
</template>

<script>
    export default {
        props: ['question']
    }
</script>

<style scoped>
    .rating, .answers, .views {
        display: inline-block;
        height: 38px;
        min-width: 38px;
        margin: 0 7px 0 0;
        font-size: 11px;
        color: #565c61;
        padding: 7px 6px;
        text-align: center;
    }

    .count {
        font-size: 22px;
        font-weight: 300;
        color: #4d4f52;
        margin-bottom: 4px;
    }

    .question-stat {
        padding-top: 19px;
        padding-left: 16px;

    }

    .question-tag {
        position: relative;
        display: inline-block;
        padding: .4em .5em;
        margin: 2px 2px 2px 0;
        font-size: 11px;
        line-height: 1;
        white-space: nowrap;
        text-decoration: none;
        text-align: center;
        border-width: 1px;
        border-style: solid;
        border-radius: 3px;
        transition: all .15s ease-in-out;

        color: #39739d;
        background-color: #E1ECF4;
        border-color: #E1ECF4;
    }

    .question-tags {
        padding-top: 12px;
        padding-left: 18px;
    }

    .v-card{
        margin-bottom: 20px;
    }
</style>