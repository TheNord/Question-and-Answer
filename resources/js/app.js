require('./bootstrap');

window.Vue = require('vue');

import Vue from 'vue'
import Vuetify from 'vuetify'
import VueSimplemde from 'vue-simplemde'
import md from 'marked'
import User from './Helpers/User'
import Exception from "./Helpers/Exception";
import VueToasted from 'vue-toasted';

Vue.use(Vuetify);
Vue.use(VueSimplemde);
Vue.use(VueToasted, {
    iconPack : 'material'
});

window.md = md;
window.User = User;
window.Exception = Exception;

window.EventBus = new Vue();

User.checkAuth();


// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('AppHome', require('./components/AppHome.vue').default);

import router from './Router/router.js'

const app = new Vue({
    el: '#app',
    router
});
