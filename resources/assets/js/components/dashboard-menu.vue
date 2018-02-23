<template>
    <div class="col-md-3">
    <nav>
        <div class="card card-default ">
            <div class="card-header dashboard-menu"><i class="fa fa-users" aria-hidden="true"></i>
                Teams <span>({{ teams.length}})</span>
                <button
                        class="btn btn-outline-light btn-sm float-right"
                        @click="showNewTeamModal = true"
                >New Team</button>
            </div>


            <div class="card-body dashboard-menu">
                <ul class="list-unstyled">
                    <li v-for="(team, index) in teams" :key="index">
                        <router-link :to="{path: '/dashboard/team/' + team.id}">
                            <span>{{team.name}}</span>

                        </router-link>

                        <ul class="ml-2 list-unstyled">

                            <small>Projects </small>

                            <span class="float-right">
                                <i class="fa fa-plus-circle" aria-hidden="true"></i>
                            </span>

                            <li v-for="(project, index) in team.projects" :key="index">
                                <router-link :to="{path: '/dashboard/project/' + project.id}">
                                    <small>{{project.name}}</small>
                                </router-link>
                            </li>
                        </ul>
                        <hr style="border-top:1px solid rgb(99, 99, 99)">
                    </li>

                </ul>
            </div>
        </div>
    </nav>
        <modal-new-team v-if="showNewTeamModal" @closed="newTeamModalClosed()"></modal-new-team>
        <new-one v-if="showNewModal" @closed="showNewModal = false"></new-one>
    </div>

</template>
<style scoped>
    .dashboard-menu{
        background: #222b37;
        color:#fff;
    }
    .dashboard-menu a{
        color:#fff
    }
    .dashboard-menu li{
        padding:8px
    }
</style>
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
