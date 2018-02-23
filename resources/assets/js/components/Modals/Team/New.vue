<template>
    <my-modal @closed="$emit('closed')">
        <template slot="title">Create New Team</template>
        <form @submit.prevent="addNewTeam()" style="padding: 10px">
                        <div class="form-group">
                            <label for="name">Team Name</label>
                            <input type="text"
                                   class="form-control"
                                   id="name"
                                   placeholder="e.g. Marketing, Engineering, or Finance"
                                   name="name"
                                   v-model="newTeam.name"
                                   required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control"
                                      id="description"
                                      name="description"
                                      v-model="newTeam.description"
                                      placeholder="Tell what is your team about">

                                    </textarea>
                        </div>

                        <div class="form-group">
                            <label>Members</label>
                            <vselect multiple :options="options" label="email" v-model="newTeam.members">
                                <template slot="option" slot-scope="option">
                                    <div class="pb-2">
                                        <div class="avatar">
                                            {{option.display_name[0]}}
                                        </div>
                                        {{ option.email }}
                                    </div>
                                </template>
                            </vselect>
                        </div>
                        <button type="submit" class="btn btn-primary">Create Team</button>
                    </form>
    </my-modal>
</template>
<style scoped>

</style>
<script>
    export default {
        mounted() {
            let vm = this;
            axios.get("/api/users").then(function (response) {
                vm.options =  response.data;
            });

        },
        data(){
            return {
                options: [],
                newTeam: {
                    name:'',
                    description:'',
                    members:[]
                }
            }
        },
        methods:{
            addNewTeam(){
                let vm = this;
                axios.post("/api/team", vm.newTeam).then(function(response){
                    console.log(response.data);
                });
            }
        }
    }
</script>
