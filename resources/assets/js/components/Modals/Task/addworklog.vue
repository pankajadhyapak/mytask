<template>
    <div class="modal docked docked-right in" id="addWorkLog" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header ">
                    <h4>Add Work Log</h4>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="addWorkLog()" style="padding: 10px">
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
                            <user-select v-model="newTeam.members">
                            </user-select>
                        </div>
                        <button type="submit" class="btn btn-primary">Create Team</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</template>
<style scoped>

</style>
<script>
    export default {
        mounted() {
            let vm = this;
            let modal = $('#addWorkLog');
            modal.modal();
            modal.on('hidden.bs.modal', function (e) {
                vm.eventHub.$emit('addWorkLog');
            });
            axios.get("/api/users").then(function (response) {
                vm.options =  response.data;
            });
        },
        data(){
            return {
                options: [],
                closeModal: false,
                createdTeam: null,
                newTeam: {
                    name:'',
                    description:'',
                    members:[]
                }
            }
        },
        methods:{
            async addWorkLog(){
                try{
                    const response = await axios.post("/api/team", this.newTeam);
                    this.createdTeam = response.data;
                    this.eventHub.$emit("newTeamCreated", response.data);
                }catch (error){
                    flash("Error Adding Work log", "danger");
                }
                this.closeModal = true;
            }
        }
    }
</script>
