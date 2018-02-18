
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Vue = require('vue');
import VueRouter from 'vue-router';
import router from './routes';
import moment from 'moment';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('nav-component', require('./components/nav.vue'));
Vue.component('dashboard-menu', require('./components/dashboard-menu.vue'));
Vue.component('modal-task-new', require('./components/Modals/Task/New'));
Vue.component('modal-task-view', require('./components/Modals/Task/view'));

Vue.use(VueRouter);
Vue.prototype.$http = axios.create();
const eventHub = new Vue();

Vue.mixin({
    data(){
        return {
            eventHub: eventHub,
            baseUrl: window.app.baseUrl,
            authUser : window.app.loggedInUser
        }
    },
    methods:{
        _dis(value, placeHolder = "-"){
            return value ? value : placeHolder;
        },
        formatDate(time, format = 'D/M/Y'){
            if(time){
                return moment(time).format(format);
            }
        },
        formNow(time){
            if(time){
                return moment(time).fromNow();
            }
        }
    }
});
new Vue({
    el: '#app',
    router: router
});
