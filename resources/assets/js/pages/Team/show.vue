<template>
    <div class="container" v-if="dataLoaded">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="meta-avtar flex-center mb-2" style="flex-direction: row">
                    <div class="team-avatar avatar mr-4">
                        {{ team.name[0] }}
                    </div>
                    <div>
                        <h4 class="mt-2">{{ team.name }} -
                            <small class="text-muted">created by {{ team.owner.display_name}}</small>
                        </h4>
                        <div style="display: flex;align-items: center;justify-content: center;">
                            <h5 class="mt-2">
                                {{ team.members.length }} Members
                            </h5>
                            <button class="ml-4 btn btn-outline-success btn-sm float-right"
                                    data-toggle="collapse" data-target="#addNewMembers"
                            >Add Members
                            </button>
                        </div>
                    </div>
                </div>
                <div class="collapse" id="addNewMembers" style="padding: 0 22px;">
                    <div class="card card-default">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" v-model="usersearchKey">
                                </div>
                                <div class="col-md-6">
                                    <button
                                            :disabled="!usersearchKey"
                                            @click="searchUsers()"
                                            :class="{ loader : isLoadingSearch}"
                                            class="btn btn-outline-dark">Search
                                    </button>
                                </div>
                            </div>
                            <hr>
                            <table class="table mt-2 table table-striped">
                                <tbody>
                                <tr v-for="user in searchedUsers">
                                    <th>
                                        <div class="avatar">
                                            {{ user.name[0] }}
                                        </div>
                                    </th>
                                    <td class="text-capitalize">{{ user.display_name }}</td>
                                    <td>{{ user.email }}</td>
                                    <td>Joined {{ formatDate(user.created_at, "MMM d Y") }}</td>
                                    <td>
                                        <button
                                                @click="addToTeam(user)"
                                                class="btn btn-outline-danger btn-sm">
                                            Add to team
                                        </button>
                                    </td>
                                </tr>

                                </tbody>
                            </table>

                            <div class="invite" v-if="searched && !searchedUsers.length">
                                <div class="row">
                                    <div class="col-md-3">
                                        <p style="margin-bottom: 0;margin-top: 8px;">No result found, Invite ?</p>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" v-model="invite.name" class="form-control"
                                               placeholder="Name">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="email" v-model="invite.email" class="form-control"
                                               placeholder="Email">
                                    </div>
                                    <div class="col-md-3">
                                        <button @click="inviteUser()" class="btn btn-outline-success">Invite</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card card-default is-transparent no-shadow">
                    <!--<div class="card-header">About Page</div>-->
                    <div class="card-body" v-if="dataLoaded">
                        <ul class="nav nav-pills mb-3 mt-3 nav-justified" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home"
                                   role="tab" aria-controls="pills-home" aria-selected="true">Members</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-settings-tab" data-toggle="pill" href="#pills-settings"
                                   role="tab" aria-controls="pills-contact" aria-selected="false">Settings</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent"
                             style="background: #fff;box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16);">
                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                 aria-labelledby="pills-home-tab" style="padding:5px 16px;">
                                <table class="table mt-2 table table-striped">
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
                                        <td>
                                            <button @click="removeFromTeam(member)"
                                                    class="btn btn-outline-danger btn-sm"
                                                    v-if="member.id != team.owner.id">Remove
                                            </button>
                                            <span v-else>Owner</span>
                                        </td>
                                    </tr>

                                    </tbody>
                                </table>
                                <hr>
                            </div>
                            <div class="tab-pane fade" id="pills-settings" role="tabpanel"
                                 aria-labelledby="pills-contact-tab" style="padding:25px 16px;">Settings
                            </div>
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
        data() {
            return {
                team: {},
                dataLoaded: false,
                isLoadingSearch: false,
                invite: {
                    name: '',
                    email: '',
                    team_id: this.$route.params.id
                },
                searchedUsers: [],
                searched: false,
                usersearchKey: ''
            }
        },
        watch: {
            '$route'(to, from) {
                this.team = this.getTeam(to.params.id);
            }
        },
        methods: {
            async removeFromTeam(user) {
                try {
                    const response = await axios.delete("/api/" + this.team.id + "/remove-from-team", {data: {user_id: user.id}});
                    flash("Removed " + user.display_name + " from team and marked task as un assigned");
                    this.team.members = _.reject(this.team.members, function (o) {
                        return o.id == user.id;
                    });
                } catch (error) {
                    flash("Error adding user to team", "danger");
                }
            },
            async addToTeam(user) {
                try {
                    const response = await axios.post("/api/" + this.team.id + "/add-to-team", {user_id: user.id});
                    this.searched = false;
                    flash("Added " + user.display_name + " to team");
                    this.searchedUsers = _.reject(this.searchedUsers, function (o) {
                        return o.id == user.id;
                    });
                    user.pivot = success.data;
                    this.team.members.push(user);
                } catch (error) {
                    flash("Error adding user to team", 'danger');
                }
            },
            async inviteUser() {
                try {
                    const response = await axios.post("/api/invite", this.invite);
                    flash("Invited " + this.invite.name + " to team " + this.team.name);
                } catch (error) {
                    flash(error.response.data.message, "danger");
                }
            },
            async searchUsers() {
                try {
                    const response = await axios.get("/api/search/addmember/" + this.team.id + "?search=" + this.usersearchKey);
                    this.searchedUsers = response.data;
                    this.searched = true;
                } catch (error) {
                    console.log(error);
                    alert("Error ");
                }
            },
            async getTeam(id) {
                try {
                    const response = await axios.get("/api/team/" + id);
                    this.team = response.data;
                    this.dataLoaded = true;
                } catch (error) {
                    this.dataLoaded = false;
                }
            }
        }
    }
</script>
