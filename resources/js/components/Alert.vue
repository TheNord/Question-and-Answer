<template>
    <v-layout row justify-center>
        <v-dialog
                v-model="alert.show"
                max-width="290"
        >
            <v-card>
                <v-card-title class="headline">{{alert.header}}</v-card-title>

                <v-card-text>
                    {{alert.text}}
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>

                    <v-btn
                            color="green darken-1"
                            flat="flat"
                            @click="cancel"
                    >
                        Cancel
                    </v-btn>

                    <v-btn
                            color="green darken-1"
                            flat="flat"
                            @click="confirm"
                    >
                        Confirm
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-layout>
</template>

<script>
    export default {
        props: ['alert'],
        methods: {
            cancel() {
                EventBus.$emit('alert-canceled')
            },
            confirm() {
                EventBus.$emit('alert-confirmed')
            }
        },
    }
</script>