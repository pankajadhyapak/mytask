<template>
    <nav class="col-md-3">
        <div class="card-header dashboard-nav mb-2">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link disabled heading" href="#">
                        <i class="fa fa-users" aria-hidden="true"></i>
                        Teams <span>({{ teams.length }})</span>
                        <button class="btn btn-outline-dark btn-sm float-right">New Team</button>

                    </a>
                </li>
                <li class="dropdown-divider"></li>
                <li class="nav-item" v-for="team in teams">
                    <router-link
                            :to="{path: '/dashboard/team/' + team.id}"
                            class="nav-link">
                        {{team.name}}
                    </router-link>
                </li>

            </ul>
        </div>

        <div class="card-header dashboard-nav">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link disabled heading" href="#">
                        <i class="fa fa-cubes" aria-hidden="true"></i>
                        Projects <span>({{ projects.length }})</span>
                        <button class="btn btn-outline-dark btn-sm float-right">New Project</button>
                    </a>
                </li>
                <li class="dropdown-divider"></li>
                <li class="nav-item" v-for="project in projects">
                    <router-link
                            :to="{path: '/dashboard/project/' + project.id}"
                            class="nav-link">
                        {{project.name}}
                    </router-link>
                </li>
            </ul>
        </div>
    </nav>
</template>

<script>
    export default {
        data(){
            return {
                teams:[],
                projects:[]
            }
        },
        mounted() {
            this.getDashboardData();
        },
        methods:{
            getDashboardData(){
                let vm = this;

                axios.get("/api/dashboard/init").then(function (response) {
                    vm.teams = response.data.teams;
                    vm.projects = response.data.projects;
                })
            }
        }
    }
</script>
