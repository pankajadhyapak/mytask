import VueRouter from 'vue-router';
import Home from './pages/Home.vue';
import About from './pages/About.vue';
import PageNotFound from './pages/NotFound.vue';
import SingleTeam from './pages/Team/show';
import SingleProject from './pages/Project/show'
import ModuleTasks from './pages/Module/AllTask'
import Setting from './pages/Settings'

let routes = [
    {
        path: '/',
        component: Home
    },
    {
        path: '/settings',
        component: Setting
    },
    {
        path: '/about',
        component: About
    },
    {
        path: '/team/:id',
        component: SingleTeam
    },
    {
        path: '/project/:id',
        component: SingleProject,
        children: [
            // {
            //     path: '/',
            //     component: ModuleTasks
            // },
            {
                path: 'module/:module_id',
                component: ModuleTasks
            }
        ]
    },
    {
        path: "*",
        component: PageNotFound
    }

];
export default new VueRouter({
    mode: 'history',
    base: '/dashboard/',
    routes
});
