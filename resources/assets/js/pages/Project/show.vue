<template>
    <div class="container">

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
                <button class="btn btn-outline-dark btn-sm float-right">New Module</button>
            </div>
        </div>

        <div class="module-container mb-3" v-for="module in project.modules" :key="module.id">
            <project-module-card :module="module"></project-module-card>
        </div>
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
            vm.fetchProject(this.$route.params.id);
        },
        data(){
            return {
                project:{},
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
                this.fetchProject(to.params.id);
            }
        },
        computed: {

        },
        methods:{
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
