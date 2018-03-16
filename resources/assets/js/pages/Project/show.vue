<template>
    <div class="container" v-if="project.modules">

        <div class="d-flex align-items-center">
            <div class="project-title">
                <h4>{{ project.name}} Project - <small class="text-muted">{{ project.modules.length}} Modules</small></h4>
            </div>
            <div class="filter-btns">
                <div class="dropdown">
                  <button class="btn btn-secondary btn-sm mr-2 dropdown-toggle text-capitalize" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fa fa-filter mr-1" aria-hidden="true"></i>
                      {{filterType}}
                  </button>

                  <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                    <button @click="filterBy('all')" class="dropdown-item" type="button">All Tasks</button>
                    <button @click="filterBy('complete')" class="dropdown-item" type="button">Completed Tasks</button>
                    <button @click="filterBy('in complete')" class="dropdown-item" type="button">In Complete Tasks</button>
                  </div>
                </div>
                <button class="btn btn-outline-dark btn-sm float-right" data-toggle="collapse" data-target="#addModule">New Module</button>

            </div>

        </div>
        <div class="collapse mt-3 mb-3" id="addModule">
            <div class="card card-body">
                <div class="row">
                    <div class="col-md-6">
                        <input type="text" placeholder="Module Name" class="form-control" v-model="newModule.name">
                    </div>
                    <div class="col-md-6">
                        <button
                                @click="addNewModule()"
                                :disabled="!newModule.name"
                                class="btn btn-outline-dark">Add Module</button>
                    </div>
                </div>

            </div>
        </div>

        <div class="module-container mb-3" v-for="module in project.modules" :key="module.id">
            <project-module-card :module="module"></project-module-card>
        </div>

        <router-view/>
    </div>
</template>
<style>

    .complete-icon{
        color:#333;
        font-size: 18px;
    }
    .complete-icon:hover{
        color: #28a745;
    }
    .completed-task .complete-icon{
        color: #28a745;
    }
    .task {
        padding: 10px;
        border: 1px dashed #b1afaf;
        cursor: pointer;
    }
    .module-heading{
        box-shadow: none;
        border-radius: 0;
        background: #dcdcdc;
    }

    .avatar.float-right {
        position: relative;
        right: 0;
        top: -4px;
    }

    .completed-task {
        color: #28a745 !important;
        opacity: .6;
    }
    .project-title{
        display: flex;
        align-items: left;
        justify-content: flex-start;
        flex: 1;
    }
    .filter-btns{
        display: flex;
        align-items: center;
        flex: 1;
        justify-content: flex-end;
    }
</style>
<script>

    export default {
        mounted() {
            let vm = this;
            console.log("Show Vue");
            //mixpanel.track("project_page");
            vm.fetchProject(this.$route.params.id);
            vm.eventHub.$on("moduleDeleted", function (e) {
                vm.fetchProject(vm.$route.params.id);
            });
        },
        data(){
            return {
                project:{},
                newModule:{
                    name:''
                },
                dataLoaded: false,
                showNewTaskModal: false,
                currentModuleId: null,
                showViewTaskModal: false,
                quickTask: "",
                filterType: "all",
                markCompleteMsg:"Mark Done",
                markInCompleteMsg: "Mark InComplete"
            }
        },
        watch: {
            '$route' (to, from) {
                console.log(to.params);
                console.log(from.params);
                $('#viewTask').modal('hide');

                //TODO reduce network call
                if(!to.params.task_id){
                    if(!this.project.modules || this.project.id != to.params.id){
                        this.fetchProject(to.params.id);
                    }
                }
            }
        },
        methods:{
            async addNewModule(){
                try{
                    const response = await axios.post("/api/"+this.project.id+"/module", this.newModule);
                    console.log(response);
                    this.project.modules.push(response.data);
                    flash("Module Created");
                    this.newModule.name = "";
                    $('#addModule').collapse('hide');
                }catch (error){
                    flash("Unable to create Module", 'danger');
                    console.error(error)
                }
            },
            filterBy(type){
                this.filterType = type;
                this.eventHub.$emit("filterBy", type);
            },
            fetchProject(id){
                let vm = this;
                axios.get("/api/project/"+id).then(function (response) {
                    vm.project = response.data;
                    vm.dataLoded = true;
                }, function (error) {
                    vm.dataLoded = false;
                    //vm.$router.go('/dashboard/404');
                });
            }
        }
    }
</script>
