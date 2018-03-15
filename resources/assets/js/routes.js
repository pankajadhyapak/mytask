import VueRouter from 'vue-router';
import Home from './pages/Home.vue';
import About from './pages/About.vue';
import PageNotFound from './pages/NotFound.vue';
import SingleTeam from './pages/Team/show';
import SingleProject from './pages/Project/show'
import ModuleTasks from './pages/Module/AllTask'
import Setting from './pages/Settings'
import ProjectRoot from './pages/Project/ProjectRoot'
import ModalSingleTask from './components/Modals/Task/view-r'
import ReportPage from './pages/report'
import FilePage from './pages/FilePage'

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
        component: ProjectRoot,
        children: [
            {
                path: '/',
                redirect: 'tasks',
            },
            {
                path: 'report',
                component: ReportPage
            },
            {
                path: 'files',
                component: FilePage
            },
            {
                path: 'tasks',
                component: SingleProject,
                children:[
                    {
                        path: ':task_id',
                        component: ModalSingleTask
                    }
                ]
            },
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
