<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card card-default">
                    <!--<div class="card-header">About Page</div>-->
                    <div class="card-body" v-if="dataLoaded">
                        <center>
                            <div class="team-avatar avatar">
                                {{ team.name[0] }}
                            </div>
                            <h4 class="mt-2">{{ team.name }} - <small class="text-muted">created by {{ team.owner.display_name}}</small></h4>
                            <h5 class="mt-2">{{ team.members.length }} Members</h5>


                        </center>

                        <ul class="nav nav-pills mb-3 mt-3 nav-justified" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Members</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-settings-tab" data-toggle="pill" href="#pills-settings" role="tab" aria-controls="pills-contact" aria-selected="false">Settings</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="Search Members by email">
                                    </div>
                                    <div class="col-md-6">
                                        <button class="btn btn-success btn-block">Invite Members</button>
                                    </div>
                                </div>
                                <table class="table mt-2" >
                                    <!--<thead>-->
                                    <!--<tr>-->
                                        <!--<th scope="col">-->
                                        <!--</th>-->
                                        <!--<th scope="col">Name</th>-->
                                        <!--<th scope="col">Email</th>-->
                                        <!--<th scope="col">Action</th>-->
                                    <!--</tr>-->
                                    <!--</thead>-->
                                    <tbody>
                                    <tr v-for="member in team.members">
                                        <th>
                                            <div class="avatar">
                                                {{ member.name[0] }}
                                            </div>
                                        </th>
                                        <td class="text-capitalize">{{ member.display_name }}</td>
                                        <td>{{ member.email }}</td>
                                        <td>{{ formatDate(member.pivot.created_at, "MMM d Y") }}</td>
                                        <td >
                                            <button class="btn btn-outline-danger btn-sm" v-if="member.id != team.owner.id">Remove</button>
                                            <span v-else>Owner</span>
                                        </td>
                                    </tr>

                                    </tbody>
                                </table>

                            </div>
                           <div class="tab-pane fade" id="pills-settings" role="tabpanel" aria-labelledby="pills-contact-tab">Settings</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
    .team-avatar.avatar {
        font-size: 60px;
        height: 100px;
        line-height: 100px;
        width: 100px;
    }
</style>
<script>

    export default {
        mounted() {
            let vm = this;
            vm.getTeam(vm.$route.params.id);
        },
        data(){
            return {
                team:{},
                dataLoaded: false
            }
        },
        watch: {
            '$route' (to, from) {
                this.team = this.getTeam(to.params.id);
            }
        },
        methods:{
            getTeam(id){
                let vm = this;
                axios.get("/api/team/"+id).then(function (response) {
                    vm.team = response.data;
                    vm.dataLoaded = true;
                }, function (error) {
                    vm.dataLoaded = false;
                    //vm.$router.go('/dashboard/404');
                });
            }
        }

    }
</script>
