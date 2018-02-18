import VueRouter from 'vue-router';
import Home from './pages/Home.vue';
import About from './pages/About.vue';
import PageNotFound from './pages/NotFound.vue';
import SingleTeam from './pages/Team/show';
import SingleProject from './pages/Project/show'
import ModuleTasks from './pages/Module/AllTask'

let routes = [
    {
        path: '/dashboard',
        component: Home
    },
    {
        path: '/dashboard/about',
        component: About
    },
    {
        path: '/dashboard/team/:id',
        component: SingleTeam
    },
    {
        path: '/dashboard/project/:id',
        component: SingleProject,
        children: [
            {
                path: '/',
                component: ModuleTasks
            },
            {
                path: 'module/:module_id',
                component: ModuleTasks
            }
        ]
    },
    {
        path: "/dashboard/*",
        component: PageNotFound
    }

];
export default new VueRouter({
    mode: 'history',
    routes
});