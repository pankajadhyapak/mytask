<template>
    <div class="col-md-2 mainSidebar-container">
        <aside id="mainSidebar">
            <div class="d-flex align-items-center" style="padding-left: 15px;">
                <div class="left-box">
                    <i class="fa fa-users" aria-hidden="true"></i>
                    &nbsp;Teams <span>({{ teams.length}})</span>
                </div>
                <div class="right-box">
                    <i @click="showNewTeamModal = true"
                        title="Create New Team"
                        v-tippy='{ placement : "bottom" , arrow : true}'
                       class="fa fa-plus-circle"
                       style="font-size: 18px;"
                       aria-hidden="true"></i>
                </div>
            </div>
            <hr>

            <div class="sidebar-menu">
                    <div class="sidebar-menu-item" v-for="(team, index) in teams" :key="index">
                        <router-link
                                :to="{path: '/team/' + team.id}"
                                active-class="active"
                                style="display:block"
                        >
                            <strong class="text-capitalize">{{team.name}}</strong>
                        </router-link>
                        <div class="d-flex align-items-center">
                            <div class="left-box">
                                <small class="text-muted mt-1">
                                    Projects
                                </small>
                            </div>
                            <div class="right-box">
                                <i @click="showProjectModal(team)"
                                    title="Create New Project"
                                    v-tippy='{ placement : "bottom", arrow : true }'
                                   class="fa fa-plus-circle"
                                   style="font-size: 14px;"
                                   aria-hidden="true"></i>
                            </div>
                        </div>


                        <div class="sidebar-menu-item" v-for="(project, pindex) in team.projects" :key="pindex">
                            <router-link
                                    :to="{path: '/project/' + project.id}"
                                    active-class="active-p"
                                    class="text-capitalize"
                                    style="display:block;font-size: 12px;margin-left: 10px;">
                                {{project.name}}
                            </router-link>

                        </div>
                        <hr>
                    </div>
            </div>
        </aside>
        <modal-new-team v-if="showNewTeamModal" @closed="showNewTeamModal = false"/>

        <modal-new-project
                v-if="showNewProjectModal"
                :team="currentTeam"
                @closed="showNewProjectModal = false"/>
    </div>
</template>
<style scoped>
    hr{
        padding: 1px;
    }
    .mainSidebar-container{
        box-shadow: 2px 0 5px #c1c1c1;
        padding-top: 20px;
        padding-bottom: 20px;
    }
    #mainSidebar a {
        color: #495057;
    }
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
    .sidebar-menu-item a.active {
        color: #7746ec !important;
    }
    .sidebar-menu-item {
        padding: 10px 0px;
    }
    .sidebar-menu {
        padding: 10px;
    }
    .active-p{
        background: #7746ec !important;
        color: #fff !important;
        padding: 6px;
    }
</style>
<script>
    export default {
        data(){
            return {
                teams: [],
                showNewTeamModal: false,
                showNewProjectModal: false,
                currentTeam: '',
                showNewModal: false,
            }
        },
        mounted() {
            let vm =this;
            vm.getDashboardData();

            vm.eventHub.$on("newTeamCreated", function (data) {
                if(data){
                    vm.teams.push(data);
                    swal({ title :"Team Created", icon:"success" ,timer:1000});
                }
            });
            vm.eventHub.$on("newProjectCreated", function (data) {
                if(data){
                    let team = _.find(vm.teams, {'id' : data.team_id})
                    console.log(team);
                    if(team){
                        team.projects.push(data);
                        swal({ title :"Project Created", icon:"success" ,timer:1000});
                    }
                }
            });

        },
        methods:{
            showProjectModal(team){
                this.currentTeam = team;
                this.showNewProjectModal = true;
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
