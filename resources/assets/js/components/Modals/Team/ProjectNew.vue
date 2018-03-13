<template>
    <my-modal
            @closed="$emit('closed')"
            name="newProject"
            :close="closeModal">

        <template slot="title">Create New Project in {{ this.team.name }} Team</template>

        <form @submit.prevent="addNewProject()" style="padding: 10px">
                        <div class="form-group">
                            <label for="name">Project Name</label>
                            <input type="text"
                                   class="form-control"
                                   id="name"
                                   placeholder="e.g. Marketing, Engineering, or Finance"
                                   name="name"
                                   v-model="newProject.name"
                                   required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control"
                                      id="description"
                                      name="description"
                                      v-model="newProject.description"
                                      placeholder="Tell what is your team about">

                                    </textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Create Project</button>
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
                vm.allUsers =  response.data;
            });

        },
        props:['team'],
        data(){
            return {
                allUsers: [],
                closeModal: false,
                createdProject: null,
                newProject: {
                    name:'',
                    description:'',
                }
            }
        },
        methods:{
            addNewProject(){
                let vm = this;
                axios.post("/api/team/"+vm.team.id+"/project", vm.newProject)
                    .then((response) => {
                        vm.createdProject = response.data;
                        vm.eventHub.$emit("newProjectCreated", response.data);
                    }, (error) => {

                });
                vm.closeModal = true;
            }
        }
    }
</script>
