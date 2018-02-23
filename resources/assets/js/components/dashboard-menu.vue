<template>
    <div class="col-md-3">
    <nav>
        <div class="card card-default">
            <div class="card-header"><i class="fa fa-users" aria-hidden="true"></i>
                Teams <span>({{ teams.length}})</span>
                <button
                        class="btn btn-outline-dark btn-sm float-right"
                        @click="showNewTeamModal = true"
                >New Team</button>
            </div>


            <div class="card-body">
                <ul class="list-unstyled">
                    <li v-for="(team, index) in teams" :key="index">
                        <router-link :to="{path: '/dashboard/team/' + team.id}">
                            <span>{{team.name}}</span>
                            <small><span class="float-right">New Project</span></small>
                        </router-link>

                        <ul class="ml-2 list-unstyled">
                            <li v-for="(project, index) in team.projects" :key="index">
                                <router-link :to="{path: '/dashboard/project/' + project.id}">
                                    {{project.name}}
                                </router-link>
                            </li>
                        </ul>

                    </li>

                </ul>
            </div>
        </div>
    </nav>
        <modal-new-team v-if="showNewTeamModal" @closed="newTeamModalClosed()"></modal-new-team>
        <new-one v-if="showNewModal" @closed="showNewModal = false"></new-one>
    </div>

</template>

<script>
    export default {
        data(){
            return {
                teams: [],
                showNewTeamModal: false,
                showNewModal: false,
            }
        },
        mounted() {
            let vm =this;
            vm.getDashboardData();
        },
        methods:{
            newTeamModalClosed(){
                this.showNewTeamModal = false;
            },
            getDashboardData(){
                let vm = this;
                axios.get("/api/dashboard/init").then(function (response) {
                    vm.teams = response.data;
                })
            }
        }
    }
</script>
