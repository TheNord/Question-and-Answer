<template>
    <v-container>
        <v-form @submit.prevent="login">

            <v-text-field
                    v-model="form.email"
                    label="Email"
                    type="email"
                    required
            ></v-text-field>
            <span class="red--text" v-if="errors.email">{{ errors.email[0] }}</span>

            <v-text-field
                    v-model="form.password"
                    label="Password"
                    type="password"
                    required
            ></v-text-field>
            <span class="red--text" v-if="errors.password">{{ errors.password[0] }} <br></span>

            <v-btn
                    color="green"
                    type="submit"
            >Login</v-btn>

            <router-link to="/signup">
                <v-btn color="blue">Sign Up</v-btn>
            </router-link>

        </v-form>
    </v-container>

</template>

<script>
    export default {
        data() {
            return {
                form: {
                    email: null,
                    password: null
                },
                errors: {}
            }
        },
        created() {
          if (User.loggedIn()) {
              this.$router.push({name: 'forum'});
          }
        },
        methods: {
            login() {
                axios
                    .post('/api/auth/login', this.form)
                    .then(res => User.responseAfterLogin(res))
                    .catch(error => this.errors = error.response.data.errors)
            }
        }
    }
</script>

<style scoped>

</style>