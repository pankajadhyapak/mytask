/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Vue = require('vue');
import VueRouter from 'vue-router';
import router from './routes';
import VueSelect from 'vue-select';

Vue.directive('tooltip', function(el, binding){
    $(el).tooltip({
        title: binding.value,
        placement: binding.arg,
        trigger: 'hover'
    })
});
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
Vue.component('vselect', VueSelect);
Vue.component('modal-new-team', require('./components/Modals/Team/New'));
Vue.component('my-modal', require('./components/Modals/modal'));
Vue.component('new-one', require('./components/Modals/Team/NewOne'));
Vue.component('user-select', require('./components/user-select'));
Vue.component('project-task', require('./pages/Project/task'));
Vue.component('project-module-card', require('./pages/Project/module'));
Vue.component('flash', require('./components/flash.vue'));

Vue.use(VueRouter);


Vue.prototype.$http = axios.create();

window.eventHub = new Vue();
window.flash = function (message, type="success") {
    window.eventHub.$emit('flash', {"message": message, "type": type});
};

Vue.mixin({
    data(){
        return {
            eventHub: window.eventHub,
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
        formNow(time, ist = true){
            if(time){
                if(ist){
                    return moment(time).add({ hours: 5, minutes: 30}).fromNow();
                }
                return moment(time).fromNow();
            }
        }
    }
});
new Vue({
    el: '#app',
    router
});
