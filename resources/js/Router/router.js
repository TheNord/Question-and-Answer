import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter);

import Parallax from '../components/Parallax'
import Login from '../components/login/Login'
import Logout from '../components/login/Logout'
import Signup from '../components/login/Signup'
import Forum from '../components/forum/Forum'
import Read from '../components/forum/Read'
import Create from '../components/forum/Create'
import CreateTag from '../components/tags/CreateTag'

const routes = [
    { path: '/', component: Parallax },
    { path: '/login', component: Login },
    { path: '/logout', component: Logout },
    { path: '/signup', component: Signup },
    { path: '/forum', component: Forum, name: 'forum' },
    { path: '/question/:slug', component: Read, name: 'read' },
    { path: '/ask', component: Create},
    { path: '/tags', component: CreateTag},
];

const router = new VueRouter({
    routes,
    hashbang: false,
    mode: 'history'
});

export default router